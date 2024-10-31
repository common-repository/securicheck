<?php
if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}
?>
<p></p>
<table class="hpixl-securicheck-audit-table-plugins">
    <thead>
        <th><?php _e('Date', 'securicheck'); ?></th>
        <th><?php _e('Login', 'securicheck'); ?></th>
        <th><?php _e('IP', 'securicheck'); ?></th>
        <th><?php _e('Navigateur', 'securicheck'); ?></th>
        <th><?php _e('Login existant ?', 'securicheck'); ?></th>
    </thead>
    <tbody>
        <?php
        if (is_array($listeConnexionsEchouees)) {
            foreach ($listeConnexionsEchouees as $connexion) {
        ?>
                <tr>
                    <td><?php echo esc_html(hpixl_securicheck_format_date($connexion->date)); ?></td>
                    <td><?php echo esc_html($connexion->username); ?></td>
                    <td><?php echo esc_html($connexion->ip); ?></td>
                    <td><?php echo esc_html($connexion->browser); ?></td>
                    <td><?php echo $connexion->user_exists ? "oui" : "non" ?></td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>