<?php
defined('ABSPATH') || exit;

/*
 * Ajout d'un menu dans la backoffice
 */

// le menu
function hpixl_securicheck_Menu_Admin_Link()
{

    /*nombre d'erreurs du dernier audit*/
    $lastAudit = new hpixl_securicheck_audit(-1, null);
    $nbErreur = $lastAudit->resultats->nb_total_erreurs;
    if (!$nbErreur)
        $nbErreur = 0;
    //le menu prioncipal
    add_menu_page(
        '', // Title of the page
        'Securicheck <span class="update-plugins count-' . $nbErreur . '"><span class="plugin-count">' . $nbErreur . '</span></span>', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        HPIXL_SECURICHECK_SETTINGS_SLUG, // The 'slug' - file to display when clicking the link
        'hpixl_securicheck_render_audit_page', // Display callback
        'dashicons-lock', // Icon
        66 // Priority/position. Just after 'Plugins'
    );


    // On crée un élément de sous menu: Audit
    add_submenu_page(
        HPIXL_SECURICHECK_SETTINGS_SLUG, // slug du Menu Parent
        __('Audit Securicheck', 'securicheck'),
        'Audit <span class="update-plugins count-' . $nbErreur . '"><span class="plugin-count">' . $nbErreur . '</span></span>',  // Menu title, Text Domain(pour la traduction)
        'manage_options',                     // Capabilities (Capacités)
        HPIXL_SECURICHECK_SETTINGS_SLUG,              // Slug du sous menu
        'hpixl_securicheck_render_audit_page'       // affichage de la page
    );

    // On crée un élément de sous menu: Connexions
    add_submenu_page(
        HPIXL_SECURICHECK_SETTINGS_SLUG, // slug du Menu Parent
        'Connexions',  // Titre de la page
        'Connexions',  // Menu title
        'manage_options',                     // Capabilities (Capacités)
        HPIXL_SECURICHECK_SETTINGS_SLUG . '-connexions',              // Slug du sous menu
        'hpixl_securicheck_render_connexions_page'       // affichage de la page
    );

    // On crée un élément de sous menu: Réglages
    add_submenu_page(
        HPIXL_SECURICHECK_SETTINGS_SLUG, // slug du Menu Parent
        __('Réglages Securicheck', 'securicheck'),
        __('Réglages', 'securicheck'), // Menu title       
        'manage_options',                     // Capabilities (Capacités)
        HPIXL_SECURICHECK_SETTINGS_SLUG . '-reglages',              // Slug du sous menu
        'hpixl_securicheck_render_reglages_page'       // affichage de la page
    );
}
add_action('admin_menu', 'hpixl_securicheck_Menu_Admin_Link');


//le contenu de la page réglages
function hpixl_securicheck_render_reglages_page()
{
    //include dirname(__FILE__) . '/reglages.php';
    $screen = new hpixl_securicheck_ScreenReglages();
    $screen->render_screen();
}

//le contenu de la page réglages
function hpixl_securicheck_render_connexions_page()
{
    //include dirname(__FILE__) . '/reglages.php';
    $screen = new hpixl_securicheck_ScreenConnexions();
    $screen->render_screen();
}

//le contenu de la page Audit
function hpixl_securicheck_render_audit_page()
{
    // include dirname(__FILE__) . '/Screen_audit.php';
    $screen = new hpixl_securicheck_ScreenAudit();
    $screen->render_screen();
}
