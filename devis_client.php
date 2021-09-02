<?php

//-----------------------------------------------------------------------------------
//              REQUIRE & DATABASE                                                                                                                      |
//-----------------------------------------------------------------------------------

define('LM_ROOT', '../ListManager/');
require_once LM_ROOT.'includes.php';
require_once('../Connecteur/base.php');

Database::instantiate(LMBASE, LMUSER, LMPASS);
//-----------------------------------------------------------------------------------

$sql = new SQLRequest("SELECT DISTINCT
							MVI_DVCL_NUMERO DEVIS,
							MVI_DVCL_CLNT_CODE CODE_CLIENT,
							MVI_CLNT_NOM CLIENT_NOM,
							MVI_DVCL_REFERENCE_CLIENT REFERENCE_CLIENT,
							MVI_CONTACT_LANC CONTACT_LANC,
							MVI_CONTACT_DEV CONTACT_DEV,
							MVI_DVCL_DEBUT_VALIDITE DEBUT_VALIDITE,
							MVI_DVCL_FIN_VALIDITE FIN_VALIDITE,
							MVI_DVCL_PERS_INITIALE_DEVIS PERS_INITIALE_DEVIS,
							MVI_DVCL_PERS_INITIALE_BE PERS_INITIALE_BE,
							MVI_DVCL_STATUT STATUT,
							MVI_DVCL_USER_DT1 DT_DEMANDE_CLIENT,
							MVI_DVCL_DT_SAISIE DT_SAISIE_DEVIS,
							MVI_DT_SAISIE_REF DT_SAISIE_REF,
							MVI_DVCL_DT_EDITION DT_EDITION,
							MVI_DVCL_DT_REPONSE DT_REPONSE_PREV,
							MVI_DVCL_USER_NUM1 NB_REF,
							MVI_DT_OBJ_CRE_DEV DT_OBJ_CRE_DEV,
							MVI_DT_OBJ_CRE_REF DT_OBJ_CRE_REF
						FROM
						MVI_DEVIS_CLIENT
						ORDER BY MVI_DVCL_NUMERO DESC", true);

// ListManager
$lm = new ListManager();

// Callback
//$lm->setCellCallback(function($contenu, $titre, $numLigne, $data, $numCol){
    //return "<a href='javascript:void(Popup(\"http://wwwsite.mpa.tn/bippage/public/atelier2.php?GROFurl=".$data[0]."\"));'> ".$contenu." </a>";
//});

$lm->setFilter(['MVI_DVCL_NUMERO' => 'D'.date('y').'%']);
   // ->setOrderBy(['MVI_DVCL_NUMERO']);

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
