<?php

if (!defined('ABSPATH')) {
    exit;
}

global $hpixl_securicheck_is_login;

register_activation_hook(__FILE__, 'hpixl_securicheck_on_activation');
function hpixl_securicheck_on_activation()
{

    set_transient('hpixl_securicheck_remind_me', 950400);
}

add_filter('login_url', 'hpixl_securicheck_login_url', 10, 3);
function hpixl_securicheck_login_url($login_url, $redirect, $force_reauth)
{
    if (get_option('hpixl_securicheck_toggle_page_connexion_url') && get_option('hpixl_securicheck_textarea_page_connexion_url')) {
        /* Si l'url est install.php, on cache le login */
        $request_uri = sanitize_text_field($_SERVER['REQUEST_URI']); // Sanitize

        if (mb_strpos($request_uri, 'wp-admin/install.php') !== false) { // Valide
            return admin_url();
        }

        if (is_404()) {
            nocache_headers();
            return '#';
        }

        if ($force_reauth === false) {
            return $login_url;
        }

        if (empty($redirect)) {
            return $login_url;
        }

        $redirect = explode('?', $redirect);

        if ($redirect[0] === admin_url('options.php')) {
            $login_url = admin_url();
        }
    }


    return $login_url;
}

/**
 * Désactive wp-login et active la nouvelle URL
 */
add_action('plugins_loaded', 'hpixl_securicheck_plugin_on_page_loaded');
function hpixl_securicheck_plugin_on_page_loaded()
{
    global $pagenow, $hpixl_securicheck_is_login_network, $hpixl_securicheck_is_login;

    $request_uri = sanitize_text_field(rawurldecode($_SERVER['REQUEST_URI'])); // Sanitize the raw URL
    $request = wp_parse_url($request_uri); // Parse the sanitized URL

    if (get_option('hpixl_securicheck_toggle_page_connexion_url') && get_option('hpixl_securicheck_textarea_page_connexion_url')) {
        //rajout de la ligne suivante pour vérifier que $request est bien un tableau
        if (is_array($request)) {
            if ((strpos($request_uri, 'wp-login.php') || $request['path'] == site_url('wp-login', 'relative')) && !is_admin()) {
                $hpixl_securicheck_is_login = true;
                $pagenow = 'index.php';
            } elseif ((strpos($request_uri, 'wp-register.php') || $request['path'] == site_url('wp-register', 'relative')) && !is_admin()) {
                $hpixl_securicheck_is_login = true;
                $pagenow = 'index.php';
            } elseif ($request['path'] == site_url(get_option('hpixl_securicheck_textarea_page_connexion_url'), 'relative')) {
                $hpixl_securicheck_is_login = false;
                $pagenow = 'wp-login.php';
            }
        }
    }
}

/**
 * Soigner les redirections
 */
add_action('wp_loaded', 'hpixl_securicheck_redirect_page', 1);
function hpixl_securicheck_redirect_page()
{
    global $pagenow, $hpixl_securicheck_is_login, $hpixl_securicheck_is_login_network;

    if (get_option('hpixl_securicheck_toggle_page_connexion_url') && get_option('hpixl_securicheck_textarea_page_connexion_url')) {

        if (!(isset($_GET['action']) && isset($_POST['post_password']) && sanitize_text_field(wp_unslash($_GET['action'])) == 'postpass')) {
            if ($hpixl_securicheck_is_login) {
                nocache_headers();
                if (get_option('hpixl_securicheck_toggle_page_connexion_redirection') && get_option('hpixl_securicheck_textarea_page_connexion_redirection')) {
                    /**/
                    wp_safe_redirect(get_site_url() . "/" . get_option('hpixl_securicheck_textarea_page_connexion_redirection'));
                } else {
                    wp_safe_redirect(get_site_url() . "/404");
                }
                exit;
            } elseif ($pagenow == 'wp-login.php') {
                global $user_login, $error;
                $redirect_admin = admin_url();
                $redirect_url = isset($_REQUEST['redirect_to']) ? esc_url_raw($_REQUEST['redirect_to']) : ""; // Sanitize

                if (is_user_logged_in() && !isset($_REQUEST['action'])) {
                    nocache_headers();
                    wp_safe_redirect(apply_filters('hpixl_securicheck_redirect_if_connected_login', $redirect_admin, $redirect_url));
                    exit();
                }

                require_once(ABSPATH . 'wp-login.php');
                exit;
            }

            if (is_admin() && !is_user_logged_in() && !defined('WP_CLI') && !wp_doing_ajax() && !defined('DOING_CRON') && $pagenow !== 'admin-post.php') {
                nocache_headers();
                if (get_option('hpixl_securicheck_toggle_page_connexion_redirection') && get_option('hpixl_securicheck_textarea_page_connexion_redirection')) {
                    wp_safe_redirect(get_site_url() . "/" . get_option('hpixl_securicheck_textarea_page_connexion_redirection'));
                } else {
                    wp_safe_redirect(get_site_url() . "/404");
                }
                exit;
            }
        }
    }
}

add_filter('network_site_url', 'hpixl_securicheck_siteurl');
add_filter('site_url', 'hpixl_securicheck_siteurl');
add_filter('wp_redirect', 'hpixl_securicheck_redirect');

function hpixl_securicheck_siteurl($url)
{
    return hpixl_securicheck_filter_login($url);
}

function hpixl_securicheck_redirect($location)
{
    return hpixl_securicheck_filter_login($location);
}

/**
 * Si l'url contient wp-login.php,
 * recréer une url avec l'url custom définie
 */
function hpixl_securicheck_filter_login($url)
{
    if (strpos($url, 'wp-login.php?action=postpass') !== false) {
        return $url;
    }

    if (strpos($url, 'wp-login.php') && strpos(wp_get_referer(), 'wp-login.php') === false) {
        $args = explode('?', $url);
        if (get_option('hpixl_securicheck_toggle_page_connexion_url') && get_option('hpixl_securicheck_textarea_page_connexion_url')) {
            if (isset($args[1])) {
                parse_str($args[1], $args);
                $url = add_query_arg($args, get_site_url() . "/" . get_option('hpixl_securicheck_textarea_page_connexion_url'));
            } else {

                $url = get_site_url() . "/" . get_option('hpixl_securicheck_textarea_page_connexion_url');
            }
        }
    }

    return $url;
}
