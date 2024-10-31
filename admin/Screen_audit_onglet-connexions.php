<?php
defined('ABSPATH') || exit;
?>
<div class="hpixl-securicheck-accordion">
    <!-- Accordion Connexions Tous les utilisateurs-->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-31" checked>
        <label for="tab-31">
            <span><img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/info_icon.svg" /><?php _e('Tous les Utilisateurs', 'securicheck'); ?></span>
        </label>
        <div class="content">
            <p><?php _e('Vous trouverez ci dessous les informations concernant les connexions à votre site internet', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table-plugins">
                <thead>
                    <th><?php _e('Date', 'securicheck'); ?></th>
                    <th><?php _e('Login', 'securicheck'); ?></th>
                    <th><?php _e('IP', 'securicheck'); ?></th>
                    <th><?php _e('Navigateur', 'securicheck'); ?></th>
                </thead>
                <tbody>
                    <?php

                    foreach ($this->audit->resultats->listConnexionsOk as $connexion) {
                    ?>
                        <tr>
                            <td><?php echo esc_html(hpixl_securicheck_format_date($connexion->date)); ?></td>
                            <td><?php echo esc_html($connexion->username); ?></td>
                            <td><?php echo esc_html($connexion->ip); ?></td>
                            <td><?php echo esc_html($connexion->browser); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Accordion Connexions les administrateurs -->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-32">
        <label for="tab-32">
            <span><img src="<?php echo esc_html(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/info_icon.svg" /><?php _e('Administrateurs', 'securicheck'); ?></span>
        </label>
        <div class="content">
            <p><?php _e('Vous trouverez ci dessous les informations concernant les connexions à votre site internet', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table-plugins">
                <thead>
                    <th><?php _e('Date', 'securicheck'); ?></th>
                    <th><?php _e('Login', 'securicheck'); ?></th>
                    <th><?php _e('IP', 'securicheck'); ?></th>
                    <th><?php _e('Navigateur', 'securicheck'); ?></th>
                </thead>
                <tbody>
                    <?php
                    foreach ($this->audit->resultats->listConnexionsOkAdmin as $connexion) {
                    ?>
                        <tr>
                            <td><?php echo  esc_html(hpixl_securicheck_format_date($connexion->date)); ?></td>
                            <td><?php echo  esc_html($connexion->username); ?></td>
                            <td><?php echo  esc_html($connexion->ip); ?></td>
                            <td><?php echo  esc_html($connexion->browser); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Accordion Connexions échouées-->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-33">
        <label for="tab-33">
            <?php $nbConFailed = $this->audit->resultats->nbFailedConnexions; ?>
            <span><?php if ($nbConFailed > 0) { ?><img src="<?php echo esc_attr(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/warning_icon.svg" /><?php } ?><?php _e('Échouées', 'securicheck'); ?><?php if ($nbConFailed > 0) { ?><span class="hpixl-securicheck-audit-badge-warning"><?php echo  esc_html($nbConFailed); ?></span><?php } ?></span>
        </label>
        <div class="content">
            <p><?php _e('Vous trouverez ci dessous les informations concernant les connexions à votre site internet', 'securicheck'); ?></p>
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
                    foreach ($this->audit->resultats->listFailedConnexions as $connexion) {
                    ?>
                        <tr>
                            <td><?php echo  esc_html(hpixl_securicheck_format_date($connexion->date)); ?></td>
                            <td><?php echo  esc_html($connexion->username); ?></td>
                            <td><?php echo  esc_html($connexion->ip); ?></td>
                            <td><?php echo  esc_html($connexion->browser); ?></td>
                            <td><?php echo  $connexion->user_exists ? "oui" : "non" ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>