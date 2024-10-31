<?php
defined('ABSPATH') || exit;
?>
<table class="hpixl-securicheck-audit-table" id="hpixl-securicheck-table-list-audits">
    <tbody>
        <tr>
            <th></th>
            <th><?php _e('Date de l\'audit', 'securicheck'); ?></th>
            <th><?php _e('Score de l\'audit', 'securicheck'); ?></th>
            <th><?php _e('Nb Problèmes Total', 'securicheck'); ?></th>
            <th><?php _e('Nb Problèmes Techniques', 'securicheck'); ?></th>
            <th><?php _e('Nb Problèmes Fonctionnels', 'securicheck'); ?></th>
            <th><?php _e('Nb Problèmes Sécurité', 'securicheck'); ?></th>
            <th><?php _e('Nb Problèmes Performance', 'securicheck'); ?></th>
            <th><?php _e('Type de l\'audit', 'securicheck'); ?></th>
            <th></th>
            <th></th>
        </tr>
        <?php
        global $wpdb;
        $table = $wpdb->prefix . HPIXL_SECURICHECK_TABLE_AUDIT;

        $cache_key = 'securicheck_liste_tous_audits_' . $table;
        // Tenter de récupérer le résultat du cache
        $resultatRequeteListeAudits = wp_cache_get($cache_key);
        if ($resultatRequeteListeAudits === false) {
            // Si le résultat n'est pas en cache, effectuer la requête
            $resultatRequeteListeAudits = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM %i ORDER BY date DESC",
                esc_sql($table)
            ));

            // Mettre en cache le résultat pour une utilisation future
            wp_cache_set($cache_key, $resultatRequeteListeAudits, '', 3600); // Mettre en cache pour 1 heure (3600 secondes)
        }

        //on parcours la liste des audit pour l'afficher
        $i = 0;
        foreach ($resultatRequeteListeAudits as $audit) {
            $i++;
        ?>
            <tr>
                <td class="hpixl-securicheck-celulle-count"><?php echo esc_html($i); ?></td>
                <td class="hpixl-securicheck-celulle-date"><?php echo esc_html(hpixl_securicheck_format_date_in_french($audit->date)); ?></td>
                <td class="hpixl-securicheck-celulle-score"><?php echo esc_html($audit->score); ?></td>
                <td class="hpixl-securicheck-celulle-score"><?php echo esc_html($audit->pb_total); ?></td>
                <td class="hpixl-securicheck-celulle-score"><?php echo esc_html($audit->pb_techniques); ?></td>
                <td class="hpixl-securicheck-celulle-score"><?php echo esc_html($audit->pb_fonctionnels); ?></td>
                <td class="hpixl-securicheck-celulle-score"><?php echo esc_html($audit->pb_securite); ?></td>
                <td class="hpixl-securicheck-celulle-score"><?php echo esc_html($audit->pb_performance); ?></td>
                <td class="hpixl-securicheck-celulle-date"><?php echo esc_html($audit->type); ?></td>
                <?php /*<td class="hpixl-securicheck-celulle-icon"><a href="supprimer?id=<?php echo $audit->id; ?>"><img class="hpixl-securicheck-icon" src="<?php echo HPIXL_SECURICHECK_PLUGIN_URL; ?>images/supprimer.jpg" alt="logo hpixl-securicheck" /></a></td>*/ ?>
                <td class="hpixl-securicheck-celulle-icon"><a href="#">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="supprimer-audit" value="<?php echo esc_attr($audit->id); ?>">
                            <?php wp_nonce_field('supprimer-audit', '_wpnonce-supprimer-audit'); ?>
                            <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;">
                                <img class="hpixl-securicheck-icon" src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/supprimer.jpg" alt="logo hpixl-securicheck" />
                            </button>
                        </form>
                </td>
                <td class="hpixl-securicheck-celulle-last-icon"><a href="#">
                        <?php /*<img class="hpixl-securicheck-icon" src="<?php echo HPIXL_SECURICHECK_PLUGIN_URL; ?>images/ouvrir.jpg" alt="logo hpixl-securicheck" /></a>*/ ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="charger-audit" value="<?php echo esc_attr($audit->id); ?>">
                            <?php wp_nonce_field('charger-audit', '_wpnonce-charger-audit'); ?>
                            <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;">
                                <img class="hpixl-securicheck-icon" src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/ouvrir.jpg" alt="logo hpixl-securicheck" />
                            </button>
                        </form>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>