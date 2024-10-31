<?php

if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}
if (!class_exists('hpixl_securicheck_screen_reglages')) {

    class hpixl_securicheck_ScreenReglages
    {

        public function __construct($id = null) {}

        function render_screen()
        {
?>
            <?php settings_errors(); ?>
            <div class='wrap'>
                <h1 style="display:none;"></h1>
                <div class="hpixl-securicheck-admin-audit-header">
                    <div class="hpixl-securicheck-admin-audit-header-panneau-logo">
                        <img src="<?php echo esc_html(HPIXL_SECURICHECK_PLUGIN_URL . "images/securicheck.png"); ?>" alt="logo hpixl-securicheck" />
                        <h1><?php esc_html_e('Securicheck - RÉGLAGES', 'securicheck'); ?></h1>
                    </div>
                    <span>V<?php echo esc_html(HPIXL_SECURICHECK_PLUGIN_VERSION); ?></span>
                </div>


                <?php
                $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'fonctionnalites';
                ?>

                <h2 class="nav-tab-wrapper">
                    <a href="?page=<?php echo esc_html(HPIXL_SECURICHECK_SETTINGS_SLUG . '-reglages'); ?>&tab=fonctionnalites" class="nav-tab <?php echo esc_html($active_tab == 'fonctionnalites' ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Fonctionnalités', 'securicheck'); ?></a>
                    <a href="?page=<?php echo esc_html(HPIXL_SECURICHECK_SETTINGS_SLUG) . '-reglages'; ?>&tab=url-connexion" class="nav-tab <?php echo $active_tab == 'url-connexion' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('URL de connexion', 'securicheck'); ?></a>
                </h2>
                <div class='hpixl-securicheck-settings-form-panel'>
                    <form action='options.php' method='POST'>
                        <?php
                        submit_button();
                        if ($active_tab == 'fonctionnalites') {
                            settings_fields('hpixl_securicheck_settings_fonctionnalites_groupe');
                            do_settings_sections('hpixl_securicheck_settings_fonctionnalites_groupe');
                            hpixl_securicheck_render_reglages_fonctionnalites();
                        } else if ($active_tab == 'url-connexion') {
                            settings_fields('hpixl_securicheck_settings_securite_groupe_connexion');
                            do_settings_sections('hpixl_securicheck_settings_securite_groupe_connexion');
                            hpixl_securicheck_render_page_securite_onglet_connexion();
                        }
                        ?>
                    </form>
                </div>
            </div>
<?php
        }
    }

    //Ajout d'un settings pour notre plugin 
    add_action('admin_init', 'hpixl_securicheck_settings');

    /**
     * Enregistre le setting
     */
    function hpixl_securicheck_settings()
    {
        register_setting('hpixl_securicheck_settings_fonctionnalites_groupe', 'hpixl_securicheck_hotlinking_image_url');
        register_setting('hpixl_securicheck_settings_fonctionnalites_groupe', 'hpixl_securicheck_toggle_notifications');
        register_setting('hpixl_securicheck_settings_fonctionnalites_groupe', 'hpixl_securicheck_destinataire_email_notifications');
        register_setting('hpixl_securicheck_settings_fonctionnalites_groupe', 'hpixl_securicheck_toggle_limite_nombre_audit');
        register_setting('hpixl_securicheck_settings_fonctionnalites_groupe', 'hpixl_securicheck_text_limite_nombre_audit');
        register_setting('hpixl_securicheck_settings_fonctionnalites_groupe', 'hpixl_securicheck_toggle_delete_audits_after_uninstall');
        register_setting('hpixl_securicheck_settings_fonctionnalites_groupe', 'hpixl_securicheck_toggle_delete_reglages_after_uninstall');

        //valeurs par défaut
        add_option('hpixl_securicheck_hotlinking_image_url', '');
        add_option('hpixl_securicheck_toggle_notifications', '0');
        add_option('hpixl_securicheck_destinataire_email_notifications', get_option('admin_email'));
        add_option('hpixl_securicheck_toggle_limite_nombre_audit', '0');
        add_option('hpixl_securicheck_text_limite_nombre_audit', '');
        add_option('hpixl_securicheck_toggle_delete_audits_after_uninstall', '0');
        add_option('hpixl_securicheck_toggle_delete_reglages_after_uninstall', '0');

        register_setting('hpixl_securicheck_settings_securite_groupe_connexion', 'hpixl_securicheck_toggle_page_connexion_url');
        register_setting('hpixl_securicheck_settings_securite_groupe_connexion', 'hpixl_securicheck_textarea_page_connexion_url');
        register_setting('hpixl_securicheck_settings_securite_groupe_connexion', 'hpixl_securicheck_toggle_page_connexion_redirection');
        register_setting('hpixl_securicheck_settings_securite_groupe_connexion', 'hpixl_securicheck_textarea_page_connexion_redirection');
        //valeurs par défaut
        add_option('hpixl_securicheck_toggle_page_connexion_url', '0');
        add_option('hpixl_securicheck_textarea_page_connexion_url', 'cachette');
        add_option('hpixl_securicheck_toggle_page_connexion_redirection', '0');
        add_option('hpixl_securicheck_textarea_page_connexion_redirection', '404');
    }

    function hpixl_securicheck_render_reglages_fonctionnalites()
    {
        require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_reglages_onglet_fonctionnalites.php';
    }

    function hpixl_securicheck_render_page_securite_onglet_connexion()
    {
        require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_reglages_onglet_url_connexion.php';
    }
}
