<?php

if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}
if (!class_exists('hpixl_securicheck_screen_audit')) {

    class hpixl_securicheck_ScreenAudit
    {
        public $audit;

        public function __construct($id = null)
        {
            /**
             * Si on capte un argument POST de type submit-audit c'est que l'on a lancé un audit
             * Si on capte un argument POST de type charger-audit c'est que l'on veut charger un audit
             */
            if (isset($_POST['btn-submit-audit']) && $_POST['btn-submit-audit'] != "") {
                if ((isset($_POST['_wpnonce-lancer-audit-bienvenue']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce-lancer-audit-bienvenue'])), 'lancer_audit')) ||
                    (isset($_POST['_wpnonce-lancer-audit']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce-lancer-audit'])), 'lancer_audit'))
                ) {
                    $this->hpixl_securicheck_lancer_audit(HPIXL_SECURICHECK_AUDIT_MODE_MANUEL);
                } else {
                    wp_die(
                        'Échec de la vérification de sécurité pour la création d\'un audit.',
                        'Création de l\'audit annulé',
                        array('back_link' => true)
                    );
                }
            } else if (isset($_POST['charger-audit']) && $_POST['charger-audit'] != "") {
                if (isset($_POST['_wpnonce-charger-audit']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce-charger-audit'])), 'charger-audit')) {
                    $idAudit = sanitize_text_field($_POST['charger-audit']);
                    $this->hpixl_securicheck_charger_audit($idAudit);
                } else {
                    wp_die(
                        'Échec de la vérification de sécurité pour le chargement d\'un audit.',
                        'Chargement de l\'audit annulé',
                        array('back_link' => true)
                    );
                }
            } else if (isset($_POST['supprimer-audit']) && $_POST['supprimer-audit'] != "") {
                if (isset($_POST['_wpnonce-supprimer-audit']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce-supprimer-audit'])), 'supprimer-audit')) {
                    $idAudit = sanitize_text_field($_POST['supprimer-audit']);
                    $this->hpixl_securicheck_supprimer_audit($idAudit);
                } else {
                    wp_die(
                        'Échec de la vérification de sécurité pour la suppression d\'un audit.',
                        'Suppression de l\'audit annulé',
                        array('back_link' => true)
                    );
                }
            } else {
                $this->hpixl_securicheck_charger_audit();
            }
        }

        /*lancer un audit*/
        function hpixl_securicheck_lancer_audit($type)
        {
            $this->audit = new hpixl_securicheck_audit(null, $type);
        }

        /* charger un audit*/
        function hpixl_securicheck_charger_audit($id = null)
        {
            if ($id) {
                $this->audit = new hpixl_securicheck_audit($id, '');
            } else {
                $this->audit = new hpixl_securicheck_audit(-1, '');
            }
        }

        /*supprimer un audit*/
        function hpixl_securicheck_supprimer_audit($id)
        {
            hpixl_securicheck_delete_audit($id);
            //charge le dernier audit
            $this->hpixl_securicheck_charger_audit();
            //F5 de la page pour actualiser l'affichage si on a supprimé le dernier audit
            if (isset($_SERVER['REQUEST_URI'])) {
                // Get and sanitize the current URL
                $request_uri = esc_url_raw($_SERVER['REQUEST_URI']);

                // Redirect using the sanitized URL
                if (!headers_sent()) {
                    header("Location: " . $request_uri);
                    exit; // Ensure no further code is executed
                }
            }
        }

        function welcomeScreen()
        {
?>
            <div class='wrap'>
                <h1 style="display:none;"></h1>
                <div style="display:flex;flex-direction:column;height:calc(100vh - 50px);">
                    <div class="hpixl-securicheck-admin-audit-header">
                        <div class="hpixl-securicheck-admin-audit-header-panneau-logo">
                            <img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/securicheck.png" alt="logo hpixl-securicheck" />
                            <h1><?php _e('Securicheck - AUDIT', 'securicheck'); ?></h1>
                        </div>
                        <span>V<?php echo esc_html(HPIXL_SECURICHECK_PLUGIN_VERSION); ?></span>
                    </div>
                    <div class="hpixl-securicheck-admin-audit-panneau-welcome">
                        <img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/securicheck.png" alt="logo hpixl-securicheck" width="75px" />
                        <p><span style=" font-weight:bold;font-size:30px;">Pas encore d'audit </span></p>
                        <p style="font-weight:normal;font-size:20px"><?php _e('Pour commencer, lancez un 1er audit ici !', 'securicheck'); ?></p>
                        <form name="formulaire_lancer_audit" method="post">
                            <?php wp_nonce_field('lancer_audit', '_wpnonce-lancer-audit-bienvenue'); ?>
                            <button type="submit" name="btn-submit-audit" onclick="lancerAudit(event);" value="creer"><?php _e('Lancer un audit', 'securicheck'); ?><div class="loading"></div></button>
                        </form>
                    </div>
                </div>
            <?php
        }

        function render_screen()
        {
            //si il n'y a pas d'audit on affiche un écran d'accueil
            if (!($this->audit->id_audit)) {
                return $this->welcomeScreen();
            }
            ?>
                <div class='wrap'>
                    <h1 style="display:none;"></h1>
                    <div class="hpixl-securicheck-admin-audit-header">
                        <div class="hpixl-securicheck-admin-audit-header-panneau-logo">
                            <img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/securicheck.png" alt="logo hpixl-securicheck" />
                            <h1><?php _e('Securicheck - AUDIT', 'securicheck'); ?></h1>
                        </div>
                        <span>V<?php echo esc_html(HPIXL_SECURICHECK_PLUGIN_VERSION); ?></span>
                    </div>
                    <div class="hpixl-securicheck-admin-audit-panneau-score">
                        <div class="hpixl-securicheck-admin-audit-panneau-score-details">
                            <div class="hpixl-securicheck-admin-audit-sous-panneau-score-details">
                                <div class="hpixl-securicheck-admin-audit-sous-panneau-score-details-graphique">
                                    <h3>Score</h3>
                                    <div class="semi-donut margin" style="--percentage : <?php echo esc_attr($this->audit->score); ?>; --fill: <?php echo esc_attr($this->audit->score > 85 ?  "#08cc0a" : ($this->audit->score > 50 ? "#fcb214" : "#D63638")); ?>;">
                                        <?php echo esc_html($this->audit->score > 0 ? $this->audit->score . '%' : "N/A"); ?>
                                    </div>
                                </div>
                                <div>
                                    <?php
                                    $dateDernierAudit = hpixl_securicheck_get_date_dernier_audit();
                                    if ($dateDernierAudit > $this->audit->date) {
                                    ?>
                                        <p style="font-size: 15px;line-height: 1.2;"><?php _e('Audit chargé : ', 'securicheck'); ?></br><span style="font-weight:bold;font-size:30px;"><?php echo esc_html($this->audit->date); ?></span></p>
                                    <?php
                                    } else {
                                    ?>
                                        <p style="font-size: 15px;line-height: 1.2;"><?php _e('Dernier audit le ', 'securicheck'); ?></br><span style="font-weight:bold;font-size:30px;"><?php echo esc_html($this->audit->date); ?></span></p>
                                    <?php
                                    }
                                    //si score <100
                                    if ($this->audit->score < 100) {
                                    ?>
                                        <p style="font-size: 15px;line-height: 1.2;"><?php _e(' Continuez à travailler ', 'securicheck'); ?><span style="font-weight:500;color:#3A8373;"><?php echo esc_html(wp_get_current_user()->user_firstname); ?></span></p>
                                        <p style="font-size: 15px;line-height: 1.2;"><?php _e(' Vous y êtes presque ', 'securicheck'); ?></p>
                                    <?php
                                    } else {
                                    ?>
                                        <p style="font-size: 15px;line-height: 1.2;"><?php _e(' Félicitations ', 'securicheck'); ?><span style="font-weight:500;color:#3A8373;"><?php echo esc_html(wp_get_current_user()->user_firstname); ?></span></p>
                                        <p style="font-size: 15px;line-height: 1.2;"><?php _e(' Votre site est 100% sécurisé ', 'securicheck'); ?></p>
                                    <?php
                                    }
                                    $timestamp = wp_next_scheduled('hpixl_securicheck_pro_automatisation_audit_hook');
                                    if ($timestamp > 0) {
                                    ?>
                                        <p style="font-size: 15px;line-height: 1.2;"><?php _e(' Prochain audit prévu le ', 'securicheck'); ?><span style="font-weight:500;color:#3A8373;"><?php echo esc_html(date('d/m/Y \à H:i:s', $timestamp)); ?></span></p>
                                    <?php
                                    }
                                    ?>

                                    <form name="formulaire_lancer_audit" method="post">
                                        <?php wp_nonce_field('lancer_audit', '_wpnonce-lancer-audit'); ?>
                                        <button type="submit" name="btn-submit-audit" onclick="lancerAudit(event);" value="creer"><?php _e('Lancer un audit', 'securicheck'); ?><div class="loading"></div></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="hpixl-securicheck-admin-audit-panneau-score-problemes">

                            <div class="hpixl-securicheck-admin-audit-sous-panneau-score-problemes">
                                <div class="hpixl-securicheck-admin-audit-sous-panneau-score-problemes-graphique">
                                    <p class="hpixl-securicheck-audit-nb-problèmes"><?php echo esc_html($this->audit->resultats->nb_total_erreurs); ?></p>
                                    <p class="hpixl-securicheck-audit-txt-problèmes"><?php _e('problèmes', 'securicheck'); ?></p>
                                </div>
                                <div class="hpixl-securicheck-admin-audit-sous-panneau-score-problemes-resultats">
                                    <h3><?php _e('Problèmes', 'securicheck'); ?></h3>
                                    <ul class="hpixl-securicheck-liste-des-problemes">
                                        <li class=<?php echo esc_attr($this->audit->resultats->nb_erreurs_etat_technique > 0 ? "error" : "ok"); ?>> <?php echo esc_html($this->audit->resultats->nb_erreurs_etat_technique); ?><?php echo esc_html($this->audit->resultats->nb_erreurs_etat_technique > 1 ? __(' problèmes techniques', 'securicheck') : __(' problème technique', 'securicheck')); ?></li>
                                        <li class=<?php echo esc_attr($this->audit->resultats->nb_erreurs_etat_fonctionnel > 0 ? "error" : "ok"); ?>> <?php echo esc_html($this->audit->resultats->nb_erreurs_etat_fonctionnel); ?><?php echo esc_html($this->audit->resultats->nb_erreurs_etat_fonctionnel > 1 ? __(' problèmes fonctionnels', 'securicheck') : __(' problème fonctionnel', 'securicheck')); ?></li>
                                        <li class=<?php echo esc_attr($this->audit->resultats->nb_erreurs_securite > 0 ? "error" : "ok"); ?>> <?php echo esc_html($this->audit->resultats->nb_erreurs_securite); ?><?php echo esc_html($this->audit->resultats->nb_erreurs_securite > 1 ? __(' problèmes de sécurité ', 'securicheck') : __(' problème de sécurité ', 'securicheck')); ?></li>
                                        <li class=<?php echo esc_attr($this->audit->resultats->nb_erreurs_etat_performances > 0 ? "error" : "ok"); ?>> <?php echo esc_html($this->audit->resultats->nb_erreurs_etat_performances); ?><?php echo esc_html($this->audit->resultats->nb_erreurs_etat_performances > 1 ? __(' problèmes de performance', 'securicheck') : __(' problème de performance', 'securicheck')); ?></li>
                                        <li class=<?php echo esc_attr($this->audit->resultats->nbFailedConnexions > 0 ? "warning" : "ok"); ?>> <?php echo esc_html($this->audit->resultats->nbFailedConnexions); ?><?php echo esc_html($this->audit->resultats->nbFailedConnexions > 1 ? __(' tentatives de connexions échouées', 'securicheck') : __(' tentative de connexion échouée', 'securicheck')); ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hpixl-securicheck-admin-panneau-audit-tabs">
                        <div class="hpixl-securicheck-admin-audit-tabs">
                            <input type="radio" id="tab1" name="tab" checked="true" onclick="openOnglet(event, 'FIRST')" class="tablinks">
                            <label for="tab1"><?php _e('Santé du site', 'securicheck'); ?><?php if ($this->audit->resultats->nb_erreurs_sante_site > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo esc_html($this->audit->resultats->nb_erreurs_sante_site); ?></span><?php } ?></label>
                            <input type="radio" id="tab2" name="tab" onclick="openOnglet(event, 'SECOND')" class="tablinks">
                            <label for="tab2"><?php _e('Sécurité', 'securicheck'); ?><?php if ($this->audit->resultats->nb_erreurs_securite > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo esc_html($this->audit->resultats->nb_erreurs_securite); ?></span><?php } ?></label>
                            <input type="radio" id="tab3" name="tab" onclick="openOnglet(event, 'THIRD')" class="tablinks">
                            <label for="tab3"><?php _e('Mises à jour', 'securicheck'); ?><?php if ($this->audit->resultats->nbErreurMajs > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo esc_html($this->audit->resultats->nbErreurMajs); ?></span><?php } ?></label>
                            <input type="radio" id="tab4" name="tab" onclick="openOnglet(event, 'FOURTH')" class="tablinks">
                            <?php $nbConFailed = $this->audit->resultats->nbFailedConnexions; ?>
                            <label for="tab4"><?php _e('Connexions', 'securicheck'); ?><?php if ($nbConFailed > 0) { ?><span class="hpixl-securicheck-audit-badge-warning"><?php echo esc_html($nbConFailed); ?></span><?php } ?></label>
                            <input type="radio" id="tab5" name="tab" onclick="openOnglet(event, 'FIFTH')" class="tablinks">
                            <label for="tab5"><?php _e('Liste des Audits', 'securicheck'); ?></label>
                        </div>
                        <div id="FIRST" class="hpixl-securicheck-admin-audit-tabcontent">
                            <h3><?php _e('Santé du site', 'securicheck'); ?></h3>
                            <p><?php _e('Vous trouverez ci dessous les informations concernant la santé générale de votre site', 'securicheck'); ?></p>
                            <?php require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_audit_onglet-sante.php'; ?>
                        </div>

                        <div id="SECOND" class="hpixl-securicheck-admin-audit-tabcontent" style="display: none;">
                            <h3><?php _e('Sécurité', 'securicheck'); ?></h3>
                            <p><?php _e('Vous trouverez ci dessous les informations concernant la sécurité de votre site', 'securicheck'); ?></p>
                            <?php require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_audit_onglet-securite.php'; ?>
                        </div>

                        <div id="THIRD" class="hpixl-securicheck-admin-audit-tabcontent" style="display: none;">
                            <h3><?php _e('Mises à jour', 'securicheck'); ?></h3>
                            <p><?php _e('Vous trouverez ci dessous les informations concernant les mises à jour de votre site', 'securicheck'); ?></p>
                            <?php require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_audit_onglet-mises-a-jour.php'; ?>
                        </div>
                    </div>

                    <div id="FOURTH" class="hpixl-securicheck-admin-audit-tabcontent" style="display: none;">
                        <h3><?php _e('Connexions sur les 30 derniers jours avant l\'audit chargé', 'securicheck'); ?></h3>
                        <p><?php _e('Vous trouverez ci dessous les informations concernant les connexions à votre site lors des 30 derniers jours avant l\'audit chargé.', 'securicheck'); ?></p>
                        <?php require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_audit_onglet-connexions.php'; ?>
                    </div>
                    <div id="FIFTH" class="hpixl-securicheck-admin-audit-tabcontent" style="display: none;">
                        <h3><?php _e('Liste des Audits', 'securicheck'); ?></h3>
                        <p><?php _e('Vous trouverez ci dessous tous les Audits réalisés', 'securicheck'); ?></p>
                        <?php require plugin_dir_path(dirname(__FILE__)) . 'admin/Screen_audit_onglet-liste-audits.php'; ?>

                    </div>
                </div>
            </div>
<?php
        }
    }
}
