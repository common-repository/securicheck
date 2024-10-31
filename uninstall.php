<?php
// Si ce fichier est appelé directement, exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

//suppression des tables d'audit à la suppression du plugin si le réglage est fait en ce sens
if (get_option("hpixl_securicheck_toggle_delete_audits_after_uninstall")) {
    // Nom des tables à supprimer
    global $wpdb;
    //pas les constantes car le plugin est désactivé donc on ne peut pas les lire
    $tableAudit = $wpdb->prefix . "securicheck_audit";
    $tableAuditResultats = $wpdb->prefix . "securicheck_audit_resultats";

    // Supprimer les tables si elles existent
    if (hpixl_securicheck_table_exists($tableAudit)) {
        $wpdb->query($wpdb->prepare(
            "DROP TABLE IF EXISTS %i",
            esc_sql($tableAudit)
        ));
    }

    if (hpixl_securicheck_table_exists($tableAuditResultats)) {
        $wpdb->query($wpdb->prepare(
            "DROP TABLE IF EXISTS %i",
            esc_sql($tableAuditResultats)
        ));
    }
}

// Supprimer les options des reglages is on a bien coché l'option qu'il faut
if (get_option("hpixl_securicheck_toggle_delete_reglages_after_uninstall")) {
    delete_option('hpixl_securicheck_hotlinking_image_url');
    delete_option('hpixl_securicheck_toggle_notifications');
    delete_option('hpixl_securicheck_destinataire_email_notifications');
    delete_option('hpixl_securicheck_toggle_limite_nombre_audit');
    delete_option('hpixl_securicheck_text_limite_nombre_audit');
    delete_option('hpixl_securicheck_toggle_delete_audits_after_uninstall');
    delete_option('hpixl_securicheck_toggle_delete_reglages_after_uninstall');
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
        $result = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", esc_sql($table_name)));
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
