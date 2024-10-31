<?php
defined('ABSPATH') || exit;
?>
<table class="form-table">
    <tr>
        <td class="hpixl_securicheck_toggle_title_reglages">
            <?php esc_html_e('URL de Connexion', 'securicheck'); ?>
        </td>
        <td class="hpixl_securicheck_toggle_panneau_option_reglages">
            <div class="hpixl_securicheck_toggle_option_reglages">
                <input type="checkbox" id="hpixl_securicheck_toggle_page_connexion_url" name="hpixl_securicheck_toggle_page_connexion_url" value="1" <?php checked(1, esc_attr(get_option('hpixl_securicheck_toggle_page_connexion_url')), true) ?> />
                <label for="hpixl_securicheck_toggle_page_connexion_url">Toggle</label>
                <p><?php esc_html_e(' Changer l\'url de connexion ?', 'securicheck'); ?></p>
            </div>
            <div class="hpixl_securicheck_panneau_page_connexion_url" id="hpixl_securicheck_panneau_page_connexion_url" style="<?php echo (get_option('hpixl_securicheck_toggle_page_connexion_url') == 1) ? 'display:flex;' : 'display:none;'; ?>">
                <p><?php esc_html_e('Nouvelle url : ', 'securicheck'); ?><?php echo esc_html(site_url()); ?>/</p>
                <input type="text" id="hpixl_securicheck_textarea_page_connexion_url" name="hpixl_securicheck_textarea_page_connexion_url" value="<?php echo esc_attr(get_option('hpixl_securicheck_textarea_page_connexion_url')); ?>" style="margin-left:0;" />
            </div>
            <p><?php esc_html_e('Changer l\'URL de connexion de votre site WordPress renforce la sécurité en rendant plus difficile l\'accès des attaquants, car l\'URL par défaut est connue de tous. Cela réduit les risques d\'attaques par force brute en dissimulant la page de connexion. Une URL personnalisée ajoute une couche de protection supplémentaire, compliquant les tentatives d\'intrusion et améliorant ainsi la sécurité globale de votre site WordPress.', 'securicheck'); ?></p>
        </td>
    </tr>
    <tr>
        <td class="hpixl_securicheck_toggle_title_reglages">
            <?php esc_html_e('Redirection', 'securicheck'); ?>
        </td>
        <td class="hpixl_securicheck_toggle_panneau_option_reglages">
            <div class="hpixl_securicheck_toggle_option_reglages">
                <input type="checkbox" id="hpixl_securicheck_toggle_page_connexion_redirection" name="hpixl_securicheck_toggle_page_connexion_redirection" value="1" <?php checked(1, esc_attr(get_option('hpixl_securicheck_toggle_page_connexion_redirection')), true) ?> />
                <label for="hpixl_securicheck_toggle_page_connexion_redirection">Toggle</label>
                <p><?php esc_html_e(' Activer la redirection ?', 'securicheck'); ?></p>
            </div>
            <div class="hpixl_securicheck_panneau_page_connexion_redirection" id="hpixl_securicheck_panneau_page_connexion_redirection" style="<?php echo (get_option('hpixl_securicheck_toggle_page_connexion_redirection') == 1) ? 'display:flex;' : 'display:none;'; ?>">
                <p><?php esc_html_e('Url de redirection : ', 'securicheck'); ?><?php echo esc_html(site_url()); ?>/</p>
                <input type="text" id="hpixl_securicheck_textarea_page_connexion_redirection" name="hpixl_securicheck_textarea_page_connexion_redirection" value="<?php echo esc_attr(get_option('hpixl_securicheck_textarea_page_connexion_redirection')); ?>" />
            </div>
            <p><?php esc_html_e('Redirige les utilisateurs vers une url personnalisée ?', 'securicheck'); ?></p>
        </td>
    </tr>
</table>
<?php
submit_button();
