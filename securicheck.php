<?php

/*
Plugin Name: Securicheck
Plugin URI: https://wp-securicheck.com
Description: Plugin permettant de vérifier la sécurité de votre site internet.
Author: Mickael Hauwy
Version: 1.1.0
Author URI: https://8pixl.fr
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: securicheck
*/

if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}

require_once(ABSPATH . 'wp-admin/includes/plugin.php');
// les fonctions utiles
require_once(__DIR__ . '/utils/utils.php');
// la page d'administration
require_once(__DIR__ . '/admin/admin.php');
// les screens de l'administration audit
require_once(__DIR__ . '/admin/Screen_audit.php');
require_once(__DIR__ . '/admin/Screen_reglages.php');
require_once(__DIR__ . '/admin/Screen_connexions.php');
// les classes de l'audit
require_once(__DIR__ . '/audit/Audit.php');
require_once(__DIR__ . '/audit/ResultatAudit.php');
// le watch des connexions
require_once(__DIR__ . '/utils/connexions-logs.php');
// les notifications
require_once(__DIR__ . '/utils/notifications.php');
// cacher le BO
require_once(__DIR__ . '/utils/cachette.php');


//le text domain
define('HPIXL_SECURICHECK_TEXT_DOMAIN', 'securicheck');
// déclare les constantes
define('HPIXL_SECURICHECK_PLUGIN_PATH', dirname(__FILE__));
define('HPIXL_SECURICHECK_PLUGIN_URL', WP_PLUGIN_URL . '/securicheck/');
define('HPIXL_SECURICHECK_PLUGIN_FILE_URL', __FILE__);
// la version minimum de PHP à comparer
define('HPIXL_SECURICHECK_PHP_MINI', '8.1.0');

//la taille de la BDD Maxi
define('HPIXL_SECURICHECK_BDD_MAXI', '500 MB');
// déclare le slug du plugin
define('HPIXL_SECURICHECK_SETTINGS_SLUG', 'securicheck');

//les textes pour l'image de test hotlinking
define('HPIXL_SECURICHECK_HOTLINKING_IMAGE_NON_PRESENTE', 'Image définie dans les réglages du plugin non trouvée sur votre site');
define('HPIXL_SECURICHECK_HOTLINKING_PARAMETRE_NON_DEFINI', 'Merci d\'aller dans les réglages du plugin pour choisir l\'url d\'une image afin de pouvoir réaliser le test');

//les tables en bdd
define('HPIXL_SECURICHECK_TABLE_AUDIT', 'securicheck_audit');
define('HPIXL_SECURICHECK_TABLE_AUDIT_RESULTATS', 'securicheck_audit_resultats');
define('HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS', 'securicheck_audit_logs_connexions');

//les constantes du mode d'audit
define('HPIXL_SECURICHECK_AUDIT_MODE_MANUEL', 'manuel');
define('HPIXL_SECURICHECK_AUDIT_MODE_AUTOMATIQUE', 'automatique');

//le nom du plugin pro
define('HPIXL_SECURICHECK_PREMIUM_PLUGIN_NAME', 'securicheck-pro/securicheck-pro.php');



class Hpixl_Securicheck
{

    protected $pluginPath;
    protected $pluginUrl;
    protected $pluginSlug;

    public function __construct()
    {
        // les infos utiles du plugin
        $this->pluginPath = HPIXL_SECURICHECK_PLUGIN_PATH;
        $this->pluginUrl = HPIXL_SECURICHECK_PLUGIN_URL;
        $this->pluginSlug = HPIXL_SECURICHECK_SETTINGS_SLUG;

        register_activation_hook(__FILE__, array($this, 'hpixl_securicheck_activation_plugin'));
        register_deactivation_hook(__FILE__, array($this, 'hpixl_securicheck_desactivation_plugin'));

        add_action('plugins_loaded', array($this, 'hpixl_securicheck_init_after_plugin_loaded'), 100);
        add_action('wp_enqueue_scripts', array($this, 'reports_scripts_hpixl_securicheck'));
        add_action('admin_enqueue_scripts', array($this, 'reports_admin_scripts_hpixl_securicheck'), 10);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'hpixl_securicheck_plugin_action_links'));
        add_action('in_plugin_update_message-8PIXL-hpixl-securicheck/hpixl-securicheck.php', array($this, 'hpixl_securicheck_prefix_plugin_update_message'), 10, 2);

        add_action('wp_dashboard_setup', array($this, 'hpixl_securicheck_register_custom_dashboard_widget'), 1);

        //on ajoute un loader dans le header de la page d'admin
        add_action('admin_head', array($this, 'hpixl_securicheck_adminheader'));

        // déclare la version du plugin
        define('HPIXL_SECURICHECK_PLUGIN_VERSION', get_plugin_data(__FILE__)['Version']);

        //la traduction
        add_action('init', array($this, 'hpixl_securicheck_charger_textdomain'));
    }

    //charge les fichiers de traduction
    function hpixl_securicheck_charger_textdomain()
    {
        load_plugin_textdomain('securicheck', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }


    //loader si on est en train de créer un audit
    function hpixl_securicheck_adminheader()
    {
        //si on n'est pas sur la page admin.php on sort
        global $pagenow;
        if (!('admin.php' == $pagenow)) return;

        //si la page n'est pas la page d'audit on sort
        if (!isset($_GET['page']) || $_GET['page'] != 'hpixl-securicheck') return;

        //si on n'est pas en train de créer un audit on sort
        if (!isset($_POST['btn-submit-audit']) || $_POST['btn-submit-audit'] === "") return;
        if (!((isset($_POST['_wpnonce-lancer-audit-bienvenue']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce-lancer-audit-bienvenue'])), 'lancer_audit')) ||
            (isset($_POST['_wpnonce-lancer-audit']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce-lancer-audit'])), 'lancer_audit'))
        )) {
            return;
        }

        $urlLogo = HPIXL_SECURICHECK_PLUGIN_URL . "images/securicheck.png";
        $urlLoader = HPIXL_SECURICHECK_PLUGIN_URL . "images/Preloader_4.gif";
?>
        <div class='hpixl-securicheck-pre-con' style='--imgLoader : url("<?php echo esc_url($urlLoader) ?>")'>
            <h2>Securicheck</h2>
            <img src="<?php echo esc_url($urlLogo) ?>" />
            <p>Audit en cours</p>
        </div>";
    <?php
    }

    /**
     * Lancé à l'activation du plugin
     */
    public function hpixl_securicheck_activation_plugin()
    {
        // Insertion des tables en bdd
        $this->init_db_myplugin();
    }

    /**
     * Lancé à la désactivation du plugin
     */
    public function hpixl_securicheck_desactivation_plugin()
    {
        //si on désactive le plugin on verifie si securicheck-pro est actif ou non et si oui on le désactive
        if (is_plugin_active(HPIXL_SECURICHECK_PREMIUM_PLUGIN_NAME)) {
            //désactive securicheck-pro
            add_action('update_option_active_plugins', array($this, 'hpixl_securicheck_disable_securicheck_pro'));
        }

        //suppression des options
        // delete_option('hpixl_securicheck_toggle_notifications');
        delete_option('hpixl_securicheck_toggle_page_connexion_url');
        delete_option('hpixl_securicheck_textarea_page_connexion_url');
        delete_option('hpixl_securicheck_toggle_page_connexion_redirection');
        delete_option('hpixl_securicheck_textarea_page_connexion_redirection');
    }

    //on désactive le plugin securicheck pro si il est actif
    public function hpixl_securicheck_disable_securicheck_pro()
    {
        // Vérifie si le plugin est actif
        if (is_plugin_active(HPIXL_SECURICHECK_PREMIUM_PLUGIN_NAME)) {
            // Désactive le plugin
            deactivate_plugins(HPIXL_SECURICHECK_PREMIUM_PLUGIN_NAME);
        }
    }


    // quand le plugin est initialisé
    protected function init_db_myplugin()
    {
        global $table_prefix, $wpdb;
        $tableAudit = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;
        $tableAuditResultats = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_RESULTATS;
        $tableAuditLogsConnexions = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_LOGS_CONNEXIONS;

        /**
         * La table wp_securicheck_audit
         */
        if (!hpixl_securicheck_table_exists($tableAudit)) {
            // Query - Create Table
            $sqlAudit = "CREATE TABLE `$tableAudit` (";
            $sqlAudit .= " `id` int(11) NOT NULL auto_increment, ";
            $sqlAudit .= " `date` DATETIME NOT NULL, ";
            $sqlAudit .= " `type` VARCHAR(32) NOT NULL, ";
            $sqlAudit .= " `score` int(11) NOT NULL, ";
            $sqlAudit .= " `pb_total` int(11) NOT NULL, ";
            $sqlAudit .= " `pb_techniques` int(11) NOT NULL, ";
            $sqlAudit .= " `pb_fonctionnels` int(11) NOT NULL, ";
            $sqlAudit .= " `pb_securite` int(11) NOT NULL, ";
            $sqlAudit .= " `pb_performance` int(11) NOT NULL, ";
            $sqlAudit .= " PRIMARY KEY `audit_id` (`id`) ";
            $sqlAudit .= ") AUTO_INCREMENT=1;";

            // Inclue le script Upgrade
            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

            // Créer la Table
            dbDelta($sqlAudit);
        }

        /**
         * La table wp_securicheck_audit_resultats
         */
        if (!hpixl_securicheck_table_exists($tableAuditResultats)) {

            // Query - Create Table
            $sqlAuditResultats = "CREATE TABLE `$tableAuditResultats` (";
            $sqlAuditResultats .= " `id` int(11) NOT NULL auto_increment, ";
            $sqlAuditResultats .= " `id_audit` int(11) NOT NULL, ";
            $sqlAuditResultats .= " `action` VARCHAR(128) NOT NULL, ";
            $sqlAuditResultats .= " `resultat` VARCHAR(6144) NOT NULL, ";
            $sqlAuditResultats .= " PRIMARY KEY `resultat_id` (`id`) ";
            $sqlAuditResultats .= ") AUTO_INCREMENT=1;";

            // Inclue le script Upgrade
            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

            // Créer la Table
            dbDelta($sqlAuditResultats);
        }

        /**
         * La table wp_securicheck_audit_logs_connexions
         */
        if (!hpixl_securicheck_table_exists($tableAuditLogsConnexions)) {

            // Query - Create Table
            $sqlAuditLogsConnexions = "CREATE TABLE `$tableAuditLogsConnexions` (";
            $sqlAuditLogsConnexions .= " `id` int(11) NOT NULL auto_increment, ";
            $sqlAuditLogsConnexions .= " `date` DATETIME NOT NULL, ";
            $sqlAuditLogsConnexions .= " `username` VARCHAR(128) NOT NULL, ";
            $sqlAuditLogsConnexions .= " `browser` VARCHAR(512) NOT NULL, ";
            $sqlAuditLogsConnexions .= " `ip` VARCHAR(16) NOT NULL, ";
            $sqlAuditLogsConnexions .= " `etat` VARCHAR(16) NOT NULL, ";
            $sqlAuditLogsConnexions .= " PRIMARY KEY `resultat_id` (`id`) ";
            $sqlAuditLogsConnexions .= ") AUTO_INCREMENT=1;";

            // Inclue le script Upgrade
            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

            // Créer la Table
            dbDelta($sqlAuditLogsConnexions);
        }
    }

    /** 
     * charge ceci une fois le plugin chargé
     */

    public function hpixl_securicheck_init_after_plugin_loaded() {}


    /** 
     * le style et le js du frontend 
     */
    public function reports_scripts_hpixl_securicheck()
    {
        // chargement du css
    }

    /* le style et le js du backend */
    public function reports_admin_scripts_hpixl_securicheck($hook_suffix)
    {
        //scripts
        wp_enqueue_script('hpixl_securicheck_admin_script',  $this->pluginUrl . 'js/hpixl_securicheck_admin.js', array('jquery'), true, true);
        //chartjs
        wp_enqueue_script('hpixl_securicheck_admin_script_chartjs', $this->pluginUrl . "js/chart.umd.js", array('jquery'), true, true);
        //style
        //  if ('appearance_page_theme-options' === $hook_suffix) {
        wp_enqueue_style('hpixl_securicheck_admin_style', $this->pluginUrl . 'css/hpixl_securicheck_admin_style.css', array(), HPIXL_SECURICHECK_PLUGIN_VERSION);
        wp_enqueue_style('hpixl_securicheck_admin_audit_style', $this->pluginUrl . 'css/hpixl_securicheck_admin_audit_style.css', array(), HPIXL_SECURICHECK_PLUGIN_VERSION);
        wp_enqueue_style('hpixl_securicheck_admin_reglages_style', $this->pluginUrl . 'css/hpixl_securicheck_admin_reglages_style.css', array(), HPIXL_SECURICHECK_PLUGIN_VERSION);
        // }
    }

    /**
     * Ajout d'un widget dans le dashboard du wordpress
     */
    function hpixl_securicheck_render_dashboard_widget_connexions($callback_args, $widget)
    {
        $nb = hpixl_securicheck_get_nb_connexions_failed();
        echo "<strong>Nombre de connexions échouées sur les 30 derniers jours : " . esc_html($nb) . "</strong>";
        $maData = hpixl_securicheck_count_connexions_failed_by_day();
    ?>
        <div id="chart-container" style="position: relative; height:320px;margin-bottom:20px;">
            <canvas id="graphCanvas"></canvas>
        </div>

        <a class="hpixl_securicheck_widget_bouton" href="<?php echo esc_url(site_url()); ?>/wp-admin/admin.php?page=securicheck-connexions&tab=echouees"> Détails des connexions </a>
        <?php
        wp_enqueue_script('hpixl_securicheck_show_chart_script',  $this->pluginUrl . 'js/hpixl_securicheck_show_chart.js', array('jquery'), true, true);
        $jsonMaData = wp_json_encode($maData);
        wp_localize_script('hpixl_securicheck_show_chart_script', 'data', $maData);
        ?>




<?php
    }

    function hpixl_securicheck_register_custom_dashboard_widget()
    {
        global $wp_meta_boxes;

        // Sauvegarder les widgets existants
        $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
        $side_dashboard = $wp_meta_boxes['dashboard']['side']['core'];

        // Supprimer tous les widgets
        $wp_meta_boxes['dashboard']['normal']['core'] = array();
        $wp_meta_boxes['dashboard']['side']['core'] = array();

        wp_add_dashboard_widget(
            'hpixl-securicheck-dashboard-widget-connexions',
            'Securicheck',
            array($this, 'hpixl_securicheck_render_dashboard_widget_connexions'), // Fonction de callback
            null, // Pas d'édition
            null, // Pas de contrôle de contenu
            'normal', // Section "normal"
            'high' // Priorité haute
        );
        // Remettre les widgets sauvegardés en dessous du widget personnalisé
        // Vérifier si la clé 'hpixl-securicheck-dashboard-widget-connexions' existe dans $wp_meta_boxes avant d'essayer de la récupérer
        if (isset($wp_meta_boxes['dashboard']['normal']['core']['hpixl-securicheck-dashboard-widget-connexions'])) {
            $custom_widget = array(
                'hpixl-securicheck-dashboard-widget-connexions' => $wp_meta_boxes['dashboard']['normal']['core']['hpixl-securicheck-dashboard-widget-connexions']
            );
        } else {
            $custom_widget = array();
        }

        // Fusionner le widget personnalisé avec les autres widgets sauvegardés
        $wp_meta_boxes['dashboard']['normal']['core'] = array_merge(
            $custom_widget,
            $normal_dashboard
        );

        $wp_meta_boxes['dashboard']['side']['core'] = $side_dashboard;
    }

    /** 
     * Ajout d'un lien "settings" dans la liste des plugins dans le backoffice
     */
    public function hpixl_securicheck_plugin_action_links($links)
    {
        $links[] = '<a href="' . menu_page_url($this->pluginSlug, false) . '">Paramètres</a>';
        return $links;
    }
}

$securicheck = new Hpixl_Securicheck();
