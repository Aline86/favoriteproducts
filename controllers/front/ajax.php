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
class FavoriteProductsAjaxModuleFrontController extends ModuleFrontController
{
   
    /**
     * @see FrontController::initContent()
     * 
     * @return void
     */
    public function initContent()
    {
        // Gère l'insertion des datas produits en BDD
        $this->ajax = true;
        parent::initContent();
        $id_customer = (int) $this->context->cookie->id_customer;
        if (Tools::getValue('action') === 'add-to-favorite-products') {
            if($id_customer  !== 0) {
                Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('favorite_products', [
                    'id_user' => $id_customer ,
                    'id_product' => Tools::getValue('id_product'),
                    'id_attribute' => Tools::getValue('id_attribute'),
                ], false, true, Db::INSERT_IGNORE);
            }
            else {
                Context::getContext()->cookie->__set('outside_cart', TRUE);
            }
        }   
    }    
}



        