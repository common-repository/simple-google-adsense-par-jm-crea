<?php
/**
 * Plugin Name: Simple Google Adsense par JM Créa
 * Plugin URI: http://www.jm-crea.com
 * Description: Intégrez vos bandeaux publicitaires Google adsense très facilement
 * Version: 1.4
 * Author: JM Créa
 * Author URI: http://www.jm-crea.com
 */
#################################### INSTALLATION DU PLUGIN

//On créé le menu
function menu_sga() {
add_submenu_page( 'tools.php', 'Simple Google Adsense', 'Simple Google Adsense', 'manage_options', 'sga', 'sga' ); 
}
add_action('admin_menu', 'menu_sga');



//On créé la table mysql
function creer_table_sga() {
global $wpdb;
$table_sga = $wpdb->prefix . 'sga';
$sql = "CREATE TABLE $table_sga (
id_sga int(11) NOT NULL AUTO_INCREMENT,
sga1 text DEFAULT NULL,
sga2 text DEFAULT NULL,
sga3 text DEFAULT NULL,
sga4 text DEFAULT NULL,
sga_actif text DEFAULT NULL,
UNIQUE KEY id (id_sga)
);";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
}



//On insere les infos dans la table
function insert_table_sga() {
global $wpdb;
$table_sga = $wpdb->prefix . 'sga';
$wpdb->insert( 
$table_sga, 
array('sga1'=>' ','sga2'=>' ','sga3'=>' ','sga4'=>' ','sga_actif'=>'ON'), 
array('%s','%s','%s','%s','%s')
);
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
}

register_activation_hook( __FILE__, 'creer_table_sga' );
register_activation_hook( __FILE__, 'insert_table_sga' );



####### MISE A JOUR DU BADGE
function sga() {
global $wpdb;

$table_sga = $wpdb->prefix . "sga";
$voir_sga = $wpdb->get_row("SELECT * FROM $table_sga WHERE id_sga='1'");


require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

echo "<h1>Simple Google Adsense par JM Créa</h1>
<h2>Intégrez Google Adsense à vos pages</h2>";

if (isset($_GET['action'])&&($_GET['action'] == 'maj-ok')) {
echo '<div class="updated"><p>Paramètres mis à jour avec succès !</p></div>';		
}

echo "
<form id='form1' name='form1' method='post' action=''>
<table border='0' cellspacing='8' cellpadding='0'>
<tr>
<td colspan='3'><h2>Activer / Désactiver</h2></td>
</tr>
<tr>
<td colspan='3'>Activer ou désactiver Google Adsense sur le site : </td>
</tr>
<tr>
<td colspan='3'>";

if ($voir_sga->sga_actif == 'ON') {
echo "
<input type='radio' name='sga_actif' id='radio' value='ON' checked='checked' /> ON 
<input type='radio' name='sga_actif' id='radio2' value='OFF' /> OFF ";
}
else {
echo "
<input type='radio' name='sga_actif' id='radio' value='ON' /> ON 
<input type='radio' name='sga_actif' id='radio2' value='OFF' checked='checked' /> OFF ";	
}
echo "
</td>
</tr>
<tr>
<td colspan='3'><h2>Intégration</h2></td>
</tr>
<tr>
<td><h3>Shortcodes</h3></td>
<td><h3>Codes Google Adsense</h3></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td><strong>#1</strong> <u>Intégrez le shortcode</u> : <code>[sga1_by_jm_crea]</code><br>sur vos pages pour afficher vos publicités<br> Google Adsense à l'endroit souhaité.</td>
<td><textarea name='sga1' id='sga1' cols='45' rows='5'>" . $voir_sga->sga1 . "</textarea></td>
</tr>
<tr>
<td><strong>#2</strong> <u>Intégrez le shortcode</u> : <code>[sga2_by_jm_crea]</code><br>sur vos pages pour afficher vos publicités<br> Google Adsense à l'endroit souhaité.</td>
<td><textarea name='sga2' id='sga2' cols='45' rows='5'>" . $voir_sga->sga2 . "</textarea></td>
</tr>
<tr>
<td><strong>#3</strong> <u>Intégrez le shortcode</u> : <code>[sga3_by_jm_crea]</code><br>sur vos pages pour afficher vos publicités<br> Google Adsense à l'endroit souhaité.</td>
<td><textarea name='sga3' id='sga3' cols='45' rows='5'>" . $voir_sga->sga3 . "</textarea></td>
</tr>
<tr>
<td><strong>#4</strong> <u>Intégrez le shortcode</u> : <code>[sga4_by_jm_crea]</code><br>sur vos pages pour afficher vos publicités<br> Google Adsense à l'endroit souhaité.</td>
<td><textarea name='sga4' id='sga4' cols='45' rows='5'>" . $voir_sga->sga4 . "</textarea></td>
</tr>
<tr>
<td colspan='3'>&nbsp;</td>
</tr>
<tr>
<td colspan='3' align='right'><input type='submit' name='maj' id='maj' value='Mettre à jour' class='button button-primary' /></td>
</tr>
</table>
</form>";

//On met à jour la table
if (isset($_POST['maj'])) {
$sga1 = stripslashes($_POST['sga1']);
$sga2 = stripslashes($_POST['sga2']);
$sga3 = stripslashes($_POST['sga3']);
$sga4 = stripslashes($_POST['sga4']);
$sga_actif = stripslashes($_POST['sga_actif']);

global $wpdb;
$table_sga = $wpdb->prefix . "sga";
$wpdb->query($wpdb->prepare("UPDATE $table_sga SET sga1='$sga1',sga2='$sga2',sga3='$sga3',sga4='$sga4',sga_actif='$sga_actif' WHERE id_sga='1'",APP_POST_TYPE));
echo '<script>document.location.href="tools.php?page=sga&action=maj-ok"</script>';
}
}
?>
<?php
############ PREPARATION DU SGA1 EN SHORTCODE

function sga1_contenu() {
global $wpdb;
$table_sga = $wpdb->prefix . "sga";

$apercu_sga1 = $wpdb->get_row("SELECT * FROM $table_sga WHERE id_sga='1'");

if ( ($apercu_sga1->sga_actif == 'ON')&&($apercu_sga1->sga1) ) {
$sga1 = "<!-- SIMPLE GOOGLE ADSENSE PAR JM CREA -->" . "\n";
$sga1 .= $apercu_sga1->sga1;
}
return $sga1;
}

############ PREPARATION DU SGA2 EN SHORTCODE

function sga2_contenu() {
global $wpdb;
$table_sga = $wpdb->prefix . "sga";
$apercu_sga2 = $wpdb->get_row("SELECT * FROM $table_sga WHERE id_sga='1'");

if ( ($apercu_sga2->sga_actif == 'ON')&&($apercu_sga2->sga2) ) {
$sga2 = "<!-- SIMPLE GOOGLE ADSENSE PAR JM CREA -->" . "\n";
$sga2 .= $apercu_sga2->sga2;
}
return $sga2;
}

############ PREPARATION DU SGA3 EN SHORTCODE

function sga3_contenu() {
global $wpdb;
$table_sga = $wpdb->prefix . "sga";
$apercu_sga3 = $wpdb->get_row("SELECT * FROM $table_sga WHERE id_sga='1'");

if ( ($apercu_sga3->sga_actif == 'ON')&&($apercu_sga3->sga3) ) {
$sga3 = "<!-- SIMPLE GOOGLE ADSENSE PAR JM CREA -->" . "\n";
$sga3 .= $apercu_sga3->sga3;
}
return $sga3;
}

############ PREPARATION DU SGA4 EN SHORTCODE

function sga4_contenu() {
global $wpdb;
$table_sga = $wpdb->prefix . "sga";
$apercu_sga4 = $wpdb->get_row("SELECT * FROM $table_sga WHERE id_sga='1'");

if ( ($apercu_sga4->sga_actif == 'ON')&&($apercu_sga4->sga4) ) {
$sga4 = "<!-- SIMPLE GOOGLE ADSENSE PAR JM CREA -->" . "\n";
$sga4 .= $apercu_sga4->sga4;
}
return $sga4;
}

add_shortcode('sga1_by_jm_crea','sga1_contenu');
add_shortcode('sga2_by_jm_crea','sga2_contenu');
add_shortcode('sga3_by_jm_crea','sga3_contenu');
add_shortcode('sga4_by_jm_crea','sga4_contenu');


function head_meta_sga_jm_crea() {
echo("<meta name='Simple Google Adsense par JM Créa' content='1.4' />\n");
}
add_action('wp_head', 'head_meta_sga_jm_crea');

?>