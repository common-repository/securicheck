<?php

if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}

//change le format de date
function hpixl_securicheck_format_date($date)
{
    // Convertit la date au format timestamp Unix
    $timestamp = strtotime($date);

    // Formatte la date au format d-m-Y H:i:s
    $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    datefmt_set_pattern($formatter, 'dd MMMM yyyy kk:mm:ss');
    $formatted_date = $formatter->format($timestamp);

    return $formatted_date;
}

// Fonction pour vérifier si le répertoire de téléchargement des plugins est protégé
function hpixl_securicheck_checkPluginDir()
{
    $plugins_dir = site_url() . '/wp-content/plugins/';

    $dir_exists = wp_remote_get($plugins_dir);
    return (/*is_wp_error($dir_exists) && */!(wp_remote_retrieve_response_code($dir_exists) === 200));
}

// Fonction pour vérifier si le répertoire uploads est protégé
function hpixl_securicheck_isUploadsDirProtected()
{
    // Chemin vers le répertoire des uploads
    $uploads_dir = wp_upload_dir()['basedir'];
    $uploads_url = wp_upload_dir()['baseurl'];

    // Nom du fichier de test
    $test_file_name = 'test_php_execution.php';
    $test_file_path = $uploads_dir . '/' . $test_file_name;
    $test_file_url = $uploads_url . '/' . $test_file_name;

    // Contenu PHP à insérer dans le fichier de test
    $test_file_content = "<?php echo 'PHP execution allowed'; ?>";

    // Crée le fichier PHP de test
    file_put_contents($test_file_path, $test_file_content);

    // Effectue une requête HTTP pour tenter d'exécuter le fichier PHP
    $response = wp_remote_get($test_file_url);

    // Supprime le fichier de test
    unlink($test_file_path);

    // Vérifie si l'exécution PHP a été autorisée ou non
    if (is_wp_error($response)) {
        return false; // Erreur lors de la requête
    }

    $body = wp_remote_retrieve_body($response);
    // Si la réponse contient "PHP execution allowed", alors PHP est exécuté
    if (strpos($body, 'PHP execution allowed') !== false) {
        return false; // PHP s'exécute dans le répertoire uploads
    }

    return true; // PHP est bloqué dans le répertoire uploads
}

// Fonction pour vérifier si XML-RPC est désactivé
function hpixl_securicheck_checkXmlRpcDisabled()
{
    $xmlrpc_url = site_url() . '/xmlrpc.php';
    $response = wp_remote_head($xmlrpc_url, array('timeout' => 10));

    $http_code = wp_remote_retrieve_response_code($response);

    if ($http_code === 308 || $http_code === 301 || $http_code === 404 || $http_code === 403) {
        return true;
    } else {
        return false;
    }
}

// Fonction pour vérifier que la navigation dans les dossiers est impossible
function hpixl_securicheck_checkDirectoryListing()
{

    $wp_includes_dir = site_url() . '/wp-includes/';

    $dir_exists = wp_remote_get($wp_includes_dir);
    return (/*is_wp_error($dir_exists) && */!(wp_remote_retrieve_response_code($dir_exists) === 200));
}

// Fonction pour vérifier si wp-config.php est protégé
function hpixl_securicheck_checkWpConfigProtection()
{
    $wp_config_path = site_url() . '/wp-config.php';

    $wp_config_exists = wp_remote_get($wp_config_path);
    return (/*is_wp_error($dir_exists) && */!(wp_remote_retrieve_response_code($wp_config_exists) === 200));
}

// Fonction pour vérifier si .htaccess est protégé
function hpixl_securicheck_checkHtaccessProtection()
{

    $htaccess_path = site_url() . '/.htaccess';

    $htaccess_exists = wp_remote_get($htaccess_path);
    return (/*is_wp_error($dir_exists) && */!(wp_remote_retrieve_response_code($htaccess_exists) === 200));
}

// Fonction pour vérifier si les informations relatives au serveur sont cachées
function hpixl_securicheck_checkServerInfoHidden()
{
    $server_info = get_headers(site_url(), 1);

    if (isset($server_info['X-Powered-By'])) {
        return false;
    } else {
        return true;
    }
}

// Fonction pour vérifier si l'api rest est bien désactivée
function hpixl_securicheck_checkRestApiDisabled()
{
    $rest_url = rtrim(site_url(), '/') . '/wp-json/';

    // Tente d'accéder à une URL de l'API REST
    $response = wp_remote_get($rest_url);
    $apidesactiveeTotale = (/*is_wp_error($response) && */!(wp_remote_retrieve_response_code($response) === 200));


    // Vérifie si l'API REST est partiellement désactivée en vérifiant si le endpoint "user" est accessible
    $response_user = wp_remote_get($rest_url . 'wp/v2/posts');
    $apidesactiveePartielle = (/*is_wp_error($response_user) && */!(wp_remote_retrieve_response_code($response_user) === 200));


    $retour = $apidesactiveeTotale ? 1 : ($apidesactiveePartielle ? 2 : 0);

    return $retour;
    //return !$response;
}

//vérifie si l'editeur de fichier est bien désactivé
function hpixl_securicheck_isEditeurFichierDisabled()
{
    // Vérifie si la constante DISALLOW_FILE_EDIT est définie à true
    if (defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT === true) {
        return true;
    } else {
        return false;
    }
}

//fonction qui vérifie si chatgpt a accés au site
function hpixl_securicheck_is_accessible_by_chatgpt()
{

    // URL de votre site
    $site_url = site_url();

    // Exécution de la requête avec wp_remote_get
    $response = wp_remote_get($site_url, array(
        'timeout' => 15, // Temps d'attente en secondes
        'headers' => array(
            'User-Agent' => 'ChatGPT-User', // Définir le User-Agent comme ChatGPT
            // 'User-Agent' => 'OpenAI ChatGPT', // Définir le User-Agent comme ChatGPT
        ),
    ));

    // Vérification de la réponse
    if (wp_remote_retrieve_response_code($response) === 200) {
        return true; // ChatGPT n'est pas bloqué
    } else {
        return false; // ChatGPT est bloqué
    }
}


// Fonction pour vérifier si le hotlinking est désactivé
function hpixl_securicheck_checkHotlinkingDisabled()
{

    // URL de l'image
    $image_url = "";
    $urlHotlinking = get_option('hpixl_securicheck_hotlinking_image_url') ?? '';
    if ($urlHotlinking != "") {
        $image_url = $urlHotlinking;
    } else {
        return HPIXL_SECURICHECK_HOTLINKING_PARAMETRE_NON_DEFINI;
    }

    //check existence de l'image
    $relative_path = wp_parse_url($image_url, PHP_URL_PATH);
    // Convertir le chemin relatif en chemin de fichier local
    $image_path = ABSPATH . ltrim($relative_path, '/');

    // si l'image n'existe pas
    if (!file_exists($image_path)) {
        return HPIXL_SECURICHECK_HOTLINKING_IMAGE_NON_PRESENTE;
    }

    // URL du site tiers qui tente de hotlinker
    $referer_url = "toto";

    // Exécution de la requête avec wp_remote_get
    $response = wp_remote_get($image_url, array(
        'timeout' => 15, // Temps d'attente en secondes
        'headers' => array(
            'Referer' => $referer_url,
        ),
    ));



    // Vérification de la réponse
    if (wp_remote_retrieve_response_code($response) === 200) {
        return "non"; // Hotlinking n'est pas bloqué
    } else {
        return "oui"; // Hotlinking est bloqué
    }
}
//fonction qui renvoie la version de php
function hpixl_securicheck_get_php_version()
{
    $php_version = phpversion();
    if ($php_version) {
        return $php_version;
    } else {
        return 'Version de PHP non trouvée';
    }
}

//fonction qui renvoie la version de la bdd
function hpixl_securicheck_get_bdd_version()
{
    global $wpdb;
    return $wpdb->db_version();
}

//fonction qui renvoie si la bdd est MariaDB ou Mysql
function hpixl_securicheck_get_bdd_type()
{
    global $wpdb;
    $info = $wpdb->db_server_info();
    $infos = explode("-", $info);

    if ($infos && $infos[1])
        return $infos[1];
    else
        return "N/A";
}

//fonction qui renvoie l'espace disque utilé par le site
function hpixl_securicheck_get_space_usage()
{
    $wordpress_root = ABSPATH; // Chemin vers le répertoire racine de WordPress
    $total_space = 0;

    // Fonction récursive pour parcourir les fichiers et répertoires
    $calculate_space = function ($path) use (&$calculate_space, &$total_space) {
        foreach (scandir($path) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $full_path = $path . '/' . $file;
            if (is_dir($full_path)) {
                $calculate_space($full_path);
            } else {
                $total_space += filesize($full_path);
            }
        }
    };

    // Calcul de l'espace utilisé
    $calculate_space($wordpress_root);

    // Conversion de l'espace utilisé en une chaîne lisible par l'homme (par exemple, en mégaoctets ou en gigaoctets)
    $used_space_human_readable = hpixl_securicheck_format_bytes($total_space);

    return $used_space_human_readable;
}

// Fonction pour formater les octets en une chaîne lisible par l'homme (Mo, Go, etc.)
function hpixl_securicheck_format_bytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

//fonction qui renvoie l'espace disque utilé par la bdd du site
function hpixl_securicheck_get_database_disk_usage()
{
    global $wpdb;

    // Récupération de toutes les tables WordPress
    // Définir une clé de cache unique pour cette requête
    $cache_key = 'securicheck_all_wp_tables_status';
    // Tenter de récupérer les résultats du cache
    $tables = wp_cache_get($cache_key);
    if ($tables === false) {
        // Si les résultats ne sont pas en cache, effectuer la requête
        $tables = $wpdb->get_results("SHOW TABLE STATUS");
        // Mettre en cache les résultats pour une utilisation future
        wp_cache_set($cache_key, $tables, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
    }

    $total_size = 0;

    // Calcul de la taille totale de toutes les tables
    foreach ($tables as $table) {
        $total_size += $table->Data_length + $table->Index_length;
    }

    // Conversion de la taille en une chaîne lisible par l'homme (Mo, Go, etc.)
    $disk_usage_human_readable = hpixl_securicheck_format_bytes($total_size);

    return $disk_usage_human_readable;
}

//fonction qui compte le nombre de plugins à mettre à jour
function hpixl_securicheck_get_nb_maj_plugins()
{
    $plugins_with_updates = get_plugin_updates();
    $update_count = count($plugins_with_updates);
    return $update_count;
    return 0;
}

//fonction qui compte le nombre de plugins à mettre à jour
function hpixl_securicheck_get_nb_maj_themes()
{
    $plugins_with_updates = get_theme_updates();
    $update_count = count($plugins_with_updates);
    return $update_count;
    return 0;
}
//fonction qui récupère si le wordpress est à mettre à jour
function hpixl_securicheck_get_nb_maj_wordpress()
{
    // Récupération de la version actuelle de WordPress
    $current_version = get_bloginfo('version');

    // Récupération de la dernière version stable de WordPress
    $latest_version = get_core_updates();
    $latest_version = !empty($latest_version) ? $latest_version[0]->current : '';

    // Vérification si une mise à jour est disponible
    if (version_compare($current_version, $latest_version, '<')) {
        return 1;
    } else {
        return 0;
    }
}

//fonction qui récupère les informations du thème actif
function hpixl_securicheck_get_theme_info()
{
    $theme_info = wp_get_theme();
    $nom_theme = $theme_info->name;
    $titre_theme = $theme_info->title;
    $version_theme = $theme_info->version;
    $theme_enfant = hpixl_securicheck_is_child_theme_installed();
    $theme_parent = $theme_info->get_template();
    return $nom_theme . "-" . $titre_theme . "-" . $version_theme . " - theme enfant : " . $theme_enfant . " - theme parent : " . $theme_parent;
}

//fonction qui récupére le nom du theme actuel
function hpixl_securicheck_get_theme_name()
{
    $theme_info = wp_get_theme();
    return $theme_info->name;
}

//fonction qui récupére le nom du theme parent
function hpixl_securicheck_get_theme_parent()
{
    $theme_info = wp_get_theme();
    return $theme_info->get_template();
}

//fonction qui vérifie si un thèe enfant est installé
function hpixl_securicheck_is_child_theme_installed()
{
    // Récupération du nom du thème actuellement activé
    $active_theme = get_stylesheet();

    // Vérification si le thème actif est un thème enfant
    $parent_theme = wp_get_theme($active_theme)->get('Template');
    if ($parent_theme) {
        return "oui"; // Le thème actif est un thème enfant
    } else {
        return "non"; // Le thème actif n'est pas un thème enfant
    }
}

//fonction permettant de récupérer le nombre de plugins inactifs
function hpixl_securicheck_get_disabled_plugins_count()
{
    // Récupération de la liste des plugins
    $plugins = get_plugins();

    $disabled_count = 0;

    // Parcours des plugins pour vérifier s'ils sont désactivés
    foreach ($plugins as $plugin_file => $plugin_info) {
        if (!is_plugin_active($plugin_file)) {
            $disabled_count++;
        }
    }

    return $disabled_count;
}

//fonction permettant de récupérer le nombre de themes inactifs
function hpixl_securicheck_get_disabled_themes_count()
{
    //le theme actif
    $theme_info = wp_get_theme();
    $nom_theme = $theme_info->name;
    // Récupération de la liste de tous les thèmes installés
    $themes = wp_get_themes();

    $disabled_count = 0;

    foreach ($themes as $theme) {
        if (!$theme->is_active && $nom_theme != $theme->get('Name')) {
            $disabled_count++;
        }
    }

    return $disabled_count;
}

//le site est il en https ?
function hpixl_securicheck_isHTTPSUrl()
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        return true;
    } elseif (isset($_SERVER['HTTP_FRONT_END_HTTPS']) && $_SERVER['HTTP_FRONT_END_HTTPS'] === 'on') {
        return true;
    }

    return false;
}

// récupère le prefixe des tables en bdd
function hpixl_securicheck_getBddPrefix()
{
    global $wpdb;
    return $wpdb->prefix;
}

// teste si un compte "admin" existe
function hpixl_securicheck_compteAdminExiste()
{
    // Obtient tous les utilisateurs de WordPress
    $utilisateurs = get_users();

    // Parcours tous les utilisateurs
    foreach ($utilisateurs as $utilisateur) {
        // Vérifie si le nom d'affichage de l'utilisateur est "admin"
        if ($utilisateur->display_name === 'admin') {
            return true;
        }
    }

    return false;
}

//retourne le nombre d'administrateurs du site
function hpixl_securicheck_nombreAdministrateurs()
{
    $utilisateurs = get_users();
    $compteurAdministrateurs = 0;

    foreach ($utilisateurs as $utilisateur) {
        if (in_array('administrator', $utilisateur->roles)) {
            $compteurAdministrateurs++;
        }
    }

    return $compteurAdministrateurs;
}

//vérifie si on est en mode debug ou non
function hpixl_securicheck_debugActif()
{
    // Vérifie si le mode debug est activé
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
        return true;
    } else {
        return false;
    }
}

//protection du live writer ?
function hpixl_securicheck_isLiveWriterProtected()
{
    // Vérifie si l'action de suppression du lien wlwmanifest a été ajoutée dans functions.php
    return !has_action('wp_head', 'wlwmanifest_link'); // Cela signifie que la protection est en place
}

//l'url du backoffice
function hpixl_securicheck_getBackEndUrl()
{
    $admin_url = get_admin_url();

    // Exécution de la requête avec wp_remote_get
    $response = wp_remote_get($admin_url, array(
        'timeout' => 15, // Temps d'attente en secondes
        'headers' => array(
            'User-Agent' => 'Mozilla/5.0', // Fournir un agent utilisateur bidon
        ),
        'method' => 'HEAD', // Utiliser la méthode HEAD pour éviter de télécharger le contenu
        'redirection' => 5, // Nombre maximum de redirections à suivre
    ));

    // Vérification de la réponse
    if (!is_wp_error($response)) {
        $http_code = wp_remote_retrieve_response_code($response);
        if ($http_code === 404) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//fonction qui check l'en-tete de sécurité HSTS (Strict-Transport-Security)
function hpixl_securicheck_isStrictTransportSecurityOk()
{
    $url = site_url();
    $mesHeaders = get_headers($url, 1);
    if ($mesHeaders != false) {
        // Récupère les en-têtes HTTP de l'URL
        $headers = array_change_key_case($mesHeaders);

        // Vérifie si l'en-tête Strict-Transport-Security (HSTS) est présent dans les en-têtes HTTP
        if (isset($headers['strict-transport-security'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//fonction qui check l'en-tete de sécurité HSTS (Strict-Transport-Security)
function hpixl_securicheck_isReferrerPolicyOk()
{
    $url = site_url();
    $mesHeaders = get_headers($url, 1);
    if ($mesHeaders != false) {
        // Récupère les en-têtes HTTP de l'URL
        $headers = array_change_key_case($mesHeaders);

        // Vérifie si l'en-tête Referrer-Policy est présent dans les en-têtes HTTP
        if (isset($headers['referrer-policy'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//fonction qui check l'en-tete de Content-Security-Policy (CSP)
function hpixl_securicheck_isContentSecurityPolicyOk()
{
    $url = site_url();
    $mesHeaders = get_headers($url, 1);
    if ($mesHeaders != false) {
        // Récupère les en-têtes HTTP de l'URL
        $headers = array_change_key_case($mesHeaders);

        // Vérifie si l'en-tête Content-Security-Policy est présent dans les en-têtes HTTP
        if (isset($headers['content-security-policy'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//fonction qui check l'en-tete de X-Frame-Options
function hpixl_securicheck_isXFrameOptionsOk()
{
    $url = site_url();
    $mesHeaders = get_headers($url, 1);
    if ($mesHeaders != false) {
        // Récupère les en-têtes HTTP de l'URL
        $headers = array_change_key_case($mesHeaders);

        // Vérifie si l'en-tête X-Frame-Options est présent dans les en-têtes HTTP
        if (isset($headers['x-frame-options'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//fonction qui check l'en-tete de X-XSS-Protection
function hpixl_securicheck_isXXssProtectionOk()
{
    $url = site_url();
    $mesHeaders = get_headers($url, 1);
    if ($mesHeaders != false) {
        // Récupère les en-têtes HTTP de l'URL
        $headers = array_change_key_case($mesHeaders);

        // Vérifie si l'en-tête X-XSS-Protection est présent dans les en-têtes HTTP
        if (isset($headers['x-xss-protection'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//fonction qui check l'en-tete de X-XSS-Protection
function hpixl_securicheck_isXContentTypeOptionsOk()
{
    $url = site_url();
    $mesHeaders = get_headers($url, 1);
    if ($mesHeaders != false) {
        // Récupère les en-têtes HTTP de l'URL
        $headers = array_change_key_case($mesHeaders);

        // Vérifie si l'en-tête X-Content-Type-Options est présent dans les en-têtes HTTP
        if (isset($headers['x-content-type-options'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//fonction qui check l'en-tete de Permissions-Policy
function hpixl_securicheck_isPermissionsPolicyOk()
{
    $url = site_url();
    $mesHeaders = get_headers($url, 1);
    if ($mesHeaders != false) {
        // Récupère les en-têtes HTTP de l'URL
        $headers = array_change_key_case($mesHeaders);

        // Vérifie si l'en-tête Permissions-Policy est présent dans les en-têtes HTTP
        if (isset($headers['permissions-policy'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//fonction qui check l'en-tete de Access-Control-Allow-Origin
function hpixl_securicheck_isAccessControlAllowOriginOk()
{
    $url = site_url();
    $mesHeaders = get_headers($url, 1);
    if ($mesHeaders != false) {
        // Récupère les en-têtes HTTP de l'URL
        $headers = array_change_key_case($mesHeaders);

        // Vérifie si l'en-tête Access-Control-Allow-Origin est présent dans les en-têtes HTTP
        if (isset($headers['access-control-allow-origin'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//fonction qui récupère le nombre de révision maxi défini
function hpixl_securicheck_getNombreMaxRevisions()
{
    // Récupère le nombre maximal de révisions configurées
    $max_revisions = -1;

    // Si la constante WP_POST_REVISIONS est définie, utilisez sa valeur
    if (defined('WP_POST_REVISIONS')) {
        $max_revisions = WP_POST_REVISIONS === true ? -1 : intval(WP_POST_REVISIONS);
    }

    return $max_revisions;
}

//fonction qui vérifie si un système de cache est acif sur le site
function hpixl_securicheck_isCacheActif()
{
    // Vérifie si une constante de cache est définie dans wp-config.php
    if (defined('WP_CACHE') && WP_CACHE) {
        return true;
    }

    // Vérifie si des fichiers de cache sont présents dans le répertoire wp-content/cache
    $cache_dir = WP_CONTENT_DIR . '/cache';
    if (is_dir($cache_dir) && (count(glob("$cache_dir/*")) > 0)) {
        return true;
    }

    return false;
}

//fonction qui vérifie que la version de wordpress est bien cachée
function hpixl_securicheck_is_wordpress_version_hidden()
{
    ob_start();
    wp_head();
    $wp_head_content = ob_get_clean();

    return strpos($wp_head_content, '<meta name="generator" content="WordPress"') === false;
}

//fonction qui retourne les plugins installés
function hpixl_securicheck_get_plugins()
{
    $plugins = get_plugins();
    $plugins_retour = array();

    foreach ($plugins as $slug => $plugin) {
        $version = isset($plugin['Version']) ? $plugin['Version'] : '';
        $name = isset($plugin['Name']) ? $plugin['Name'] : '';
        $plugins_retour[$slug]['Version'] = $version;
        $plugins_retour[$slug]['Name'] = $name;
    }

    return $plugins_retour;
}

//fonction qui retourne les plugins à update
function hpixl_securicheck_get_plugin_updates()
{
    $plugins = get_plugin_updates();
    $plugins_retour = array();

    foreach ($plugins as $slug => $plugin) {
        $new_version = isset($plugin->update->new_version) ? $plugin->update->new_version : '';
        $plugins_retour[$slug]['new_version'] = $new_version;
    }
    return $plugins_retour;
}

//fonction qui retourne les plugins installés
function hpixl_securicheck_get_themes()
{
    $themes = wp_get_themes();
    $themes_retour = array();

    foreach ($themes as $slug => $theme) {
        $version = isset($theme->version) ? $theme->version : '';
        $name = isset($theme->name) ? $theme->name : '';
        $themes_retour[$slug]['Version'] = $version;
        $themes_retour[$slug]['Name'] = $name;
    }

    return $themes_retour;
}

//fonction qui retourne les thèmes à update
function hpixl_securicheck_get_theme_updates()
{
    $themes = get_theme_updates();
    $themes_retour = array();

    foreach ($themes as $slug => $theme) {
        $new_version = isset($theme->update['new_version']) ? $theme->update['new_version'] : '';
        $themes_retour[$slug]['new_version'] = $new_version;
    }
    return $themes_retour;
}

//fonction qui retourne les connexions failed 30 jours avant la date de l'audit avec l'id passé en paramètre
function hpixl_securicheck_get_connexions_failed_by_audit($id)
{
    global $wpdb;
    //on va chercher la date de l'audit
    $tableAudit = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;
    $retour = null;
    if (hpixl_securicheck_table_exists($tableAudit)) {

        $cache_key = 'audit_date_' . $id;
        // Tenter de récupérer le résultat du cache
        $resultatId = wp_cache_get($cache_key);
        if ($resultatId === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultatId = $wpdb->get_results($wpdb->prepare(
                "SELECT date FROM %i WHERE id = %s",
                esc_sql($tableAudit),
                $id
            ));

            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultatId, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }

        if ($resultatId) {
            //on va chercher les connexions avant cette date
            $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;

            $resultat = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM %i WHERE date <= %s AND date >= %s - INTERVAL 30 DAY and etat = 'failed' ORDER BY date DESC",
                esc_sql($tableAuditLogsConnexions),
                $resultatId[0]->date,
                $resultatId[0]->date
            ));

            $retour = array();
            $i = 0;
            foreach ($resultat as $row) {

                $user = get_user_by('login', $row->username);
                if ($user) {
                    $row->user_exists = true;
                } else {
                    $row->user_exists = false;
                }
                $i++;
                $retour[$i] = $row;
            }
        }
    }
    return $retour;
}

//fonction qui retourne les connexions failed sur les 30 derniers jours
function hpixl_securicheck_get_connexions_failed_last_30days()
{
    global $wpdb;

    $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;
    $retour = array();
    if (hpixl_securicheck_table_exists($tableAuditLogsConnexions)) {

        $cache_key = 'securicheck_audit_logs_failed_last_30_days';

        // Tenter de récupérer le résultat du cache
        $resultat = wp_cache_get($cache_key);
        if ($resultat === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultat = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM %i WHERE etat = %s AND date >= CURDATE() - INTERVAL 30 DAY ORDER BY date DESC",
                esc_sql($tableAuditLogsConnexions),
                'failed'
            ));
            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultat, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }

        $i = 0;
        foreach ($resultat as $row) {

            $user = get_user_by('login', $row->username);
            if ($user) {
                $row->user_exists = true;
            } else {
                $row->user_exists = false;
            }
            $i++;
            $retour[$i] = $row;
        }
    }
    return $retour;
}

//fonction qui retourne les connexions ok 30 jours avant la date de l'audit avec l'id passé en paramètre
function hpixl_securicheck_get_connexions_ok_by_audit($id)
{
    global $wpdb;
    //on va chercher la date de l'audit
    $tableAudit = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;
    $resultat = null;
    if (hpixl_securicheck_table_exists($tableAudit)) {

        $cache_key = 'securicheck_audit_date_for_id_' . $id;

        // Tenter de récupérer le résultat du cache
        $resultatId = wp_cache_get($cache_key);

        if ($resultatId === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultatId = $wpdb->get_results($wpdb->prepare(
                "SELECT date FROM %i WHERE id = %s",
                esc_sql($tableAudit),
                $id
            ));

            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultatId, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }

        if ($resultatId) {
            //on va chercher les connexions avant cette date
            $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;

            $cache_key = 'securicheck_logs_ok_for_id_.' . $id . '_and_date_' . $resultatId[0]->date;

            // Tenter de récupérer le résultat du cache
            $resultat = wp_cache_get($cache_key);

            if ($resultat === false) {
                // Si le résultat n'est pas en cache, effectuer la requête
                $resultat = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM %i WHERE date <= %s AND date >= %s - INTERVAL 30 DAY AND etat = 'ok' ORDER BY date DESC",
                    esc_sql($tableAuditLogsConnexions),
                    $resultatId[0]->date,
                    $resultatId[0]->date
                ));

                // Mettre en cache le résultat pour une utilisation future
                wp_cache_set($cache_key, $resultat, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
            }
        }
    }
    return $resultat;
}

//fonction qui retourne les connexions ok sur les 30 derniers jours
function hpixl_securicheck_get_connexions_ok_last_30days()
{
    global $wpdb;

    $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;
    $resultat = array();
    if (hpixl_securicheck_table_exists($tableAuditLogsConnexions)) {

        $cache_key = 'securicheck_logs_ok_last_30_days';
        // Tenter de récupérer le résultat du cache
        $resultat = wp_cache_get($cache_key);
        if ($resultat === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultat = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM %i WHERE etat = %s AND date >= CURDATE() - INTERVAL 30 DAY ORDER BY date DESC",
                esc_sql($tableAuditLogsConnexions),
                'ok'
            ));
            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultat, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }
    }
    return $resultat;
}

//fonction qui retourne les connexions d'administrateurs ok 30 jours avant la date de l'audit avec l'id passé en paramètre
function hpixl_securicheck_get_connexions_ok_admin_by_audit($id)
{
    global $wpdb;
    //on va chercher la date de l'audit
    $tableAudit = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;
    $admins = array();
    if (hpixl_securicheck_table_exists($tableAudit)) {

        $cache_key = 'securicheck_audit_date_for_id_' . $id;

        // Tenter de récupérer le résultat du cache
        $resultatId = wp_cache_get($cache_key);

        if ($resultatId === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultatId = $wpdb->get_results($wpdb->prepare(
                "SELECT date FROM %i WHERE id = %s",
                esc_sql($tableAudit),
                $id
            ));

            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultatId, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }

        if ($resultatId) {

            //on va chercher les connexions avant cette date
            $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;

            $cache_key = 'securicheck_admin_logs_ok_for_' . $id . '_and_date_' . $resultatId[0]->date;
            // Tenter de récupérer le résultat du cache
            $resultat = wp_cache_get($cache_key);
            if ($resultat === false) {
                // Si le résultat n'est pas en cache, effectuer la requête
                $resultat = $wpdb->get_results($wpdb->prepare(
                    "SELECT * FROM %i WHERE date <= %s  AND date >= %s - INTERVAL 30 DAY AND etat = 'ok' ORDER BY date DESC",
                    esc_sql($tableAuditLogsConnexions),
                    $resultatId[0]->date,
                    $resultatId[0]->date
                ));

                // Mettre en cache le résultat pour une utilisation future
                wp_cache_set($cache_key, $resultat, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
            }

            //on ne garde que les administrateurs
            foreach ($resultat as $row) {
                $user = get_user_by('login', $row->username);
                if ($user && in_array('administrator', $user->roles)) {
                    $admins[] = $row;
                }
            }
        }
    }
    return $admins;
}

//fonction qui retourne les connexions d'administrateurs ok sur les 30 derniers jours
function hpixl_securicheck_get_connexions_ok_admin_last_30days()
{
    global $wpdb;

    $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;
    $admins = array();
    if (hpixl_securicheck_table_exists($tableAuditLogsConnexions)) {

        $cache_key = 'securicheck_audit_logs_ok_last_30_days';
        // Tenter de récupérer le résultat du cache
        $resultat = wp_cache_get($cache_key);
        if ($resultat === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultat = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM %i WHERE etat = %s AND date >= CURDATE() - INTERVAL 30 DAY ORDER BY date DESC",
                esc_sql($tableAuditLogsConnexions),
                'ok'
            ));
            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultat, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }

        //on ne garde que les administrateurs
        foreach ($resultat as $row) {
            $user = get_user_by('login', $row->username);
            if ($user && in_array('administrator', $user->roles)) {
                $admins[] = $row;
            }
        }
    }

    return $admins;
}

//fonction qui retourne les connexions d'administrateurs ok sur les 30 derniers jours
function hpixl_securicheck_get_connexions_ok_administrateurs()
{
    global $wpdb;

    $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;
    $admins = array();
    if (hpixl_securicheck_table_exists($tableAuditLogsConnexions)) {

        $cache_key = 'securicheck_audit_logs_ok';
        // Tenter de récupérer le résultat du cache
        $resultat = wp_cache_get($cache_key);
        if ($resultat === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultat = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM %i WHERE etat = %s ORDER BY date DESC",
                esc_sql($tableAuditLogsConnexions),
                'ok'
            ));
            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultat, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }

        //on ne garde que les administrateurs
        foreach ($resultat as $row) {
            $user = get_user_by('login', $row->username);
            if ($user && in_array('administrator', $user->roles)) {
                $admins[] = $row;
            }
        }
    }

    return $admins;
}


// fonction qui retourne le nombre de connexions failed par jour sur les 30 derniers jours 
function hpixl_securicheck_count_connexions_failed_by_day()
{
    //on va chercher les infos pour ces 30 jours là
    global $wpdb;

    $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;
    $retour = array();
    if (hpixl_securicheck_table_exists($tableAuditLogsConnexions)) {

        $cache_key = 'audit_logs_failed_by_day_last_30_days';

        // Tenter de récupérer le résultat du cache
        $resultat = wp_cache_get($cache_key);

        if ($resultat === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultat = $wpdb->get_results($wpdb->prepare(
                "SELECT all_dates.jour, IFNULL(COUNT(%i.date), 0) AS nombre_connexions
        FROM (
            SELECT CURDATE() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS jour
            FROM (
                SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
            ) AS a
            CROSS JOIN (
                SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
            ) AS b
            CROSS JOIN (
                SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
            ) AS c
        ) AS all_dates
        LEFT JOIN %i ON DATE(%i.date) = all_dates.jour
            AND %i.date >= CURDATE() - INTERVAL 30 DAY
            AND %i.etat = %s
        WHERE all_dates.jour >= CURDATE() - INTERVAL 30 DAY
        GROUP BY all_dates.jour
        ORDER BY all_dates.jour ASC;",
                esc_sql($tableAuditLogsConnexions),
                esc_sql($tableAuditLogsConnexions),
                esc_sql($tableAuditLogsConnexions),
                esc_sql($tableAuditLogsConnexions),
                esc_sql($tableAuditLogsConnexions),
                'failed'
            ));

            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultat, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }



        $i = 0;
        foreach ($resultat as $row) {
            $i++;
            // Convertit la date au format timestamp Unix
            $timestamp = strtotime($row->jour);

            $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            datefmt_set_pattern($formatter, 'dd/MM/yyyy');
            $row->jour = $formatter->format($timestamp);
            $retour[$i] = $row;
        }
    }
    return $retour;
}

//fonction qui retourne le nombre de connexions failed sur les 30 derniers jours
function hpixl_securicheck_get_nb_connexions_failed()
{
    global $wpdb;

    //on va chercher les connexions avant cette date
    $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;
    if (hpixl_securicheck_table_exists($tableAuditLogsConnexions)) {

        $cache_key = 'securicheck_failed_connections_count';
        // Tenter de récupérer le résultat du cache
        $resultat = wp_cache_get($cache_key);
        if ($resultat === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultat = $wpdb->get_results($wpdb->prepare(
                "SELECT COUNT(id) AS nombre FROM %i WHERE etat = %s AND date >= CURDATE() - INTERVAL 30 DAY;",
                esc_sql($tableAuditLogsConnexions),
                'failed'
            ));
            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultat, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }

        return $resultat[0]->nombre;
    } else {
        return 0;
    }
}

//fonction qui récupère l'id de l'administrateur principal
function hpixl_securicheck_get_main_admin_id()
{
    // Récupère tous les utilisateurs ayant le rôle d'administrateur
    $admins = get_users(array(
        'role'    => 'administrator',
        'orderby' => 'user_registered', // Vous pouvez changer cet ordre si nécessaire
        'order'   => 'ASC',
    ));

    // Vérifie s'il y a des administrateurs
    if ($admins) {
        // Récupère le premier administrateur (celui qui a été enregistré en premier)
        $main_admin = reset($admins);

        // Retourne l'ID de l'administrateur principal
        return $main_admin->ID;
    } else {
        // Aucun administrateur trouvé
        return false;
    }
}

/**
 * Crée une date complète à partir de chaînes de caractères pour la date, les heures et les minutes.
 *
 * @param string $dateString  La date au format 'YYYY-MM-DD'.
 * @param string $hoursString Les heures au format 'HH'.
 * @param string $minutesString Les minutes au format 'MM'.
 * @return DateTime|false L'objet DateTime si la date est valide, false sinon.
 */
function hpixl_securicheck_create_full_date($dateString, $hoursString, $minutesString)
{
    // Combine les chaînes pour former une date complète
    $fullDateString = $dateString . ' ' . $hoursString . ':' . $minutesString . ':00';
    // Crée un objet DateTime à partir de la chaîne combinée
    try {
        $timezone = new DateTimeZone('Europe/Paris');
        $date = new DateTime($fullDateString, $timezone);
    } catch (Exception $e) {
        // Si la création échoue, retourne false

        return false;
    }

    // Vérifie si la date est valide
    if ($date && $date->format('Y-m-d H:i:s') === $fullDateString) {
        return $date->getTimestamp();
    } else {
        return false;
    }
}

//valide une adress email
function hpixl_securicheck_is_valid_email($email)
{
    // Utilisation de la fonction filter_var avec FILTER_VALIDATE_EMAIL pour vérifier si l'email est valide
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Fonction qui prend une date au format americain (2024-05-23 08:09:15) en paramètre 
 * et renvoie au format français : 23 mai 2024 à 08:09:15
 */
function hpixl_securicheck_format_date_in_french($dateString)
{
    // Crée une instance de DateTime à partir de la chaîne de date donnée
    $date = new DateTime($dateString);

    // Formate la date selon le format souhaité
    $formattedDate = $date->format('d F Y \à H:i:s');

    // Convertir le nom du mois en français
    $formattedDate = str_replace(
        array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
        array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'),
        $formattedDate
    );

    return $formattedDate;
}

/**
 * Retourne la date du dernier audit
 */
function hpixl_securicheck_get_date_dernier_audit()
{
    global $wpdb;
    $dateDernierAudit = "";
    $table = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;
    if (hpixl_securicheck_table_exists($table)) {

        $cache_key = 'securicheck_latest_audit_date_' . $table;

        // Tenter de récupérer le résultat du cache
        $resultatRequeteAudit = wp_cache_get($cache_key);
        if ($resultatRequeteAudit === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultatRequeteAudit = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM %i ORDER BY date DESC LIMIT 1",
                esc_sql($table)
            ));
            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultatRequeteAudit, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }

        foreach ($resultatRequeteAudit as $r) {
            $dateDernierAudit = hpixl_securicheck_format_date($r->date);
        }
    }
    return $dateDernierAudit;
}

/**
 * Retourne si la table de la bdd existe
 */
function hpixl_securicheck_table_exists($table_name)
{
    global $wpdb;

    $cache_key = 'securicheck_table_exists_' . $table_name;

    // Tenter de récupérer le résultat du cache
    $cached_result = wp_cache_get($cache_key);
    if ($cached_result === false) {
        // Si le résultat n'est pas en cache, effectuer la requête
        $result = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name));
        // Mettre en cache le résultat pour une utilisation future
        wp_cache_set($cache_key, $result, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
    } else {
        // Utiliser le résultat mis en cache
        $result = $cached_result;
    }

    if ($result === $table_name) {
        return true;
    } else {
        return false;
    }
}

/**
 * Supprime toutes les données d'un audit
 */
function hpixl_securicheck_delete_audit($id)
{
    global $wpdb;

    //suppression de l'audit
    $table = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;
    if (hpixl_securicheck_table_exists($table)) {

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $table WHERE id = %d",
                $id
            )
        );
    }
    //suppression des de cet audit
    $table = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_RESULTATS;
    if (hpixl_securicheck_table_exists($table)) {

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $table WHERE id_audit = %d",
                $id
            )
        );
    }
}

/**
 * Supprime les audits si le nombre est plus grand que la limite
 */
function hpixl_securicheck_delete_too_much_audit($limite)
{
    global $wpdb;

    $tableAudit = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;

    // Exécuter la requête préparée
    $wpdb->query($wpdb->prepare(
        "DELETE FROM %i
    WHERE id NOT IN (
        SELECT id
        FROM (
            SELECT id
            FROM %i
            ORDER BY id DESC
            LIMIT %d
        ) AS temp)",
        esc_sql($tableAudit),
        esc_sql($tableAudit),
        $limite
    ));
}

/**
 * Check si l'application passwords est activée ou non
 */
function hpixl_securicheck_is_application_password_active()
{
    return wp_is_application_passwords_available();
}

/**
 * Check si la page archie auteur est désactivée
 */
function hpixl_securicheck_is_author_archive_disabled()
{
    // Récupère un post publié
    $args = array(
        'numberposts' => 1, // Limite à un seul post
        'post_status' => 'publish', // Seuls les posts publiés
    );

    $recent_post = wp_get_recent_posts($args);

    // Si aucun post n'est trouvé
    if (empty($recent_post)) {
        return false; // Pas de post trouvé, ne peut pas tester
    }

    // Récupère l'auteur de ce post
    $post_author_id = $recent_post[0]['post_author'];

    // Récupère l'URL de l'archive auteur pour cet auteur
    $author_url = get_author_posts_url($post_author_id);
    $response = wp_remote_get($author_url);

    // Vérifie la réponse HTTP
    if (is_wp_error($response)) {
        return false; // Une erreur s'est produite lors de la requête
    }

    $status_code = wp_remote_retrieve_response_code($response);

    // Si la page retourne un 404, cela signifie que l'archive auteur est désactivée
    if ($status_code == 404) {
        return true; // Archive auteur désactivée
    }

    return false; // Archive auteur activée
}

/**
 * Check si le scan d'id des auteurs est désactivé
 */
function hpixl_securicheck_is_scan_author_disabled()
{
    // URL à tester
    $test_url = home_url('/?author=0');

    // Effectue une requête HTTP pour tester l'URL
    $response = wp_remote_get($test_url);

    // Vérifie si la requête a échoué
    if (is_wp_error($response)) {
        return false; // Une erreur s'est produite lors de la requête
    }

    // Récupère le code de réponse HTTP
    $status_code = wp_remote_retrieve_response_code($response);

    // Vérifie si le code de réponse est 404 (non trouvé)
    if ($status_code == 404) {
        return true; // Le scan des ID d'auteurs est désactivé
    }

    return false; // Le scan des ID d'auteurs est actif
}
