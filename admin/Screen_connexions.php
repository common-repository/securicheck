<?php

if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}
if (!class_exists('hpixl_securicheck_ScreenConnexions')) {

    class hpixl_securicheck_ScreenConnexions
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
                        <img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/securicheck.png" alt="logo hpixl-securicheck" />
                        <h1><?php _e('Securicheck - CONNEXIONS', 'securicheck'); ?></h1>
                    </div>
                    <span>V<?php echo esc_html(HPIXL_SECURICHECK_PLUGIN_VERSION); ?></span>
                </div>
                <p><?php _e(' Ici vous trouverez le détail des connexions à votre site sur les 30 derniers jours ', 'securicheck'); ?></p>
                <?php
                $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'tous';
                ?>

                <h2 class="nav-tab-wrapper">
                    <a href="?page=<?php echo esc_html(HPIXL_SECURICHECK_SETTINGS_SLUG . '-connexions'); ?>&tab=tous" class="nav-tab <?php echo esc_html($active_tab == 'tous' ? 'nav-tab-active' : ''); ?>"><?php _e('Tous', 'securicheck'); ?></a>
                    <a href="?page=<?php echo esc_html(HPIXL_SECURICHECK_SETTINGS_SLUG . '-connexions'); ?>&tab=administrateurs" class="nav-tab <?php echo esc_html($active_tab == 'administrateurs' ? 'nav-tab-active' : ''); ?>"><?php _e('Administrateurs', 'securicheck'); ?></a>
                    <a href="?page=<?php echo esc_html(HPIXL_SECURICHECK_SETTINGS_SLUG . '-connexions'); ?>&tab=echouees" class="nav-tab <?php echo esc_html($active_tab == 'echouees' ? 'nav-tab-active' : ''); ?>"><?php _e('Échouées', 'securicheck'); ?></a>
                </h2>
                <div class='hpixl-securicheck-connexions-form-panel'>
                    <?php
                    if ($active_tab == 'tous') {
                        settings_fields('hpixl_securicheck_connexions_tous_groupe');
                        do_settings_sections('hpixl_securicheck_connexions_tous_groupe');
                        hpixl_securicheck_render_connexions_tous();
                    } else if ($active_tab == 'administrateurs') {
                        settings_fields('hpixl_securicheck_connexions_administrateurs_groupe');
                        do_settings_sections('hpixl_securicheck_connexions_administrateurs_groupe');
                        hpixl_securicheck_render_connexions_administrateurs();
                    } else if ($active_tab == 'echouees') {
                        settings_fields('hpixl_securicheck_connexions_echouees_groupe');
                        do_settings_sections('hpixl_securicheck_connexions_echouees_groupe');
                        hpixl_securicheck_render_connexions_echouees();
                    }
                    ?>
                </div>
            </div>
<?php
        }
    }

    function hpixl_securicheck_render_connexions_tous()
    {
        $listeConnexionsTous = hpixl_securicheck_get_connexions_ok_last_30days();
        require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_connexions_onglet_tous.php';
    }

    function hpixl_securicheck_render_connexions_administrateurs()
    {
        $listeConnexionsAdministrateurs = hpixl_securicheck_get_connexions_ok_admin_last_30days();
        require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_connexions_onglet_administrateurs.php';
    }

    function hpixl_securicheck_render_connexions_echouees()
    {
        $listeConnexionsEchouees = hpixl_securicheck_get_connexions_failed_last_30days();
        require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_connexions_onglet_echouees.php';
    }
}
