<?php

//-----------------------------------------------------------------------------------
//              REQUIRE & DATABASE                                                                                                                      |
//-----------------------------------------------------------------------------------

define('LM_ROOT', '../ListManager/');
require_once LM_ROOT.'includes.php';
require_once('Connecteur/base.php');

Database::instantiate(LMBASE, LMUSER, LMPASS);
//-----------------------------------------------------------------------------------

$sql = new SQLRequest("SELECT
													   MVI_GAM_CLIENT AS CLIENT,
													   MVI_GAM_NOM AS NOM,
													   MVI_GAM_ARTICLE AS ARTICLE,
													   MVI_GAM_DONNORDRE AS DONNORDRE,
													   MVI_GAM_DESIGN AS DESIGN,
													   MVI_GAM_GAMME AS GAMME,
													   MVI_GAM_Cycle_10 AS Cycle_10,
													   MVI_GAM_Cycle_100 AS Cycle_100,
													   MVI_GAM_Cycle_500 AS Cycle_500,
													   MVI_GAM_T01_10 AS T01_10,
													   MVI_GAM_T11_100 AS T11_100,
													   MVI_GAM_T101_500 AS T101_500,
													   MVI_GAM_T501 AS GAM_T501,
													   MVI_GAM_dt_last_fact AS dt_last_fact,
													   MVI_GAM_deroule AS deroule,
													   MVI_GAM_largeur AS largeur,
													   MVI_GAM_longueur AS longueur,
													   MVI_GAM_hauteur AS hauteur,
													   MVI_GAM_devis AS devis,
													   MVI_GAM_dt_saisie AS dt_saisie,
													   MVI_GAM_dt_reponse AS dt_reponse,
													   MVI_GAM_ref_client AS ref_client
													   FROM MVI_GAMME_PRIX", true);

// ListManager
$lm = new ListManager();

// Callback
$lm->setCellCallback(function($contenu, $titre, $numLigne, $data, $numCol){
    //return "<a href='javascript:void(Popup(\"http://wwwsite.mpa.tn/bippage/public/atelier2.php?GROFurl=".$data[0]."\"));'> ".$contenu." </a>";
	return "<a href='javascript:void(Popup(\"http://wwwsite.mpa.tn/octalprix/prix_phase.php?GO=GO&Quest=1&orderby=-1,2,3&Mask=&lens[]=10&names[]=clnt_code&types[]=VARCHAR&tabselect[]=".$data[0]."&lens[]=16&names[]=arti_code&types[]=VARCHAR&tabselect[]=".$data[2]."\"));'> ".$contenu." </a>";
});

$lm->setFilter(['MVI_GAM_CLIENT' => '0059']);
   // ->setOrderBy(['grof_numero']);

$html = $lm->construct($sql);

?>

<!DOCTYPE html>
<html>
<head>
        <title>Liste Gamme Prix</title>
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
