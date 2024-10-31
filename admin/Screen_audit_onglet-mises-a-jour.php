<?php
defined('ABSPATH') || exit;
?>
<div class="hpixl-securicheck-accordion">
    <!-- Accordéon Plugins -->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-21" checked>
        <label for="tab-21">
            <span><?php if ($this->audit->resultats->nb_maj_plugins > 0) { ?><img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/error_icon.svg" /><?php } ?><?php _e('Plugins', 'securicheck'); ?><?php if ($this->audit->resultats->nb_maj_plugins > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo esc_html($this->audit->resultats->nb_maj_plugins); ?></span><?php } ?></span>
        </label>
        <div class="content">
            <p><?php _e('Les plugins à mettre à jour :', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table-plugins">
                <thead>
                    <th><?php _e('Plugin', 'securicheck'); ?></th>
                    <th><?php _e('Version Actuelle', 'securicheck'); ?></th>
                    <th><?php _e('Version Disponible', 'securicheck'); ?></th>
                </thead>
                <tbody>
                    <?php
                    // $pluginsInstalles = get_plugins();
                    $pluginsInstalles = $this->audit->resultats->listePlugins;
                    // Parcours les informations sur les themes installés
                    if (is_array($pluginsInstalles)) {
                        foreach ($pluginsInstalles as $slug => $plugin) {
                            // Initialise la dernière version disponible à "N/A" par défaut
                            $derniereVersion = "N/A";
                            // Vérifie si des informations de mise à jour sont disponibles pour ce theme
                            if ($this->audit->resultats->misesAJourPlugins && is_array($this->audit->resultats->misesAJourPlugins) && array_key_exists($slug, $this->audit->resultats->misesAJourPlugins) && !empty($this->audit->resultats->misesAJourPlugins[$slug]['new_version'])) {
                                // Récupère la dernière version disponible
                                $derniereVersion = $this->audit->resultats->misesAJourPlugins[$slug]['new_version'];
                            } else {
                                $derniereVersion = $plugin['Version'];
                            }
                    ?>
                            <tr <?php echo $derniereVersion != $plugin['Version'] ?  "class='erreur'" : "" ?>>
                                <td> <?php echo esc_html($plugin['Name']); ?></td>
                                <td><?php echo esc_html($plugin['Version']); ?></td>
                                <td><?php echo esc_html($derniereVersion); ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Accordéon Thèmes -->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-22">
        <label for="tab-22">
            <span><?php if ($this->audit->resultats->nb_maj_themes > 0) { ?><img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/error_icon.svg" /><?php } ?><?php _e('Thèmes', 'securicheck'); ?><?php if ($this->audit->resultats->nb_maj_themes > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo esc_html($this->audit->resultats->nb_maj_themes); ?></span><?php } ?></span>
        </label>
        <div class="content">
            </table>
            <p><?php _e('Les thèmes à mettre à jour :', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table-plugins">
                <thead>
                    <th><?php _e('Thème', 'securicheck'); ?></th>
                    <th><?php _e('Version Actuelle', 'securicheck'); ?></th>
                    <th><?php _e('Version Disponible', 'securicheck'); ?></th>
                </thead>
                <tbody>
                    <?php
                    $themesInstalles = $this->audit->resultats->listeThemes;
                    // Parcours les informations sur les themes installés
                    if (is_array($themesInstalles)) {
                        foreach ($themesInstalles as $slug => $theme) {
                            // Initialise la dernière version disponible à "N/A" par défaut
                            $derniereVersion = "N/A";
                            // Vérifie si des informations de mise à jour sont disponibles pour ce theme
                            if ($this->audit->resultats->misesAJourThemes && array_key_exists($slug, $this->audit->resultats->misesAJourThemes) && !empty($this->audit->resultats->misesAJourThemes[$slug]['new_version'])) {
                                // Récupère la dernière version disponible
                                $derniereVersion = $this->audit->resultats->misesAJourThemes[$slug]['new_version'];
                            } else {
                                $derniereVersion = $theme['Version'];
                            }
                    ?>
                            <tr <?php echo $derniereVersion != $theme['Version'] ?  "class='erreur'" : "" ?>>
                                <td> <?php echo esc_html($theme['Name']); ?></td>
                                <td><?php echo esc_html($theme['Version']); ?></td>
                                <td><?php echo esc_html($derniereVersion); ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Accordéon WordPress -->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-23">
        <label for="tab-23">
            <span><?php if ($this->audit->resultats->nbMajWordpress > 0) { ?><img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/error_icon.svg" /><?php } ?><?php _e('WordPress', 'securicheck'); ?><?php if ($this->audit->resultats->nbMajWordpress > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo esc_html($this->audit->resultats->nbMajWordpress); ?></span><?php } ?></span>
        </label>
        <div class="content">
            <p><?php _e('Les mises à jour WordPress :', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table-plugins">
                <thead>
                    <th></th>
                    <th><?php _e('Version Actuelle', 'securicheck'); ?></th>
                    <th><?php _e('Version Disponible', 'securicheck'); ?></th>
                </thead>
                <tbody>
                    <tr <?php echo $this->audit->resultats->wp_version != $this->audit->resultats->wp_latest_version ?  "class='erreur'" : "" ?>>
                        <td> <?php echo "WordPress"; ?></td>
                        <td><?php echo esc_html($this->audit->resultats->wp_version); ?></td>
                        <td><?php echo esc_html($this->audit->resultats->wp_latest_version); ?></td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>