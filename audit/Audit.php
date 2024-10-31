<?php
defined('ABSPATH') || exit;


if (!class_exists('hpixl_securicheck_audit')) {

    class hpixl_securicheck_audit
    {
        public $id_audit;
        public $score;
        public $date;
        public $resultats;
        public $type;

        public function __construct($id, $type)
        {
            $this->date = "N/A";
            $this->id_audit = "0";
            $this->score = 0;
            $this->type = $type;

            if ($id) {
                $this->chargerAudit($id);
            } else {
                $this->creerAudit($this->type);
            }
            if ($this->resultats) {
                $this->score = $this->resultats->score_audit;
            }
        }


        /**
         * Insertion d'un nouvel audit en bdd
         */
        public function creerAudit()
        {

            global $wpdb;
            $tableAudit = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;
            if (hpixl_securicheck_table_exists($tableAudit)) {
                // Obtenez l'horodatage actuel selon le fuseau horaire de WordPress
                $current_time = current_time('timestamp');
                // Formatez l'horodatage en utilisant le fuseau horaire de WordPress
                $date = date_i18n('Y-m-d H:i:s', $current_time);
                //maj de l'audit avec la date
                $this->date = hpixl_securicheck_format_date($date);

                $wpdb->query($wpdb->prepare(
                    "INSERT INTO %i (date, type) VALUES (%s, %s)",
                    esc_sql($tableAudit),
                    $date,
                    $this->type
                ));

                //l'id de l'audit créé
                $this->id_audit  =  0;
                // Vérifier si l'insertion a réussi
                if ($wpdb->last_error === '') {
                    // L'insertion a réussi, obtenir l'ID de l'audit créé
                    $this->id_audit  = $wpdb->insert_id;
                } else {
                    // L'insertion a échoué, gérer l'erreur ici
                    echo "Erreur lors de la création de l'audit : " . esc_html($wpdb->last_error);
                }


                //on fait tous les tests lié à l'audit
                $resultats = new hpixl_securicheck_resultatAudit($this->id_audit, true);

                //on ajout les resultats à l'audit
                $this->resultats = $resultats;
                //on ajoute le score à l'audit
                $this->score = $this->resultats->score_audit;

                // Données à mettre à jour
                $wpdb->query($wpdb->prepare(
                    "UPDATE %i 
                    SET score = %d, 
                        pb_total = %d, 
                        pb_techniques = %d, 
                        pb_fonctionnels = %d, 
                        pb_securite = %d, 
                        pb_performance = %d 
                    WHERE id = %d",
                    esc_sql($tableAudit),
                    $this->score,
                    $this->resultats->nb_total_erreurs,
                    $this->resultats->nb_erreurs_etat_technique,
                    $this->resultats->nb_erreurs_etat_fonctionnel,
                    $this->resultats->nb_erreurs_securite,
                    $this->resultats->nb_erreurs_etat_performances,
                    $this->id_audit
                ));


                //envoi du mail pour avertir
                $destinataire = get_option('admin_email'); // valeur par défaut
                $mon_option = get_option('hpixl_securicheck_toggle_notifications');
                $mon_option2 = get_option('hpixl_securicheck_destinataire_email_notifications');
                if (!empty($mon_option) && $mon_option) {
                    if (!empty($mon_option2) && $mon_option2 && hpixl_securicheck_is_valid_email($mon_option2)) {
                        $destinataire = get_option('hpixl_securicheck_destinataire_email_notifications');
                    }

                    //le tableau avec le nb d'erreurs par critere
                    $tableauNbErreurs = array();
                    $tableauNbErreurs['nb_total_erreurs'] = $this->resultats->nb_total_erreurs;
                    $tableauNbErreurs['nb_erreurs_etat_technique'] = $this->resultats->nb_erreurs_etat_technique;
                    $tableauNbErreurs['nb_erreurs_etat_fonctionnel'] = $this->resultats->nb_erreurs_etat_fonctionnel;
                    $tableauNbErreurs['nb_erreurs_etat_performances'] = $this->resultats->nb_erreurs_etat_performances;
                    $tableauNbErreurs['nb_erreurs_securite_technique'] = $this->resultats->nb_erreurs_securite_technique;
                    $tableauNbErreurs['nb_erreurs_securite_enTetesHTTP'] = $this->resultats->nb_erreurs_securite_enTetesHTTP;
                    $tableauNbErreurs['nb_maj_plugins'] = $this->resultats->nb_maj_plugins;
                    $tableauNbErreurs['nb_maj_themes'] = $this->resultats->nb_maj_themes;
                    $tableauNbErreurs['nbMajWordpress'] = $this->resultats->nbMajWordpress;
                    $tableauNbErreurs['nbConnexionsOk'] = $this->resultats->nbConnexionsOk;
                    $tableauNbErreurs['nbConnexionsOkAdmin'] = $this->resultats->nbConnexionsOkAdmin;
                    $tableauNbErreurs['nbFailedConnexions'] = $this->resultats->nbFailedConnexions;


                    //envoi de mail en fin d'audit si la fonction existe
                    hpixl_securicheck_envoi_mail_fin_audit($destinataire, $this->score, $tableauNbErreurs);

                    // on vérifie si on l'utilisateur a défini une limite du nombre d'audit en base de données
                    $hasNbAuditLimit = get_option('hpixl_securicheck_toggle_limite_nombre_audit');
                    $nbLimitAUdit = get_option('hpixl_securicheck_text_limite_nombre_audit');
                    if ($hasNbAuditLimit) {
                        if (is_numeric($nbLimitAUdit) && ctype_digit((string) $nbLimitAUdit)) {
                            hpixl_securicheck_delete_too_much_audit($nbLimitAUdit);
                        }
                    }
                }
            }
        }

        /**
         * Chargement d'un audit en bdd avec son id
         */
        public function chargerAudit($id)
        {
            global $wpdb;

            if ($id && $id >= 0) {
                //si id alors on charge l'audit qui a l'id selectionné
                $this->id_audit = $id;
                //chargement de la date de l'audit concerné
                $table = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;
                if (hpixl_securicheck_table_exists($table)) {

                    $cache_key = 'securicheck_charger_audit_result_' . $id;
                    // Tenter de récupérer le résultat du cache
                    $resultatRequeteAudit = wp_cache_get($cache_key);
                    if ($resultatRequeteAudit === false) {
                        // Si le résultat n'est pas en cache, effectuer la requête
                        $resultatRequeteAudit = $wpdb->get_results($wpdb->prepare(
                            "SELECT * FROM %i WHERE id = %d ORDER BY date DESC LIMIT 1",
                            esc_sql($table),
                            $id
                        ));
                        // Mettre en cache le résultat pour une utilisation future
                        wp_cache_set($cache_key, $resultatRequeteAudit, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
                    }

                    foreach ($resultatRequeteAudit as $r) {
                        $this->date = hpixl_securicheck_format_date($r->date);
                    }
                }
            } else {
                //si pas d'id alors on charge le dernier audit
                $table = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;
                if (hpixl_securicheck_table_exists($table)) {

                    $cache_key = 'securicheck_latest_audit_result_' . $table;
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
                        $this->id_audit = $r->id;
                        $this->date = hpixl_securicheck_format_date($r->date);
                        $this->score = $r->score;
                    }
                }
            }
            // les résultats de l'audit concerné
            $this->resultats = new hpixl_securicheck_resultatAudit($this->id_audit, false);
        }
    }
}
