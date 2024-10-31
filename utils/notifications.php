<?php

if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}

/**
 * on envoie un e-mail au destinataire en paramètre
 */
function hpixl_securicheck_envoi_mail_fin_audit($destinataire, $score, $tableauNbErreurs)
{
    $site_name = get_bloginfo('name');
    $admin_email = get_option('admin_email');
    $user_email = $destinataire;
    //si email non valide on défini le destinataire comme l'administrateur
    if (!hpixl_securicheck_is_valid_email($user_email)) {
        $user_email = $admin_email;
    }

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        __('From: WP Securicheck : nouvel AUDIT <', 'securicheck') . $admin_email . '>'
    );

    $subject = __('Lancement d\'un nouvel AUDIT pour le site ', 'securicheck') . $site_name . ' !';
    $body = hpixl_securicheck_create_email_body($score, $tableauNbErreurs);

    wp_mail($user_email, $subject, $body, $headers);
}

function hpixl_securicheck_create_email_body($score, $tableauNbErreurs)
{
    /* $body = sprintf(
        __('Bonjour, un nouvel audit a été exécuté à l\'instant.
        Votre score est de %1$s.', HPIXL_SECURICHECK_TEXT_DOMAIN),
        $score
    );*/
    ob_start();
    // Include footer contents
    include(plugin_dir_path(dirname(__FILE__)) . 'utils/mail.php');

    $body = ob_get_contents();

    ob_end_clean();
    return $body;
}
