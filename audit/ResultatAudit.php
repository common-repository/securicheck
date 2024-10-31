<?php
defined('ABSPATH') || exit;


if (!class_exists('HPIXL_securicheck_resultatAudit')) {

    class hpixl_securicheck_resultatAudit
    {

        public $id_audit;
        public $score_audit;
        /**
         * Santé du site 
         */
        public $nb_erreurs_sante_site;
        public $score_sante_site;
        /* technique */
        public $phpVersion;
        public $bddType;
        public $bddVersion;
        // public $isBddSupported;
        public $diskSpaceUsed;
        public $dbDiskUsed;

        /* fonctionnel*/
        public $nb_erreurs_etat_technique;
        public $nb_maj_plugins;
        public $nb_maj_themes;
        public $maj_wordpress;
        public $theme_info;
        public $theme_name;
        public $theme_parent;
        public $nb_old_plugins;
        public $nb_old_themes;
        public $has_theme_enfant;
        public $nb_erreurs_etat_fonctionnel;
        public $nb_erreurs_etat_performances;
        /* performance */
        public $nbMaxiRevisions;
        public $isCacheActif;


        /**
         * sécurité
         */
        public $nb_erreurs_securite;
        public $score_securite;
        /* sécurité technique*/
        public $nb_erreurs_securite_technique;
        public $isHttps;
        public $prefixBdd;
        public $compteAdminExiste;
        public $nbAdmin;
        public $isXmlRpcDisabled;
        public $isLiveWriterProtected;
        public $isApiRestDisabled;
        public $isEditeurFichierDisabled;
        public $isDirNavigationProtected;
        public $isUploadsProtected;
        public $isInfoServerHidden;
        public $isWpConfigProtected;
        public $isHtAccessProtected;
        public $isModeDebugOn;
        public $boUrl;
        public $isWordpressVersionHidden;
        public $id_admin_principal;
        public $is_hotlinking_disabled;
        public $is_chatgpt_accessible;
        public $is_application_password_active;
        public $isArchiveAuthorDisabled;
        public $isScanAuthorDisabled;
        /* en tête http*/
        public $nb_erreurs_securite_enTetesHTTP;
        public $isHstsOk;
        public $isRefererPolicyOk;
        public $isCspOk;
        public $isXFrameOptionsOk;
        public $isXXssProtectionOk;
        public $isXContentTypeOptionsOk;
        public $isPermissionsPolicyOk;
        public $isAccessControlAllowOriginOk;

        /**
         * Mise à jour 
         */
        public $misesAJourPlugins;
        public $listePlugins;
        public $misesAJourThemes;
        public $listeThemes;
        public $wp_version;
        public $wp_latest_version;
        public $nbMajWordpress;
        public $nbErreurMajs;
        public $score_majs;

        /**
         * Connexions 
         */
        public $nbFailedConnexions;
        public $listFailedConnexions;
        public $nbConnexionsOk;
        public $listConnexionsOk;
        public $nbConnexionsOkAdmin;
        public $listConnexionsOkAdmin;

        /* total*/
        public $nb_total_erreurs;

        public function __construct($id_audit, $create)
        {
            $this->id_audit = $id_audit;

            //si on doit créer le resultat
            if ($create) {

                /**
                 * Santé du site
                 */

                /* pour onglet etat technique*/
                $this->nb_erreurs_etat_technique = 0;
                $this->phpVersion = hpixl_securicheck_get_php_version();
                $this->bddType = hpixl_securicheck_get_bdd_type();
                $this->bddVersion = hpixl_securicheck_get_bdd_version();
                $this->nb_erreurs_etat_technique += version_compare($this->phpVersion, HPIXL_SECURICHECK_PHP_MINI) < 0 ? 1 : 0;
                $this->diskSpaceUsed = hpixl_securicheck_get_space_usage();
                $this->dbDiskUsed = hpixl_securicheck_get_database_disk_usage();
                //$this->isBddSupported = hpixl_securicheck_is_bdd_supported();
                //$this->nb_erreurs_etat_technique += $this->isBddSupported ? 1 : 0;

                /* pour onglet etat fonctionnel*/
                $this->nb_maj_plugins = hpixl_securicheck_get_nb_maj_plugins();
                $this->nb_maj_themes = hpixl_securicheck_get_nb_maj_themes();
                $this->maj_wordpress = hpixl_securicheck_get_nb_maj_wordpress() >= 1 ? "oui" : "non";
                $this->theme_info = hpixl_securicheck_get_theme_info();
                $this->theme_name = hpixl_securicheck_get_theme_name();
                $this->theme_parent = hpixl_securicheck_get_theme_parent();
                $this->nb_old_plugins = hpixl_securicheck_get_disabled_plugins_count();
                $this->nb_old_themes = hpixl_securicheck_get_disabled_themes_count();
                /* si pas de theme enfant on incremente le nb de problemes*/
                $this->has_theme_enfant = hpixl_securicheck_is_child_theme_installed() === "oui" ? 0 : 1;
                $this->nb_erreurs_etat_fonctionnel = ($this->nb_maj_plugins > 0 ? 1 : 0) + ($this->nb_maj_themes > 0 ? 1 : 0) + ($this->maj_wordpress === "oui" ? 1 : 0) + ($this->nb_old_plugins > 0 ? 1 : 0) + ($this->nb_old_themes > 2 ? 1 : 0) + $this->has_theme_enfant;

                /* pour onglet etat performances */
                $this->nb_erreurs_etat_performances = 0;
                $this->nbMaxiRevisions = hpixl_securicheck_getNombreMaxRevisions();
                $this->nb_erreurs_etat_performances += $this->nbMaxiRevisions > 10 || $this->nbMaxiRevisions == -1 ? 1 : 0;
                $this->isCacheActif = hpixl_securicheck_isCacheActif();
                $this->nb_erreurs_etat_performances += $this->isCacheActif ? 0 : 1;

                $this->nb_erreurs_sante_site = $this->nb_erreurs_etat_technique + $this->nb_erreurs_etat_fonctionnel + $this->nb_erreurs_etat_performances;
                $this->score_sante_site = 100 - (($this->nb_erreurs_etat_fonctionnel / 12) * 100);

                /**
                 * Sécurité
                 */
                $this->nb_erreurs_securite = 0;
                // pour onglet sécurité technique
                $this->nb_erreurs_securite_technique = 0;
                $this->isHttps = hpixl_securicheck_isHTTPSUrl();
                $this->nb_erreurs_securite_technique += $this->isHttps ? 0 : 1;
                $this->prefixBdd = hpixl_securicheck_getBddPrefix();
                $this->nb_erreurs_securite_technique += $this->prefixBdd === "wp_" ? 1 : 0;
                $this->compteAdminExiste = hpixl_securicheck_compteAdminExiste();
                $this->nb_erreurs_securite_technique += $this->compteAdminExiste ? 1 : 0;
                $this->nbAdmin = hpixl_securicheck_nombreAdministrateurs();
                $this->nb_erreurs_securite_technique += $this->nbAdmin > 1 ? 1 : 0;
                $this->isXmlRpcDisabled = hpixl_securicheck_checkXmlRpcDisabled();
                $this->nb_erreurs_securite_technique += $this->isXmlRpcDisabled ? 0 : 1;
                $this->isLiveWriterProtected = hpixl_securicheck_isLiveWriterProtected();
                $this->nb_erreurs_securite_technique += $this->isLiveWriterProtected ? 0 : 1;
                $this->isApiRestDisabled = hpixl_securicheck_checkRestApiDisabled();
                $this->nb_erreurs_securite_technique += $this->isApiRestDisabled == 0 ? 1 : 0;
                $this->isEditeurFichierDisabled = hpixl_securicheck_isEditeurFichierDisabled();
                $this->nb_erreurs_securite_technique += $this->isEditeurFichierDisabled ? 0 : 1;
                $this->isDirNavigationProtected = hpixl_securicheck_checkDirectoryListing();
                $this->nb_erreurs_securite_technique += $this->isDirNavigationProtected ? 0 : 1;
                $this->isUploadsProtected = hpixl_securicheck_isUploadsDirProtected();
                $this->nb_erreurs_securite_technique += $this->isUploadsProtected ? 0 : 1;
                $this->isInfoServerHidden = hpixl_securicheck_checkServerInfoHidden();
                $this->nb_erreurs_securite_technique += $this->isInfoServerHidden ? 0 : 1;
                $this->isWpConfigProtected = hpixl_securicheck_checkWpConfigProtection();
                $this->nb_erreurs_securite_technique += $this->isWpConfigProtected ? 0 : 1;
                $this->isHtAccessProtected = hpixl_securicheck_checkHtaccessProtection();
                $this->nb_erreurs_securite_technique += $this->isHtAccessProtected ? 0 : 1;
                $this->isModeDebugOn = hpixl_securicheck_debugActif();
                $this->nb_erreurs_securite_technique += $this->isModeDebugOn ? 1 : 0;
                $this->boUrl = hpixl_securicheck_getBackEndUrl();
                $this->nb_erreurs_securite_technique += $this->boUrl ? 0 : 1;
                $this->isWordpressVersionHidden = hpixl_securicheck_is_wordpress_version_hidden();
                $this->nb_erreurs_securite_technique += $this->isWordpressVersionHidden ? 0 : 1;
                $this->id_admin_principal = hpixl_securicheck_get_main_admin_id();
                $this->nb_erreurs_securite_technique += $this->id_admin_principal == 1 ? 1 : 0;
                //check si le paramètre de choix d'une image du site est bien rempli
                $urlHotlinking = get_option('hpixl_securicheck_hotlinking_image_url') ?? '';
                if ($urlHotlinking != "") {
                    $this->is_hotlinking_disabled = hpixl_securicheck_checkHotlinkingDisabled();
                    //var_dump("resultat:" . $this->is_hotlinking_disabled);
                    $this->nb_erreurs_securite_technique += $this->is_hotlinking_disabled === "oui" ? 0 : 1;
                } else {
                    $this->is_hotlinking_disabled = HPIXL_SECURICHECK_HOTLINKING_PARAMETRE_NON_DEFINI;
                    $this->nb_erreurs_securite_technique += 1;
                }

                $this->is_chatgpt_accessible = hpixl_securicheck_is_accessible_by_chatgpt();
                $this->nb_erreurs_securite_technique += $this->is_chatgpt_accessible ? 1 : 0;
                $this->is_application_password_active = hpixl_securicheck_is_application_password_active();
                $this->nb_erreurs_securite_technique += $this->is_application_password_active ? 1 : 0;
                $this->isArchiveAuthorDisabled = hpixl_securicheck_is_author_archive_disabled();
                $this->nb_erreurs_securite_technique += $this->isArchiveAuthorDisabled ? 0 : 1;
                $this->isScanAuthorDisabled = hpixl_securicheck_is_scan_author_disabled();
                $this->nb_erreurs_securite_technique += $this->isScanAuthorDisabled ? 0 : 1;

                /* pour onglet en tête http*/
                $this->nb_erreurs_securite_enTetesHTTP = 0;
                $this->isHstsOk = hpixl_securicheck_isStrictTransportSecurityOk();
                $this->nb_erreurs_securite_enTetesHTTP += $this->isHstsOk ? 0 : 1;
                $this->isRefererPolicyOk = hpixl_securicheck_isReferrerPolicyOk();
                $this->nb_erreurs_securite_enTetesHTTP += $this->isRefererPolicyOk ? 0 : 1;
                $this->isCspOk = hpixl_securicheck_isContentSecurityPolicyOk();
                $this->nb_erreurs_securite_enTetesHTTP += $this->isCspOk ? 0 : 1;
                $this->isXFrameOptionsOk = hpixl_securicheck_isXFrameOptionsOk();
                $this->nb_erreurs_securite_enTetesHTTP += $this->isXFrameOptionsOk ? 0 : 1;
                $this->isXXssProtectionOk = hpixl_securicheck_isXXssProtectionOk();
                $this->nb_erreurs_securite_enTetesHTTP += $this->isXXssProtectionOk ? 0 : 1;
                $this->isXContentTypeOptionsOk = hpixl_securicheck_isXContentTypeOptionsOk();
                $this->nb_erreurs_securite_enTetesHTTP += $this->isXContentTypeOptionsOk ? 0 : 1;
                $this->isPermissionsPolicyOk = hpixl_securicheck_isPermissionsPolicyOk();
                $this->nb_erreurs_securite_enTetesHTTP += $this->isPermissionsPolicyOk ? 0 : 1;
                $this->isAccessControlAllowOriginOk = hpixl_securicheck_isAccessControlAllowOriginOk();
                $this->nb_erreurs_securite_enTetesHTTP += $this->isAccessControlAllowOriginOk ? 0 : 1;

                $this->nb_erreurs_securite = $this->nb_erreurs_securite_technique + $this->nb_erreurs_securite_enTetesHTTP;
                $this->score_securite = 100 - (($this->nb_erreurs_securite / 30) * 100);

                $this->misesAJourPlugins = wp_json_encode(hpixl_securicheck_get_plugin_updates());
                $lesPlugins = hpixl_securicheck_get_plugins();
                $this->listePlugins = wp_json_encode($lesPlugins);
                $this->misesAJourThemes = wp_json_encode(hpixl_securicheck_get_theme_updates());
                $lesThemes = hpixl_securicheck_get_themes();
                $this->listeThemes = wp_json_encode($lesThemes);
                $this->wp_version = get_bloginfo('version');
                // Récupération de la dernière version stable de WordPress
                $this->wp_latest_version = get_core_updates();
                $this->wp_latest_version = !empty($this->wp_latest_version) ? $this->wp_latest_version[0]->current : '';
                $this->nbMajWordpress = $this->wp_latest_version != $this->wp_version ? 1 : 0;

                $this->nbErreurMajs = $this->nb_maj_plugins + $this->nb_maj_themes + $this->nbMajWordpress;
                $nbPlugins = count($lesPlugins);
                $nbThemes = count($lesThemes);
                $this->score_majs = 100 - (($this->nb_maj_plugins + $this->nb_maj_themes + $this->nbMajWordpress) / ($nbPlugins + $nbThemes + 1)) * 100;

                /**
                 *  Connexions 
                 * */
                $this->listFailedConnexions = hpixl_securicheck_get_connexions_failed_by_audit($this->id_audit);
                $this->nbFailedConnexions = count($this->listFailedConnexions);
                $this->listConnexionsOk = hpixl_securicheck_get_connexions_ok_by_audit($this->id_audit);
                $this->nbConnexionsOk = count($this->listConnexionsOk);
                $this->listConnexionsOkAdmin = hpixl_securicheck_get_connexions_ok_admin_by_audit($this->id_audit);
                $this->nbConnexionsOkAdmin = count($this->listConnexionsOkAdmin);

                /**
                 * TOTAL
                 */
                //nombre erreurs total : pas 2 fois les majs
                $this->nb_total_erreurs = $this->nb_erreurs_sante_site + $this->nb_erreurs_securite; //+ $this->nbErreurMajs;
                $this->score_audit = round(($this->score_sante_site + $this->score_securite + $this->score_majs) / 3, 0);

                // on fait les requetes en bdd
                $listeAttributs = get_object_vars($this);
                global $wpdb;
                $tableAudit = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_RESULTATS;

                if (hpixl_securicheck_table_exists($tableAudit)) {
                    foreach ($listeAttributs as $attribut => $valeur) {
                        if ($attribut != 'id_audit' && $attribut != "listFailedConnexions" && $attribut != "listConnexionsOk" && $attribut != "listConnexionsOkAdmin") {

                            $id_audit = (int) $this->id_audit;
                            $action = sanitize_text_field($attribut);
                            $resultat = sanitize_text_field($this->{$attribut});

                            $wpdb->query($wpdb->prepare(
                                "INSERT INTO %i (id_audit, action, resultat) VALUES (%d, %s, %s)",
                                esc_sql($tableAudit),
                                $id_audit,
                                $action,
                                $resultat
                            ));
                        }
                    }
                }
                // pour le chargement des données aprés l'insertion en bdd 
                // pour ces données, c'est stocké en bdd en json et il faut le dparser ensuite pour en faire des tableaux 
                $this->misesAJourPlugins = json_decode($this->misesAJourPlugins, true);
                $this->listePlugins = json_decode($this->listePlugins, true);
                $this->misesAJourThemes = json_decode($this->misesAJourThemes, true);
                $this->listeThemes = json_decode($this->listeThemes, true);
            } else {
                //sinon on va chercher les résultats avec l'id de l'audit               
                /* on fait les requetes en bdd*/
                $listeAttributs = get_object_vars($this);

                global $wpdb;
                $tableAudit = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT_RESULTATS;

                foreach ($listeAttributs as $attribut => $valeur) {

                    if ($attribut === "listFailedConnexions") {
                        // Pour les connexions
                        $this->listFailedConnexions = hpixl_securicheck_get_connexions_failed_by_audit($this->id_audit);
                    } else if ($attribut === "listConnexionsOk") {
                        $this->listConnexionsOk = hpixl_securicheck_get_connexions_ok_by_audit($this->id_audit);
                    } else if ($attribut === "listConnexionsOkAdmin") {
                        $this->listConnexionsOkAdmin = hpixl_securicheck_get_connexions_ok_admin_by_audit($this->id_audit);
                    } else 
                    if ($attribut != 'id_audit') {
                        if (hpixl_securicheck_table_exists($tableAudit)) {

                            $cache_key = 'securicheck_audit_results_' . $this->id_audit . '_' . $attribut;

                            // Tenter de récupérer le résultat du cache
                            $resultat = wp_cache_get($cache_key);

                            if ($resultat === false) {
                                // Si le résultat n'est pas en cache, effectuer la requête
                                $resultat = $wpdb->get_results($wpdb->prepare(
                                    "SELECT * FROM %i WHERE id_audit = %d AND action = %s",
                                    esc_sql($tableAudit),
                                    $this->id_audit,
                                    $attribut
                                ));

                                // Mettre en cache le résultat pour une utilisation future
                                wp_cache_set($cache_key, $resultat, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
                            }

                            //on parcours les résultats pour alimenter la classe
                            foreach ($resultat as $enreg) {
                                if ($attribut === "misesAJourPlugins" || $attribut === "misesAJourThemes" || $attribut === "listePlugins" || $attribut === "listeThemes") {
                                    $this->{$attribut} = json_decode($enreg->resultat, true);
                                    // var_dump($this->{$attribut});
                                } else {
                                    //ces 3 paramètres ne seront pas en bdd
                                    $this->{$attribut} = $enreg->resultat;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
