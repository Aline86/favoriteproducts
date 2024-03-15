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
class FavoriteProductsAddtocartModuleFrontController extends ModuleFrontController
{
   
    /**
     * @see FrontController::initContent()
     *
     * @return void
     */
    public function initContent()
    {
        // Permet l'ajout des produits de la liste d'envies au panier
        parent::initContent();
        // Code custom élabroré en étudiant le code source de Prestashop (CartController.php, Cart.php) et en me basant sur le fonctionnent des links
        $id_customer = (int)Context::getContext()->cookie->id_customer;
        $customer = new Customer((int) $id_customer);
        $this->ajax = true;
        if (Tools::getValue('action') === 'add-all-to-favorites') {
            if(!Context::getContext()->cart->id){
                $cart = new Cart();
            }
            else
            {
                $cart = new Cart($this->context->cart->id); 
            }
            $cart->id_shop = (int)Context::getContext()->shop->id;
            $cart->id_shop_group = (int)Context::getContext()->shop->getContextShopGroupID();
            $cart->id_currency = Context::getContext()->cart->id_currency;
            $cart->id_lang = Context::getContext()->cookie->id_lang;
            $cart->id_customer = $id_customer ;
            if (!Customer::customerHasAddress((int) $id_customer, (int) $cart->id_address_delivery)) {
                $cart->id_address_delivery = (int) Address::getFirstCustomerAddressId((int) $cart->id_customer);
            }
            if (!Customer::customerHasAddress((int) $id_customer, (int) $cart->id_address_invoice)) {
                $cart->id_address_invoice = (int) Address::getFirstCustomerAddressId((int) $cart->id_customer);
            }
            if(!Context::getContext()->cart->id){
                $cart->add();
                Context::getContext()->cart = $cart;
                $this->context->cookie->id_cart = (int)($cart->id);    
                Context::getContext()->cart->update();
                $cart = Context::getContext()->cart;
            }
            if ($cart->id_customer) {
                $cart->secure_key = $customer->secure_key;
            }
            $sql = new DbQuery();
            $sql->select('*');
            $sql->from('favorite_products', 'fp');
            $sql->where('fp.id_user = ' . $id_customer);
            $favorite_products = Db::getInstance()->executeS($sql);
            foreach ($favorite_products as $key => $product) {
                $cart->updateQty(
                    (int) 1,
                    (int) $product['id_product'],
                    (int) $product['id_attribute'],
                    false,
                    'up',
                    null,
                    new Shop((int) $cart->id_shop),
                    false,
                    $cart->id_currency
                );
            }
            CartRule::autoAddToCart(Context::getContext());
            $link = Context::getContext()->link->getPageLink('cart', true, null, $favorite_products,false);
            Tools::redirect($link);
        } 
    }    
}



        



 