<?php
defined('ABSPATH') || exit;
?>
<div class="hpixl-securicheck-accordion">
    <!-- Accordéon Sécurité Technique -->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-11" checked>
        <label for="tab-11">
            <span><?php if ($this->audit->resultats->nb_erreurs_securite_technique > 0) { ?><img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/error_icon.svg" /><?php } ?><?php _e('Sécurité Technique', 'securicheck'); ?><?php if ($this->audit->resultats->nb_erreurs_securite_technique > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo esc_html($this->audit->resultats->nb_erreurs_securite_technique); ?></span><?php } ?></span>
        </label>
        <div class="content">
            <p><?php _e('Vous trouverez ci dessous les informations concernant la sécurité de votre site', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table">
                <tbody>
                    <tr <?php echo $this->audit->resultats->isHttps ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Site en Https ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isHttps ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>";  ?></td>
                        <td><?php echo $this->audit->resultats->isHttps ? "" : __('Installez un certificat pour votre nom de domaine en passant par votre hébergeur.', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->prefixBdd === "wp_" ? "class='erreur'" : ""; ?>>
                        <td><?php _e('Préfixe des tables en base de données', 'securicheck'); ?></td>
                        <td><span class='<?php echo $this->audit->resultats->prefixBdd === "wp_" ? "badge_non" : "badge_oui"; ?>'><?php echo esc_html($this->audit->resultats->prefixBdd); ?></span></td>
                        <!--td><?php /* echo $this->audit->resultats->prefixBdd === "wp_" ? "Changez le prefixe des tables de la base de données de votre site en suivant <a href='https://wp-securicheck.com/comment-changer-le-prefixe-des-tables-de-la-base-de-donnees-de-votre-site-wordpress/' target='_blank'>ce tutoriel</a>" : ""; */ ?></td-->
                        <td><?php echo $this->audit->resultats->prefixBdd === "wp_" ? __('Changez le prefixe des tables de la base de données de votre site, nous écrivons actuellement un tutoriel pour vous aider', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->id_admin_principal == 1 ? "class='erreur'" : ""; ?>>
                        <td><?php _e('Id de l\'administrateur principal', 'securicheck'); ?></td>
                        <td><span class='<?php echo $this->audit->resultats->id_admin_principal == 1 ? "badge_non" : "badge_oui"; ?>'><?php echo esc_html($this->audit->resultats->id_admin_principal); ?></span></td>
                        <!--td><?php /*echo $this->audit->resultats->id_admin_principal == 1 ? "Changez l'id par défaut de l'administrateur principal en exécutant des requêtes en base de données. Vous pouvez suivre notre <a href='https://wp-securicheck.com/comment-changer-id-administrateur-principal-de-votre-site-wordpress/' target='_blank'>tutoriel</a>" : "";*/ ?></td-->
                        <td><?php echo $this->audit->resultats->id_admin_principal == 1 ? __('Changez l\'id par défaut de l\'administrateur principal en exécutant des requêtes en base de données, nous écrivons actuellement un tutoriel pour vous aider', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->compteAdminExiste ?  "class='erreur'" : ""; ?>>
                        <td><?php _e('Compte "admin" existant ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->compteAdminExiste ? "<span class='badge_non'>oui</span>" : "<span class='badge_oui'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->compteAdminExiste ? __('Le compte par défaut "admin" est connu des pirates, créez un nouveau compte administrateur et supprimez celui ci.', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->nbAdmin > 1 ?  "class='erreur'" : "";  ?>>
                        <td><?php _e('Nombre de Comptes Administrateurs', 'securicheck'); ?></td>
                        <td><span class='<?php echo $this->audit->resultats->nbAdmin > 1 ? "badge_non" : "badge_oui"; ?>'><?php echo esc_html($this->audit->resultats->nbAdmin); ?></span></td>
                        <td><?php echo $this->audit->resultats->nbAdmin > 1 ? __('Définissez le bon rôle pour chacun des comptes utilisateur de votre site. En limitant le nomnbre de comptes administrateur, vous limitez les risques.', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->boUrl ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Url du Backoffice cachée ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->boUrl ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->boUrl ? "" : __('L\'url par défaut "/wp-admin" est connue par tous. Changez pour compliquer la tâche des attaquants en utilisant les <a href="/wp-admin/admin.php?page=securicheck-reglages&tab=url-connexion">réglages</a> de notre plugin Securicheck.', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isWordpressVersionHidden ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Version de WordPress cachée ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isWordpressVersionHidden ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isWordpressVersionHidden ? "" : __('Ajoutez cette ligne de code dans le fichier functions.php de votre thème: <i></br> remove_action("wp_head","wp_generator");</i>', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isXmlRpcDisabled ? "" : "class='erreur'"; ?>>
                        <td><?php _e('XmlRpc désactivé ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isXmlRpcDisabled ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isXmlRpcDisabled ? "" : __('Ajoutez ces lignes de code dans le fichier .htaccess à la racine de votre site : <i></br>', 'securicheck') . esc_html("<Files xmlrpc.php>") . "</br>order deny,allow</br>deny from all</br>" . esc_html("</Files>") . "</i>"; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isLiveWriterProtected ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Live Writer désactivé ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isLiveWriterProtected ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isLiveWriterProtected ? "" : __('Ajoutez cette ligne de code dans votre fichier functions.php : <i></br> remove_action("wp_head", "wlwmanifest_link");</i>', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isApiRestDisabled == 0 ?  "class='erreur'" : ""; ?>>
                        <td><?php _e('API Rest désactivée ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isApiRestDisabled == 0 ? "<span class='badge_non'>non</span>" : ($this->audit->resultats->isApiRestDisabled == 1 ? "<span class='badge_oui'>oui</span>" : "<span class='badge_partiel'>partiellement</span>"); ?></td>
                        <!--td><?php /*echo $this->audit->resultats->isApiRestDisabled == 0 ? "Votre site expose beaucoup d'informations via l'api REST mise à disposition. Si vous ne l'utilisez pas, désactivez la en suivant notre <a href='https://wp-securicheck.com/comment-desactiver-lapi-rest-de-votre-site-wordpress/' target='_blank'>tutoriel</a>" : ($this->audit->resultats->isApiRestDisabled == 1 ? "" : "L'api est bien désactivée uniquement pour les fonctionnalités qui n'ont pas à être visibles par tous et reste accessible pour celles qui vous sont utiles.");*/ ?></td-->
                        <td><?php echo $this->audit->resultats->isApiRestDisabled == 0 ? __('Votre site expose beaucoup d\'informations via l\'api REST mise à disposition. Si vous ne l\'utilisez pas, désactivez la. Nous allons bientôt vous proposer un tutoriel pour le faire.', 'securicheck') : ($this->audit->resultats->isApiRestDisabled == 1 ? "" : __('L\'api est bien désactivée uniquement pour les fonctionnalités qui n\'ont pas à être visibles par tous et reste accessible pour celles qui vous sont utiles.', 'securicheck')); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isEditeurFichierDisabled ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Editeur de fichier désactivé ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isEditeurFichierDisabled ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isEditeurFichierDisabled ? "" : __('Ajoutez cette ligne de code dans votre fichier wp-config.php : <i></br>define("DISALLOW_FILE_EDIT", true);</i>', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isDirNavigationProtected ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Navigation dans les dossiers désactivée ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isDirNavigationProtected ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isDirNavigationProtected ? "" : __('Ajoutez cette ligne de code dans le fichier .htaccess à la racine de votre site : <i></br> Options -Indexes</i>', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isUploadsProtected ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Répertoire Uploads Protégé ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isUploadsProtected ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isUploadsProtected ? "" : __('Ajoutez ces lignes de code dans un fichier .htaccess situé dans le repertoire "wp-content/uploads" : <i></br> ', 'securicheck') . esc_html("<FilesMatch \"\\.(php|phtml|php3|php4|php5|pl|py|jsp|asp|html| htm|shtml|sh|cgi)$\">") . "</br>deny from all</br>" . esc_html("</FilesMatch>") . "</i>"; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isInfoServerHidden ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Informations Relatives au Serveur cachées ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isInfoServerHidden ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isInfoServerHidden ? "" : __('Ajoutez ce code dans le fichier .htaccess à la racine de votre site : <i></br>ServerSignature Off</i>', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isWpConfigProtected ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Fichier wp-config.php Protégé ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isWpConfigProtected ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isWpConfigProtected ? "" : __('Ajouter ces lignes de code dans le fichier htaccess à la racine de votre site : <i></br>', 'securicheck') . esc_html("<files wp-config.php>") . "</br> order allow,deny </br>	deny from all </br>" . esc_html("</files>") . "</i>"; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isHtAccessProtected ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Fichier .htAccess Protégé ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isHtAccessProtected ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isHtAccessProtected ? "" : __('Ajouter ces lignes de code dans le fichier htaccess à la racine de votre site : <i></br>', 'securicheck') . esc_html("<files .htaccess>") . "</br> order allow,deny </br>	deny from all </br>" . esc_html("</files>") . "</i>"; ?></td>
                    </tr>
                    <tr <?php echo  $this->audit->resultats->is_chatgpt_accessible ?  "class='erreur'" : ""; ?>>
                        <td><?php _e('Site accessible par ChatGPT ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->is_chatgpt_accessible ? "<span class='badge_non'>oui</span>" : "<span class='badge_oui'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->is_chatgpt_accessible ? __('Ajoutez ces lignes de code dans le fichier .htaccess à la racine de votre site : <i></br>RewriteEngine On </br> RewriteCond %{HTTP_USER_AGENT} (anthropic-ai|Bytespider|CCBot|ChatGPT-User|FacebookBot|GPTBot|Omgilibot) [NC] </br> RewriteRule ^ – [F] </i>', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->is_hotlinking_disabled === "oui" ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Protection Hotlinking ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->is_hotlinking_disabled === HPIXL_SECURICHECK_HOTLINKING_IMAGE_NON_PRESENTE || $this->audit->resultats->is_hotlinking_disabled === HPIXL_SECURICHECK_HOTLINKING_PARAMETRE_NON_DEFINI  ? "<span class='badge_partiel'>non</span>" : ($this->audit->resultats->is_hotlinking_disabled === "oui" ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"); ?></td>
                        <td><?php echo $this->audit->resultats->is_hotlinking_disabled === HPIXL_SECURICHECK_HOTLINKING_IMAGE_NON_PRESENTE || $this->audit->resultats->is_hotlinking_disabled === HPIXL_SECURICHECK_HOTLINKING_PARAMETRE_NON_DEFINI  ? esc_html($this->audit->resultats->is_hotlinking_disabled) : ($this->audit->resultats->is_hotlinking_disabled === "oui" ? "" : __('Ajoutez ces lignes de code dans le fichier .htaccess à la racine de votre site : <i></br>RewriteEngine on </br> RewriteCond %{HTTP_REFERER} !^$ </br> RewriteCond %{HTTP_REFERER} !^', 'securicheck') . esc_url(site_url()) . " [NC] </br> RewriteRule \.(jpg|jpeg|png|gif)$ - [R=404,L]</i>"); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isModeDebugOn ? "class='erreur'" : ""; ?>>
                        <td><?php _e('Mode debug activé ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isModeDebugOn ? "<span class='badge_non'>oui</span>" : "<span class='badge_oui'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isModeDebugOn ? __('Modifier la valeur de la constante "WP_DEBUG" à "false" dans le fichier wp-config.php à la racine de votre site.', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->is_application_password_active ? "class='erreur'" : ""; ?>>
                        <td><?php _e('Application passwords activé ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->is_application_password_active ? "<span class='badge_non'>oui</span>" : "<span class='badge_oui'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->is_application_password_active ? __('Ajoutez cette ligne de code dans le fichier functions.php de votre thème : <i></br> add_filter("wp_is_application_passwords_available", "__return_false"); </i>', 'securicheck') : ""; ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isArchiveAuthorDisabled ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Page archive auteur désactivée ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isArchiveAuthorDisabled ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isArchiveAuthorDisabled ? "" : __('Ajoutez cette ligne de code dans le fichier functions.php de votre thème : <i></br> add_action("template_redirect", function() { </br>&nbsp;&nbsp;if (is_author()) { </br>&nbsp;&nbsp;&nbsp;&nbsp;global $wp_query; </br>&nbsp;&nbsp;&nbsp;&nbsp;$wp_query->set_404(); </br>&nbsp;&nbsp;&nbsp;&nbsp;status_header(404);</br>&nbsp;&nbsp;&nbsp;&nbsp;exit; </br>&nbsp;&nbsp;}</br>}); </i>', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isScanAuthorDisabled ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Scan des ids des auteurs désactivé ?', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isScanAuthorDisabled ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isScanAuthorDisabled ? "" : __('Ajoutez ces lignes de code dans le fichier .htaccess à la racine de votre site : <i> </br> RewriteEngine On </br> RewriteCond %{QUERY_STRING} ^author=([0-9]*)$ </br> RewriteRule ^ /404 [L,R=301] </br> </i>', 'securicheck'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Accordion En Têtes HTTP -->
    <div class="element">
        <input type="checkbox" name="tab" id="tab-12">
        <label for="tab-12">
            <span><?php if ($this->audit->resultats->nb_erreurs_securite_enTetesHTTP > 0) { ?><img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/error_icon.svg" /><?php } ?><?php _e('En Têtes HTTP', 'securicheck'); ?><?php if ($this->audit->resultats->nb_erreurs_securite_enTetesHTTP > 0) { ?><span class="hpixl-securicheck-audit-badge-alerte"><?php echo esc_html($this->audit->resultats->nb_erreurs_securite_enTetesHTTP); ?></span><?php } ?></span>
        </label>
        <div class="content">
            <p><?php _e('Vous trouverez ci dessous les informations concernant les en-têtes sécurité de votre site', 'securicheck'); ?></p>
            <table class="hpixl-securicheck-audit-table">
                <tbody>
                    <tr <?php echo $this->audit->resultats->isCspOk ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Content-Security-Policy', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isCspOk ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isCspOk ? "" : __('Aucune en-tête n\'est configurée pour Content-Security-Policy', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isHstsOk ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Strict Transport Security (HSTS)', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isHstsOk ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isHstsOk ? "" : __('Aucune en-tête n\'est configurée pour Strict Transport Security', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isRefererPolicyOk ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Referrer Policy', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isRefererPolicyOk ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isRefererPolicyOk ? "" : __('Aucune en-tête n\'est configurée pour les Referrer Policy', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isAccessControlAllowOriginOk ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Access-Control-Allow-Origin', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isAccessControlAllowOriginOk ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isAccessControlAllowOriginOk ? "" : __('Aucune en-tête n\'est configurée pour les Access-Control-Allow-Origin', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isXFrameOptionsOk ? "" : "class='erreur'"; ?>>
                        <td><?php _e('X-Frame-Options', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isXFrameOptionsOk ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isXFrameOptionsOk ? "" : __('Aucune en-tête n\'est configurée pour les X-Frame-Options', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isXXssProtectionOk ? "" : "class='erreur'"; ?>>
                        <td><?php _e('XSS protection', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isXXssProtectionOk ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isXXssProtectionOk ? "" : __('Aucune en-tête n\'est configurée pour les XSS protection', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isXContentTypeOptionsOk ? "" : "class='erreur'"; ?>>
                        <td><?php _e('X-Content-Type-Options', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isXContentTypeOptionsOk ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isXContentTypeOptionsOk ? "" : __('Aucune en-tête n\'est configurée pour les X-Content-Type-Options', 'securicheck'); ?></td>
                    </tr>
                    <tr <?php echo $this->audit->resultats->isPermissionsPolicyOk ? "" : "class='erreur'"; ?>>
                        <td><?php _e('Permissions-Policy', 'securicheck'); ?></td>
                        <td><?php echo $this->audit->resultats->isPermissionsPolicyOk ? "<span class='badge_oui'>oui</span>" : "<span class='badge_non'>non</span>"; ?></td>
                        <td><?php echo $this->audit->resultats->isPermissionsPolicyOk ? "" : __('Aucune en-tête n\'est configurée pour les Permissions-Policy', 'securicheck'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>