{**
 * NOTICE OF LICENSE
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 * You must not modify, adapt or create derivative works of this source code
 *  @author    Claire-Aline Haestie
 *  @copyright 2023 Claire-Aline Haestie
 *  @license   LICENSE.txt
 *}
 
<input type="hidden" id="favorite_product_save" value="{$link->getModuleLink('favoriteproducts', 'ajax', ['action' => 'add-to-favorite-products', 'id_attribute' => $id_attribute, 'id_product' => $id_product])|escape:'html':'UTF-8'}" />
<input type="hidden" id="id_user" value="{$id_user|escape:'html':'UTF-8'}" />
<span class="link-item custom-favorite">
    <i class="material-icons">star</i>
    Ma liste d'envies
</span>
