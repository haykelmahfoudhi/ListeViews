<?php

//-----------------------------------------------------------------------------------
//              REQUIRE & DATABASE                                                                                                                      |
//-----------------------------------------------------------------------------------

define('LM_ROOT', '../ListManager/');
require_once LM_ROOT.'includes.php';
require_once('../Connecteur/base.php');

Database::instantiate(LMBASE, LMUSER, LMPASS);
//-----------------------------------------------------------------------------------

$sql = new SQLRequest("SELECT
							MVI_LDCL_DVCL_NUMERO ,
							MVI_LDCL_LIGNE,
							MVI_LDCL_CLNT_CODE,
							MVI_LDCL_ARTI_CODE,
							MVI_LDCL_DESIGNATION,
							MVI_LDCL_SUR_OF,
							MVI_LDCL_COEF_UV_UE,
							MVI_LDCL_QTE_COMMANDEE_UV,
							MVI_LDCL_PRIX_UNITAIRE_UV,
							MVI_DT_LIVRAISON_DEMANDEE,
							MVI_DT_LIVRAISON_CONFIRMEE,
							MVI_LDCL_STATUT,
							MVI_LDCL_NB_COMMANDE,
							MVI_LDCL_CENT_CODE,
							MVI_LDCL_EMPL_CODE,
							MVI_LDCL_DT_PREPARATION,
							MVI_LDCL_DT_EXPEDITION,
							MVI_DT_CRE,
							MVI_LOGIN_CRE,
							MVI_LDCL_GAMM_NUMERO,
							MVI_LDCL_DELAI_TRF,
							MVI_LDCL_DELAI_TRF_CENT_CODE,
							MVI_LDCL_PRIX_UNITAIRE_UP,
							MVI_LDCL_PRIX_UNITAIRE_UP_MAJ,
							MVI_DEROULE
						FROM MVI_DEVIS_DETAIL
							ORDER BY MVI_LDCL_DVCL_NUMERO DESC
						", true);

// ListManager
$lm = new ListManager();

// Callback
//$lm->setCellCallback(function($contenu, $titre, $numLigne, $data, $numCol){
    //return "<a href='javascript:void(Popup(\"http://wwwsite.mpa.tn/bippage/public/atelier2.php?GROFurl=".$data[0]."\"));'> ".$contenu." </a>";
//});

$lm->setFilter(['MVI_LDCL_DVCL_NUMERO' => 'D'.date('y').'%']);
   // ->setOrderBy(['grof_numero']);

$html = $lm->construct($sql);

?>

<!DOCTYPE html>
<html>
<head>
        <title>Liste Ordre de Fabrication</title>
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
