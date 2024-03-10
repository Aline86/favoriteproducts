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

document.addEventListener("DOMContentLoaded", function() {
    if(document.getElementById("favorite_product_save") != undefined){
        document.querySelector(".custom-favorite").addEventListener("click", function() {
            postData(document.getElementById("favorite_product_save").value);
        })
    } 
    if(document.getElementById("add_all_to_favorites") != undefined){
        document.getElementById("add_all_to_favorites").addEventListener("click", function(e) {
            postData(document.getElementById("add_all_to_favorites").getAttribute('data-value'));
            divPopupElementInside = '<a class="close-favorite">&times;</a>';
            divPopupElementInside += '<span>Vos produits sont en train d\'être ajoutés. Attendez quelques secondes, la page va s\'actualiser</span>';
            triggerPopup(divPopupElementInside);
        })
      
    } 
})
var triggerPopup = (messageHTML) => {
    let divPopupElement = document.createElement("div");
    divPopupElement.setAttribute('id', 'favorite-popup1');
    divPopupElement.setAttribute('class', 'overlay-favorite');
    let divPopupElementInside = document.createElement("div");
    divPopupElementInside.setAttribute('class', 'favorite-popup');
    divPopupElementInside.innerHTML += messageHTML;
    divPopupElement.appendChild(divPopupElementInside);
    document.querySelector("body").appendChild(divPopupElement);
    document.querySelector(".close-favorite").addEventListener("click", function(e) {
        console.log("e.target", e.target)
        e.target.parentElement.parentElement.remove();
    })
}
var postData = (url, data = null) => {
    console.log("url", url)
    $.ajax({
        url: url,
        type: "POST",
        success: function (dataoutput) {
           
            if(document.getElementById("id_user") != undefined && id_user.value === "0"){
                divPopupElementInside = '<a class="close-favorite">&times;</a>';
                divPopupElementInside += '<span>Pour ajouter un produit à votre liste d\envies, veuillez vous connecter via le lien suivant : </span>';
                divPopupElementInside += '<a href="' + document.querySelector(".user-info a").getAttribute('href') + '" title="connexion">connexion</a>';
                triggerPopup(divPopupElementInside);
            }
            else if (document.getElementById("id_user") != undefined && id_user.value !== "0"){
                divPopupElementInside= '<a class="close-favorite">&times;</a>';
                divPopupElementInside+= '<span>Le produit a bien été ajouté à votre liste d\'envies</span>';
                triggerPopup(divPopupElementInside);
            }
            else if(document.querySelector(".add_favorite_to_cart") != undefined ) {
                window.location.reload();
            }
        }
    })
}
