<?php
defined('ABSPATH') || exit;
?>
<!-- accordéon -->
<div class="hpixl-securicheck-accordion">
    <!-- Accordéon Etat Technique -->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-1" checked>
        <label for="tab-1">
            <span><img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/info_icon.svg" /><?php _e('Etat Technique', 'securicheck'); ?><?php if ($this->audit->resultats->nb_erreurs_etat_technique > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo  esc_html($this->audit->resultats->nb_erreurs_etat_technique); ?></span><?php } ?></span>
        </label>
        <div class="content">
            <p><?php _e('Vous trouverez ci dessous les informations techniques de votre installation', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table">
                <tbody>
                    <tr <?php echo version_compare($this->audit->resultats->phpVersion, HPIXL_SECURICHECK_PHP_MINI) < 0 ?  "class='erreur'"  : "" ?>>
                        <td><?php _e('Version PHP', 'securicheck'); ?></td>
                        <td><span class='<?php echo version_compare($this->audit->resultats->phpVersion, HPIXL_SECURICHECK_PHP_MINI) < 0 ? "badge_non" : "badge_oui"; ?>'><?php echo esc_html($this->audit->resultats->phpVersion); ?></span></td>
                        <td><?php echo version_compare($this->audit->resultats->phpVersion, HPIXL_SECURICHECK_PHP_MINI) < 0 ? __('(version minimum de php souhaitée : ', 'securicheck') . esc_html(HPIXL_SECURICHECK_PHP_MINI) . ")." : ""; ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Base de données', 'securicheck'); ?></td>
                        <td><?php echo esc_html($this->audit->resultats->bddType); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><?php _e('Version de', 'securicheck'); ?><?php echo esc_html($this->audit->resultats->bddType); ?></td>
                        <td><?php echo esc_html($this->audit->resultats->bddVersion); ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><?php _e('Espace disque utilisé', 'securicheck'); ?></td>
                        <td><?php echo esc_html($this->audit->resultats->diskSpaceUsed); ?></td>
                        <td></td>
                    </tr>
                    <tr <?php echo version_compare($this->audit->resultats->dbDiskUsed, HPIXL_SECURICHECK_BDD_MAXI, '>') ?  "class='erreur'"  : "" ?>>
                        <td><?php _e('Espace disque bdd utilisé', 'securicheck'); ?></td>
                        <td><span class='<?php echo version_compare($this->audit->resultats->dbDiskUsed, HPIXL_SECURICHECK_BDD_MAXI, '>') ? "badge_non" : "badge_oui"; ?>'><?php echo esc_html($this->audit->resultats->dbDiskUsed); ?></span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Accordéon Etat Fonctionnel -->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-2">
        <label for="tab-2">
            <span><?php if ($this->audit->resultats->nb_erreurs_etat_fonctionnel > 0) { ?><img src=" <?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/error_icon.svg" /><?php } ?><?php _e('Etat Fonctionnel', 'securicheck'); ?><?php if ($this->audit->resultats->nb_erreurs_etat_fonctionnel > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo esc_html($this->audit->resultats->nb_erreurs_etat_fonctionnel); ?></span><?php } ?></span>
        </label>
        <div class="content">
            <p><?php _e('Vous trouverez ci dessous les informations fonctionnelles de votre installation', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table">
                <tbody>
                    <tr <?php echo $this->audit->resultats->nb_maj_plugins > 0 ?  "class='erreur'"  : "" ?>>
                        <td><?php _e('Nombre de maj de plugin à faire', 'securicheck'); ?></td>
                        <td><span class='<?php echo $this->audit->resultats->nb_maj_plugins > 0 ? "badge_non" : "badge_oui"; ?>'><?php echo esc_html($this->audit->resultats->nb_maj_plugins); ?></span></td>
                        <td><?php echo $this->audit->resultats->nb_maj_plugins > 0 ? __('Assurez vous que tous vos plugins soient à jour.', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->nb_maj_themes > 0 ? "class='erreur'"  : "" ?>>
                        <td><?php _e('Nombre de maj de thèmes à faire', 'securicheck'); ?></td>
                        <td><span class='<?php echo $this->audit->resultats->nb_maj_themes > 0 ? "badge_non" : "badge_oui"; ?>'><?php echo esc_html($this->audit->resultats->nb_maj_themes); ?></span></td>
                        <td><?php echo $this->audit->resultats->nb_maj_themes > 0 ? __('Assurez vous que tous vos thèmes soient à jour.', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->maj_wordpress === "oui" ? "class='erreur'"  : "" ?>>
                        <td><?php _e('Maj de WordPress à faire', 'securicheck'); ?></td>
                        <td><span class='<?php echo $this->audit->resultats->maj_wordpress === "oui" ? "badge_non" : "badge_oui"; ?>'><?php echo esc_html($this->audit->resultats->maj_wordpress); ?></span></td>
                        <td><?php echo $this->audit->resultats->maj_wordpress === "oui" ? __('Assurez vous que la version de WordPress soit à jour.', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Nom du thème actuel', 'securicheck'); ?></td>
                        <td><span class='badge_oui'><?php echo esc_html($this->audit->resultats->theme_name); ?></span></td>
                        <td></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->has_theme_enfant == 0 ? "" : "class='erreur'" ?>>
                        <td><?php _e('Est ce un thème enfant ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->has_theme_enfant == 0 ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->has_theme_enfant == 0 ? "" : __('Il est vivement conseillé de faire un thème enfant de votre thème principal.', 'securicheck') ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->theme_parent ? "" : "class='erreur'" ?>>
                        <td><?php _e('Nom du thème parent', 'securicheck'); ?></td>
                        <td><span class='<?php echo $this->audit->resultats->theme_parent ? "badge_oui" : "badge_non"; ?>'><?php echo esc_html($this->audit->resultats->has_theme_enfant == 0 ? $this->audit->resultats->theme_parent : "N/A"); ?></span></td>
                        <td></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->nb_old_plugins > 0 ?  "class='erreur'" : "" ?>>
                        <td><?php _e('Nombre de plugins désactivés/obsolètes', 'securicheck'); ?></td>
                        <td><span class='<?php echo $this->audit->resultats->nb_old_plugins > 0 ? "badge_non" : "badge_oui"; ?>'> <?php echo esc_html($this->audit->resultats->nb_old_plugins); ?></span></td>
                        <td><?php echo $this->audit->resultats->nb_old_plugins > 0 ? __('Assurez vous de supprimer les plugins désactivés qui ne servent à rien pour votre site et qui sont source de failles de sécurité.', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->nb_old_themes > 2 ?  "class='erreur'" : ""  ?>>
                        <td><?php _e('Nombre de thèmes désactivés/obsolètes', 'securicheck'); ?></td>
                        <td><span class='<?php echo $this->audit->resultats->nb_old_themes > 2 ? "badge_non" : "badge_oui"; ?>'><?php echo esc_html($this->audit->resultats->nb_old_themes); ?></span></td>
                        <td><?php echo $this->audit->resultats->nb_old_themes > 2 ?  __('Assurez vous de ne garder qu\'un thème de secours et de supprimer tous les autres.', 'securicheck') : ""; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Accordion Performances -->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-3">
        <label for="tab-3" class="tab-3">
            <span><?php if ($this->audit->resultats->nb_erreurs_etat_performances > 0) { ?><img src=" <?php echo  esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/warning_icon.svg" /><?php } ?><?php _e('Performances', 'securicheck'); ?><?php if ($this->audit->resultats->nb_erreurs_etat_performances > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo  esc_html($this->audit->resultats->nb_erreurs_etat_performances); ?></span><?php } ?></span>
        </label>
        <div class="content">
            <p><?php _e('Vous trouverez ci dessous les informations de performances de votre installation', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table">
                <tbody>
                    <tr <?php echo $this->audit->resultats->nbMaxiRevisions > 10 || $this->audit->resultats->nbMaxiRevisions == -1 ? "class='erreur'" : ""  ?>>
                        <td><?php _e('Nombre de révisions limité à ', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->nbMaxiRevisions == -1 ? "<span class='badge_non'>Illimité</span>" : ($this->audit->resultats->nbMaxiRevisions > 10 ? "<span class='badge_partiel'>" . esc_html($this->audit->resultats->nbMaxiRevisions) . "</span>" : "<span class='badge_oui'>" . esc_html($this->audit->resultats->nbMaxiRevisions) . "</span>"); ?></td>
                        <td> <?php echo $this->audit->resultats->nbMaxiRevisions == -1 ? __('Un nombre illimité de révisions peut entrainer un alourdissement de la base de données et un ralentissement des performances.', 'securicheck') : ""; ?><?php echo $this->audit->resultats->nbMaxiRevisions > 10 ? __('il est conseillé de définir une valeur inférieure ou égale à 10', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isCacheActif ? "" : "class='erreur'"   ?>>
                        <td><?php _e('Système de cache ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isCacheActif ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>";  ?></td>
                        <td><?php echo $this->audit->resultats->isCacheActif ? "" : __('Un système de cache permet d\'afficher votre site beaucoup plus rapidement, sans solliciter le serveur. Installez un plugin dédié comme wp rocket ou wp super cache.', 'securicheck') ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>