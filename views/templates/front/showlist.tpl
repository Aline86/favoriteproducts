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
{extends file='customer/page.tpl'}

{block name='page_content_container'}
    <ul id="to-cart">
        {foreach from=$products key=key item=product}
            {assign var="product_attribute_split" value="-"|explode:$key}
            {if isset($product.data_attribute)}
                <li><a href="{$link->getProductLink($product_attribute_split[0])}"><img src="{$product.img_url}" alt="{$product.name}" /></a>{$product.name} <i>{$product.data_attribute}</i> <b>{number_format($product.price * 1.2,2,',','')}€ TTC </b><a class="add_favorite_to_cart" href="{$product.link}"><i class="material-icons">done</i></a></li>
            {else}
                <li><a href="{$link->getProductLink($product_attribute_split[0])}"><img src="{$product.img_url}" alt="{$product.name}" /></a>{$product.name} <b>{number_format($product.price * 1.2,2,',','')}€ TTC </b><a class="add_favorite_to_cart" href="{$product.link}"> <i class="material-icons">done</i></a></li>
            {/if}
        {/foreach}
    </ul>
    <div id="add_all_to_favorites" data-value="{$link->getModuleLink('favoriteproducts', 'addtocart', ['action' => 'add-all-to-favorites'])|escape:'html':'UTF-8'}">Ajouter tous les produits au panier</div>
{/block}


