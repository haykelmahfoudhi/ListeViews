<?php

//-----------------------------------------------------------------------------------
//		REQUIRE & DATABASE 															|
//-----------------------------------------------------------------------------------

define('LM_ROOT', '../ListManager/');
require_once LM_ROOT.'includes.php';

Database::instantiate('oci:dbname=MPA', 'proginfo', 'qlzM9zr3M');
//-----------------------------------------------------------------------------------

// Requete SQL
$sql = new SQLRequest("SELECT vfcc_societe,
                              vfcc_statut,
                              vfcc_dt_facture,
                              vfcc_four_code,
                              vfcc_four_nom,
                              vfcc_ref_four,
                              vfcc_montant_ht,
                              vfcc_montant_ttc,
                              vfcc_facture,
                              vfcc_dt_saisie,
                              vfcc_pers_init,
                              vfcc_code_compta,
                              vfcc_type,
                              vfcc_echeance,
                              vfcc_reglement,
                              vfcc_commentaire,
                              vfcc_scan
                       FROM MV_FAFO_CODE_COMPTA 
                       ORDER BY vfcc_dt_saisie desc", true);

// Config ListManager
$lm = new ListManager();
$lm ->setFilter(['vfcc_societe' => 'SOC1']);
$lm ->setFilter(['vfcc_facture' => 'F'.date('y').'-%']);

// Affichage de la liste
$html = $lm->construct($sql);

?>

<!DOCTYPE html>
<html>
<head>
   	<title>Liste factures fournisseur</title>
	<link href='../jslib/favicon.ico' rel='shortcut icon' type='image/vnd.microsoft.icon' />
   	
	<script type='text/javascript'>
		function Popup(url) {
		        options = 'toolbar=no, location=no, menubar=no, resizable=yes, scrollbars=yes, alwaysRaised=yes width=900';
		        pw = window.open (url,'',options);
		        return pw;
		}
	</SCRIPT>
</head>

<body link=black vlink=black onload='parent.init(void)'>

	<?=$html;?>
</body>
</html>
