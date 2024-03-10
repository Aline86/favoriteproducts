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
if (!defined('_PS_VERSION_')) {
    exit;
}
//require_once __DIR__ . '/vendor/autoload.php';
//use PrestaShop\Module\FavoriteProductsController\FavoriteProductsAjaxFrontController;
/**
 * Ephoto picker and synchronization Module.s.
 *
 * @author Einden
 */
class Favoriteproducts extends Module
{
    public function __construct()
    {
        $this->name = 'favoriteproducts';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Claire-Aline Haestie';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
          'min' => '1.7.6',
          'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Favorite Products');
        $this->description = $this->l('Permet de mettre de côté ses produits préférés et de les consulter via une liste accessible sur son compte personnel');
        $this->confirmUninstall = $this->l('Êtes-vous sûr-e de vouloir désinstaller ce module ?');
    }

    const HOOKS = [
        'displayCustomerAccount',
        'displayProductActions',
        'displayHeader',
       
    ];

    public function assignConfiguration()
    {
        // Pas besoin de configuration pour faire fonctionner le module
    }

    public function processConfiguration()
    {
        // Pas besoin de configuration pour faire fonctionner le module
    }

    public function getContent()
    {
        // Pas besoin de configuration pour faire fonctionner le module
    }


    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        parent::_clearCache($this->templateFile);
    }

    public function install()
    {
        return parent::install()
            && $this->_installSql()
            && $this->registerHook(static::HOOKS);
    }

    // Chargement feuilles de style et JS
    public function hookDisplayHeader($params)
    {   $cssUrl = '/modules/favoriteproducts/views/css/favorite_products.css';
        $this->context->controller->registerStylesheet(sha1($cssUrl), $cssUrl, ['media' => 'all', 'priority' => 80]);
        $jsUrl = '/modules/favoriteproducts/views/js/favorite_products.js';
        $this->context->controller->registerJavascript(sha1($jsUrl), $jsUrl, ['position' => 'bottom', 'priority' => 80]);
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->_unInstallSql();
    }

    protected function _installSql()
    {
        //Création d'une table custom pour stocker les items de la liste d'envies
        $sqlInstallFavoriteProducts = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'favorite_products (
                id_user int,
                id_product int,
                id_attribute int
            ) ENGINE=Aria DEFAULT CHARSET=utf8;';
        Db::getInstance()->execute($sqlInstallFavoriteProducts);
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'favorite_products 
            ADD CONSTRAINT uniqueCode UNIQUE (id_user, id_product, id_attribute)');
        
        return true;
    }

    protected function _unInstallSql()
    {
        return  Db::getInstance()->execute('DROP TABLE ' . _DB_PREFIX_ . 'favorite_products');
    }

    public function returnSecureKey()
    {
        return Tools::hash('favoriteProductHash');
    }

    /**
     *
     * @param array $params
     * 
     */
    public function hookDisplayProductActions(array $params)
    {    
        $id_customer = (int) $this->context->cookie->id_customer;
        // Si utilisateur non connecté, stocke en mémoire qu'il doit insérer le produit dans liste d'envies dès connexion
        if($this->context->cookie->__get('outside_cart') === "1") {
            Db::getInstance(_PS_USE_SQL_SLAVE_)->insert('favorite_products', [
                'id_user' => $id_customer,
                'id_product' => Tools::getValue('id_product'),
                'id_attribute' => Tools::getValue('id_product_attribute')
            ], false, true, Db::INSERT_IGNORE);
            unset($this->context->cookie->outside_cart);
        }

        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $this->smarty->assign([
            'id_product' => $id_product,
            'id_attribute' => $id_product_attribute,
            'id_user' => $id_customer
         ]);
        
        return $this->fetch('module:favoriteproducts/views/templates/hook/product/favorite_product_button.tpl');
       
    }

    /**
     * 
     * @param array $params
     *  
     */
    public function hookDisplayCustomerAccount(array $params)
    {
        // Bouton sur compte utilisateur
        $this->smarty->assign([
            'url' => $this->context->link->getModuleLink('favoriteproducts', 'showlist')
        ]);

        return $this->fetch('module:favoriteproducts/views/templates/hook/favorite_product_customer_button.tpl');
    }
}
