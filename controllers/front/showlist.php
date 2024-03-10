<?php
/**
 * NOTICE OF LICENSE
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 * You must not modify, adapt or create derivative works of this source code
 *  @author    Claire-Aline Haestie
 *  @copyright 2024 Claire-Aline Haestie
 *  @license   LICENSE.txt
 */
class FavoriteProductsShowlistModuleFrontController extends ModuleFrontController
{
   
    /**
     * @see FrontController::initContent()
     *
     * @return void
     */
    public function initContent()
    {
        parent::initContent();
        // Construit un tableau avec infos produits et création de liens de redirections
        $products = [];
        $id_customer = (int) $this->context->cookie->id_customer;
        // Récupère les produits de la table custom
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('favorite_products', 'fp');
        $sql->where('fp.id_user = ' . $id_customer);
        $favorite_products = Db::getInstance()->executeS($sql);
        if (!is_bool($favorite_products)) {
            foreach ($favorite_products as $key => $data) {
                $product_id = $data['id_product'];
                $attribute_id = $data['id_attribute'];
                $product = new Product((int)$product_id, false, $this->context->cookie->id_lang);
                $img = $product->getCover($product_id);           
                $img_url = $this->context->link->getImageLink($product->link_rewrite, $img['id_image'], 'cart_default'); 
                $products[$product_id . '-' . $attribute_id ]['img_url'] = $img_url;
                $products[$product_id . '-' . $attribute_id ]['name'] = $product->name;
                // Récupère les infos sur les reductions
                $sql = new DbQuery();
                $sql->select('*');
                $sql->from('specific_price', 'sp');
                $sql->where('sp.id_product = ' . $data['id_product']);
                $price = Db::getInstance()->executeS($sql);
                if (empty($price) && count($price) === 0) {
                    $products[$product_id . '-' . $attribute_id ]['price'] = $product->price;
                }
                else {
                    $products[$product_id . '-' . $attribute_id ]['price'] = $product->price * (1 - $price[0]['reduction']);
                }
                $products[$product_id . '-' . $attribute_id ]['id_product'] = $product_id;
                // Récupère l'attribut
                $sql1 = new DbQuery();
                $sql1->select('pac.id_product_attribute, al.name');
                $sql1->from('attribute_lang', 'al');
                $sql1->innerJoin('product_attribute_combination', 'pac', 'pac.id_attribute = al.id_attribute');
                $sql1->where('pac.id_product_attribute = ' . $attribute_id . ' AND al.id_lang = ' . $this->context->cookie->id_lang);
                $favorite_product_attribute = Db::getInstance()->executeS($sql1);
                if (empty($favorite_product_attribute) && count($favorite_product_attribute) === 0) {
                    $params = [
                        'add' => 1,
                        'id_product' => $product_id,
                    ];
                    $link = $this->context->link->getPageLink('cart', true, null, $params,false);
                    $products[$product_id . '-' . $attribute_id]['link'] = $link;
                }
                else if (isset($favorite_product_attribute) && count($favorite_product_attribute) > 0) 
                {
              
                    // Ajoute le nom de l'attribut à l'item produit du tableau
                    foreach ($favorite_product_attribute as $irrelevant_key => $data_attribute) {
                        $products[$product_id . '-' . $data_attribute['id_product_attribute']]['data_attribute'] = $data_attribute['name'];
                        $params = [
                            'add' => 1,
                            'id_product' => $product_id,
                            'id_product_attribute' => $data_attribute['id_product_attribute'],
                        ];
                        $link = $this->context->link->getPageLink('cart', true, null, $params,false);
                        $products[$product_id . '-' . $data_attribute['id_product_attribute']]['id_attribute'] = $data_attribute['id_product_attribute'];
                        $products[$product_id . '-' . $data_attribute['id_product_attribute']]['link'] = $link;
                    }
                }
            }
        }

        $this->context->smarty->assign([
            'products' => $products
        ]);

        $this->setTemplate('module:favoriteproducts/views/templates/front/showlist.tpl');
  
    }    
}



        