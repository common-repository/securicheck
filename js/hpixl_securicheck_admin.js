/* disparition du loader de page quand la page est chargée*/
jQuery(window).load(function () {
    jQuery('.hpixl-securicheck-pre-con').fadeOut('slow');
});

function openOnglet(evt, onglet) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("hpixl-securicheck-admin-audit-tabcontent");

    if (!tabcontent)
        return;

    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(onglet).style.display = "block";
    evt.currentTarget.className += " active";
}

/** 
 * permet de lancer l'animation sur le bouton aprés lancement de l'audit 
 * permet de ne plus pouvoir cliquer sur le bouton quand on lance un audit
 * evite d'en lancer 2 en paralelle
 * */
function lancerAudit(evt) {
    // si on clique sur le bouton de lancement d'un audit
    const btn = document.getElementsByName('btn-submit-audit')
    btn[0].classList.add('activeLoading');
    btn[0].style.cursor = 'progress';
    btn[0].style.pointerEvents = "none";
}

var pageConnexion = document.getElementById("hpixl_securicheck_toggle_page_connexion_url");
if (pageConnexion) {
    pageConnexion.addEventListener("change", function () {
        var checkbox = this;
        var textInputContainer = document.getElementById("hpixl_securicheck_panneau_page_connexion_url");
        if (checkbox.checked) {
            textInputContainer.style.display = "flex";
        } else {
            textInputContainer.style.display = "none";
        }
    });
}

var pageConnexionRedirection = document.getElementById("hpixl_securicheck_toggle_page_connexion_redirection");
if (pageConnexionRedirection) {
    pageConnexionRedirection.addEventListener("change", function () {
        var checkbox = this;
        var textInputContainer = document.getElementById("hpixl_securicheck_panneau_page_connexion_redirection");
        if (checkbox.checked) {
            textInputContainer.style.display = "flex";
        } else {
            textInputContainer.style.display = "none";
        }
    });
}

var notifications = document.getElementById("hpixl_securicheck_toggle_notifications");
if (notifications) {
    notifications.addEventListener("change", function () {
        var checkbox = this;
        var textInputContainer = document.getElementById("hpixl_securicheck_panneau_notifications");
        if (checkbox.checked) {
            textInputContainer.style.display = "flex";
        } else {
            textInputContainer.style.display = "none";
        }
    });
}

var notifications = document.getElementById("hpixl_securicheck_toggle_limite_nombre_audit");
if (notifications) {
    notifications.addEventListener("change", function () {
        var checkbox = this;
        var textInputContainer = document.getElementById("hpixl_securicheck_panneau_limitations");
        if (checkbox.checked) {
            textInputContainer.style.display = "flex";
        } else {
            textInputContainer.style.display = "none";
        }
    });
}