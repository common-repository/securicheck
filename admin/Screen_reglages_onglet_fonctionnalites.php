<?php
if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}

?>
<table class="form-table">
    <tr>
        <td class="hpixl_securicheck_toggle_title_reglages">
            <?php esc_html_e('HotLinking', 'securicheck'); ?>
        </td>
        <td class="hpixl_securicheck_toggle_panneau_option_reglages">
            <div class="hpixl_securicheck_toggle_option_reglages">
                <?php
                $value = get_option('hpixl_securicheck_hotlinking_image_url') ?? '';

                // test récupération des liens des images de la bibliothèque 
                $query_images_args = array(
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'post_status'    => 'inherit',
                    'posts_per_page' => -1,
                );

                $query_images = new WP_Query($query_images_args);

                $images = array();
                foreach ($query_images->posts as $image) {
                    $images[] = wp_get_attachment_url($image->ID);
                }
                ?>
                <p><?php esc_html_e(' Url d\'une image du site ?', 'securicheck'); ?></p>
                <select name="hpixl_securicheck_hotlinking_image_url">
                    <option value=""></option>
                    <?php
                    // if ($pages = get_pages()) {
                    foreach ($images as $image) {
                        echo '<option value="' . esc_attr($image) . '" ' . selected($image, esc_attr($value)) . '>' . esc_attr($image) . '</option>';
                    }
                    // }
                    ?>
                </select>
            </div>
            <p><?php _e('Afin de pouvoir vérifier que le HotLinking est bien désactivé, définissez ici le chemin d\'une image uploadée dans la bibliothèque de médias de votre backoffice.', 'securicheck'); ?>
                </br><?php _e('Nous pourrons alors vérifier si cette image est utilisable sur un autre site internet grâce au HotLinking.', 'securicheck'); ?></p>
        </td>
    </tr>


    <tr>
        <td class="hpixl_securicheck_toggle_title_securite">
            <?php esc_html_e('Notifications', 'securicheck'); ?>
        </td>
        <td class="hpixl_securicheck_toggle_panneau_option_reglages">
            <div class="hpixl_securicheck_toggle_option_reglages">
                <input type="checkbox" id="hpixl_securicheck_toggle_notifications" name="hpixl_securicheck_toggle_notifications" value="1" <?php checked(1, esc_attr(get_option('hpixl_securicheck_toggle_notifications')), true) ?> />
                <label for="hpixl_securicheck_toggle_notifications">Toggle</label>
                <p><?php esc_html_e(' Envoyer des notifications ?', 'securicheck'); ?></p>
            </div>
            <div class="hpixl_securicheck_panneau_notifications" id="hpixl_securicheck_panneau_notifications" style="<?php echo (get_option('hpixl_securicheck_toggle_notifications') == 1) ? 'display:flex;' : 'display:none;'; ?>">
                <p><?php _e('Email du destinataire :', 'securicheck'); ?></p>
                <input type="text" id="hpixl_securicheck_destinataire_email_notifications" name="hpixl_securicheck_destinataire_email_notifications" value="<?php echo esc_attr(get_option('hpixl_securicheck_destinataire_email_notifications')); ?>" />
            </div>
            <p><?php _e('Une notification sera envoyée aprés chaque Audit effectué', 'securicheck'); ?></p>
        </td>
    </tr>

    <tr>
        <td class="hpixl_securicheck_toggle_title_securite">
            <?php esc_html_e('Limitations', 'securicheck'); ?>
        </td>
        <td class="hpixl_securicheck_toggle_panneau_option_reglages">
            <div class="hpixl_securicheck_toggle_option_reglages">
                <input type="checkbox" id="hpixl_securicheck_toggle_limite_nombre_audit" name="hpixl_securicheck_toggle_limite_nombre_audit" value="1" <?php checked(1, esc_attr(get_option('hpixl_securicheck_toggle_limite_nombre_audit')), true) ?> />
                <label for="hpixl_securicheck_toggle_limite_nombre_audit">Toggle</label>
                <p><?php esc_html_e(' Limiter le nombre d\'audits ?', 'securicheck'); ?></p>
            </div>
            <div class="hpixl_securicheck_panneau_limitations" id="hpixl_securicheck_panneau_limitations" style="<?php echo (get_option('hpixl_securicheck_toggle_limite_nombre_audit') == 1) ? 'display:flex;' : 'display:none;'; ?>">
                <p><?php _e('Nombre maximal d\'audits à garder :', 'securicheck'); ?></p>
                <input type="text" id="hpixl_securicheck_text_limite_nombre_audit" name="hpixl_securicheck_text_limite_nombre_audit" value="<?php echo esc_attr(get_option('hpixl_securicheck_text_limite_nombre_audit')); ?>" />
            </div>
            <p><?php _e('Si vous souhaitez limiter le nombre d\'audits sauvegardés dans la base de données.', 'securicheck'); ?></p>
        </td>
    </tr>

    <tr>
        <td class="hpixl_securicheck_toggle_title_securite">
            <?php esc_html_e('Désinstallation', 'securicheck'); ?>
        </td>
        <td class="hpixl_securicheck_toggle_panneau_option_reglages">
            <p><?php esc_html_e(' Que nettoyer aprés la désinstallation complète du plugin ?', 'securicheck'); ?></p>
            <div class="hpixl_securicheck_toggle_option_reglages" style="border:none;">
                <input type="checkbox" id="hpixl_securicheck_toggle_delete_audits_after_uninstall" name="hpixl_securicheck_toggle_delete_audits_after_uninstall" value="1" <?php checked(1, esc_attr(get_option('hpixl_securicheck_toggle_delete_audits_after_uninstall')), true) ?> />
                <label for="hpixl_securicheck_toggle_delete_audits_after_uninstall">Toggle</label>
                <p><?php esc_html_e(' Supprimer les audits ?', 'securicheck'); ?></p>
            </div>
            <div class="hpixl_securicheck_toggle_option_reglages" style="border:none;">
                <input type="checkbox" id="hpixl_securicheck_toggle_delete_reglages_after_uninstall" name="hpixl_securicheck_toggle_delete_reglages_after_uninstall" value="1" <?php checked(1, esc_attr(get_option('hpixl_securicheck_toggle_delete_reglages_after_uninstall')), true) ?> />
                <label for="hpixl_securicheck_toggle_delete_reglages_after_uninstall">Toggle</label>
                <p><?php esc_html_e(' Supprimer les réglages ?', 'securicheck'); ?></p>
            </div>
            <p><?php _e('Soyez certain d\'effectuer les bons réglages. Les suppressions après désinstallation de securicheck sont irréversibles.', 'securicheck'); ?></p>
        </td>
    </tr>
</table>
<?php
submit_button();
?>