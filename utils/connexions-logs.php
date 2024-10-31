<?php

if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}

//en cas de connexion failed
function hpixl_securicheck_watch_failed_connexions($username)
{
    //récupération de toutes les infos utiles

    //adresse IPO
    $sanitized_ip = '';
    if (isset($_SERVER['REMOTE_ADDR'])) {
        // Récupère l'adresse IP
        $validated_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP); // Sanitize and validate input

        if ($validated_ip !== false) {
            // l'ip est valide alors on sanitize
            $sanitized_ip = sanitize_text_field($validated_ip);
        }
    }

    //le browser
    $sanitized_browser = '';
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $sanitized_browser = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
        // Sanitize la chaine de caractère User Agent
    } else {
        // HTTP_USER_AGENT is not set
        $sanitized_browser = '';
    }

    $sanitized_browser = esc_html($sanitized_browser);

    // Obtenez l'horodatage actuel selon le fuseau horaire de WordPress
    $current_time = current_time('timestamp');
    // Formatez l'horodatage en utilisant le fuseau horaire de WordPress
    $date = date_i18n('Y-m-d H:i:s', $current_time);

    global $wpdb;
    $tableLogConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;

    if (hpixl_securicheck_table_exists($tableLogConnexions)) {
        $wpdb->query($wpdb->prepare(
            "INSERT INTO %i (date, username,ip,browser,etat) VALUES (%s, %s, %s,%s,%s)",
            esc_sql($tableLogConnexions),
            $date,
            $username,
            $sanitized_ip,
            $sanitized_browser,
            'failed'
        ));
    }
}

add_action('wp_login_failed', 'hpixl_securicheck_watch_failed_connexions');

//en cas de connexion réussie
function hpixl_securicheck_watch_good_connexions($username, $user)
{

    //récupération de toutes les infos utiles
    $sanitized_ip = '';
    if (isset($_SERVER['REMOTE_ADDR'])) {
        // Récupère et valide l'addresse IP
        $validated_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

        if ($validated_ip !== false) {
            // l'ip est valide alors on sanitize
            $sanitized_ip = sanitize_text_field($validated_ip);
        }
    }

    //le browser
    $sanitized_browser = '';
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $browser = $_SERVER['HTTP_USER_AGENT'];
        // Sanitize la chaine de caractère User Agent
        $sanitized_browser = sanitize_text_field($browser);
    } else {
        // HTTP_USER_AGENT is not set
        $sanitized_browser = '';
    }

    // Obtenez l'horodatage actuel selon le fuseau horaire de WordPress
    $current_time = current_time('timestamp');
    // Formatez l'horodatage en utilisant le fuseau horaire de WordPress
    $date = date_i18n('Y-m-d H:i:s', $current_time);

    global $wpdb;
    $tableLogConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;
    if (hpixl_securicheck_table_exists($tableLogConnexions)) {

        $wpdb->query($wpdb->prepare(
            "INSERT INTO %i (date, username,ip,browser,etat) VALUES (%s, %s, %s,%s,%s)",
            esc_sql($tableLogConnexions),
            $date,
            $username,
            $sanitized_ip,
            $sanitized_browser,
            'ok'
        ));
    }
}

add_action('wp_login', 'hpixl_securicheck_watch_good_connexions', 10, 2);
