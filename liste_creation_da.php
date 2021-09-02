<?php

//-----------------------------------------------------------------------------------
//		REQUIRE & DATABASE 															|
//-----------------------------------------------------------------------------------

define('LM_ROOT', '../ListManager/');
require_once LM_ROOT.'includes.php';

Database::instantiate('oci:dbname=MPA', 'proginfo', 'qlzM9zr3M');
//-----------------------------------------------------------------------------------

// Requete SQL
$sql = new SQLRequest("SELECT mvcd_cdcl_numero,
                              mvcd_blcl_numero,   
                              mvcd_facl_numero,
                              mvcd_arti_code,
                              mvcd_qte_cde,
                              mvcd_qte_produite,
                              mvcd_qte_fact,
                              mvcd_pu_cde,
                              mvcd_pu_fact,
                              mvcd_clnt_code,
                              mvcd_clnt_nom,
                              mvcd_affa_numero,
                              mvcd_dt_facture,
                              mvcd_orfa_numero,
                              mvcd_grof_numero,
                              mvcd_ref_client,
                              mvcd_dossier,
                              mvcd_da,
                              mvcd_devis,
                              mvcd_nc 
                       FROM MV_CREATION_DA 
                       ORDER BY mvcd_cdcl_numero desc", true);

// Config ListManager
$lm = new ListManager();
$lm ->setFilter(['mvcd_cdcl_numero' => 'C'.date('y').'-00105']);

// Affichage de la liste
$html = $lm->construct($sql);

?>

<!DOCTYPE html>
<html>
<head>
   	<title>Liste creation da</title>
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
