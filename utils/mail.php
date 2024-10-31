<?php

if (!defined('ABSPATH')) {
    exit; // Pas d'accès direct !
}
?>

<div id="v1mainwp-email-wrapper" style="padding: 30px 0">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="margin-top: 30px; margin-bottom: 30px">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0,0,0,0.1); border-radius: 3px; padding-bottom: 30px">

                        <tbody>
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="600">
                                        <tbody>
                                            <tr>
                                                <td id="v1header_wrapper" style="padding: 20px 48px; padding-left:18px;display: flex; flex-direction:row; align-items:center;justify-content:center; background: #090e16">
                                                    <a href target="_blank"><img src="<?php echo esc_url(HPIXL_SECURICHECK_PLUGIN_URL); ?>images/securicheck.png" alt="logo hpixl-securicheck" style="width:80px" />
                                                    </a>
                                                    <h1 style="text-align: center; color: #fff;margin:0;padding-left:20px;">
                                                        <?php _e('Audit Securicheck', 'securicheck'); ?></h1>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>


                            <tr>
                                <td align="left" valign="top" style="padding: 30px 30px 0 30px">
                                    <p><strong><?php _e('Bonjour', 'securicheck'); ?> <?php echo esc_html(wp_get_current_user()->user_firstname); ?>,
                                        </strong></p>
                                    <p><?php _e('Veuillez prendre quelques minutes pour consulter les résultats du dernier audit de votre site.', 'securicheck'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="top" style="padding: 30px 30px 0 30px">
                                    <h3 style="color: #04AA6D"><?php _e('Résultats', 'securicheck'); ?></h3>
                                    <p><?php _e('Au total il y a', 'securicheck'); ?>
                                        <span style="background: #090e16; padding: 3px 7px; color: #fff"><?php echo esc_html($tableauNbErreurs['nb_total_erreurs']); ?></span>&nbsp;<?php _e('erreurs détéctées pour un score de', 'securicheck'); ?>
                                        <span style="background: #090e16; padding: 3px 7px; color: #fff"><?php echo esc_html($score); ?></span>&nbsp;/100.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="top" style="padding: 30px 30px 0 30px">
                                    <h3 style="color: #04AA6D"><?php _e('Santé du site', 'securicheck'); ?></h3>
                                    <p><?php _e('Les informations concernant la santé de votre site.', 'securicheck'); ?></p>
                                    <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%" style="font-size: 11px; margin-bottom: 30px">
                                        <thead style="background: #eee">
                                            <tr>
                                                <th style="padding: 5px" align="left"><?php _e('Critère', 'securicheck'); ?></th>
                                                <th style="padding: 5px;text-align:center;"><?php _e('Problèmes', 'securicheck'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('Etat Technique', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nb_erreurs_etat_technique']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('Etat Fonctionnel', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nb_erreurs_etat_fonctionnel']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('Performances', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nb_erreurs_etat_performances']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <h3 style="color: #04AA6D">Sécurité</h3>
                                    <p><?php _e('Les informations concernant la sécurité de votre site.', 'securicheck'); ?></p>
                                    <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%" style="font-size: 11px; margin-bottom: 30px">
                                        <thead style="background: #eee">
                                            <tr>
                                                <th style="padding: 5px" align="left"><?php _e('Critère', 'securicheck'); ?></th>
                                                <th style="padding: 5px;text-align:center;"><?php _e('Problèmes', 'securicheck'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('Sécurité Technique', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nb_erreurs_securite_technique']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('En têtes HTTP', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nb_erreurs_securite_enTetesHTTP']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <h3 style="color: #04AA6D"><?php _e('Mises à Jour', 'securicheck'); ?></h3>
                                    <p><?php _e('L\'état des mises à jour de votre site internet (WordPress, Thèmes, Plugins).', 'securicheck'); ?></p>
                                    <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%" style="font-size: 11px; margin-bottom: 30px">
                                        <thead style="background: #eee">
                                            <tr>
                                                <th style="padding: 5px" align="left"><?php _e('Critère', 'securicheck'); ?></th>
                                                <th style="padding: 5px;text-align:center;"><?php _e('Problèmes', 'securicheck'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('Plugins', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nb_maj_plugins']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('Thèmes', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nb_maj_themes']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        WordPress
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nbMajWordpress']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <h3 style="color: #04AA6D"><?php _e('Connexions', 'securicheck'); ?></h3>
                                    <p>
                                        <?php _e('Le nombre de connexions totales, administrateurs et échouées depuis les 30', 'securicheck'); ?>
                                        <?php _e('derniers jours.', 'securicheck'); ?>
                                    </p>
                                    <table border="0" cellpadding="0" cellspacing="0" align="left" width="100%" style="font-size: 11px; margin-bottom: 30px">
                                        <thead style="background: #eee">
                                            <tr>
                                                <th style="padding: 5px" align="left"><?php _e('Critère', 'securicheck'); ?></th>
                                                <th style="padding: 5px;text-align:center;"><?php _e('Nombre', 'securicheck'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('Connexions au total', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nbConnexionsOk']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('Connexions administrateurs', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nbConnexionsOkAdmin']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee">
                                                    <p style="color: #2c363a;margin:0;">
                                                        <?php _e('Connexions échouées', 'securicheck'); ?>
                                                    </p>
                                                </td>
                                                <td style="padding: 10px; border-bottom: 1px solid #eee" align="center">
                                                    <span style="background: #090e16; padding: 3px 7px; color: #fff">
                                                        <?php echo esc_html($tableauNbErreurs['nbFailedConnexions']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p><?php _e('Si vous souhaitez améliorer la sécurité de votre site internet, optez pour la', 'securicheck'); ?>
                                        <b><a href="https://wp-securicheck.com/tarifs" style="color:#04AA6D" target="_blank" rel="noreferrer"><?php _e('version premium du plugin', 'securicheck'); ?></a></b><?php _e(' et corrigez ses failles de sécurité en 1 clic.', 'securicheck'); ?>
                                    </p>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <div style="text-align: center; font-size: 11px; margin-bottom: 30px">
        Propulsé par <a href="https://wp-securicheck.com/" style="color: #2c363a" target="_blank" rel="noreferrer">WP-securicheck.com</a>.
    </div>
</div>