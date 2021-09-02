<? php>
session_start();
require ("initvar.php");
$css="newmecaprotec.css";
$BASE="MPA";
require ("phplib/LibListe.php");

//-----------------------------------------------------------------------------
//     Entete html 
//-----------------------------------------------------------------------------


$header="<html>
         <head>
         <title> listing COMPLEMENT DE FACTURES Octal ". $BASE." </title>
         <link rel=stylesheet type=text/css href=jslib/$css>
         <script language='JavaScript'>

            var IE4 = (document.all) ? 1 : 0;
            var NS4 = (document.layers) ? 1 : 0;
            var VERSION4 = (IE4 | NS4) ? 1 : 0;
           
            pwPopup=null;
            function popup(client,article) {
              var str='prix_phase.php?GO=GO&Quest=1&orderby=-1,2,3&Mask=&lens%5B%5D=10&names%5B%5D=clnt_code&types%5B%5D=VARCHAR&tabselect%5B%5D=@CLIENT@&lens%5B%5D=16&names%5B%5D=arti_code&types%5B%5D=VARCHAR&tabselect%5B%5D=@ARTICLE@';
              str=str.replace('@CLIENT@',client);
              options= 'toolbar=no,location=no,directories=no,menubar=no,resizable=yes,scrollbars=yes,alwaysRaised=yes,width=750,height=300,dependent=no';
              pwPopup=window.open (url,'Prix_Phase',options);
              return pwPopup;
            }
            function closePopup() {
              if (pwPopup != null) pwPopup.close();
            }

         </script>
         </head> ";



// ----- LA Liste ------------
/* critere de tri par defaut non pas 1 mais toujours societe */
//if (!isset($orderby)) { $orderby=2; }


$szListe = "<P1> BASE : ".$BASE." Lignes de COMPLEMENT FACTURES </P1>
            <form name=GoQuest action='$PHP_SELF' method=get>
            <table><tr><td valign=top><table>";

$szListe .= szLiBoutonsQuest ();
$szListe .= szLiBoutonsUpDown ();
$szListe .= "</table></td><td>";
$NbLigParPage = 100;

$where = " ";
$filter = szLiFilter ();

if (!isset($filter) || strlen($filter)==0) {
   $_GET["Quest"]=1;
   $_GET["GO"]="GO";
   $tabselect[0]='0003';
   $filter="( (~CLIENT~ LIKE '0003') )";
   $_GET["tabselect"][0]=$tabselect[0];
   $_GET["filter"]=$filter;
}
$filterWhere=mb_ereg_replace("and\( \(~T.*","",$filter);
$filterWhere=mb_ereg_replace("~CLIENT~","Clnt_code",$filterWhere);
$filterWhere=mb_ereg_replace("~NOM~","clnt_nom",$filterWhere);
if ($filterWhere != "") $filterWhere=$filterWhere." and ";
if ($where != "") $where = " WHERE ".$filterWhere.$where.$filter;

$order = szLiOrder ();
if (!isset($orderby)) { $order=""; }
$query = "SELECT 
clnt_code AS CLIENT,
clnt_nom AS NOM,
facl_dt_saisie,
blcl_gare as GROF,
cfcl.cfcl_numero,
cfcl.cfcl_facl_numero,
cfcl.cfcl_cplt_code,
cfcl.cfcl_mt_ht,
cfcl.cfcl_taux_remise,
cfcl.cfcl_taxe_code_para,
cfcl.cfcl_taxe_code_tva,
cfcl.cfcl_blcl_numero,
cfcl.cfcl_cdcl_numero,
cfcl.dt_cre,
cfcl.login_cre,
cfcl.dt_maj,
cfcl.login_maj,
(select cdcl_reference_client from cde_client where cdcl_numero = (select max(lccl_cdcl_numero) from ligne_cde_client where lccl_grof_numero_final=blcl_gare)) AS REF_CLIENT
FROM 
cplt_facture_client cfcl
join 
facture_client 
on facl_numero=cfcl_facl_numero
join
bl_client
on 
blcl_numero=cfcl_blcl_numero
join client
on  facl_clnt_code=clnt_code";

$query .= " ".$where." ".$order;

$dest="'\$row[0]','\$row[2]'";
$urlstring="javascript:void(popup(".$dest."));";
$szListe .= szLiTableAncre ($query,$NbLig,$urlstring,$NbLigParPage);

$szListe .= "</td></tr></table>
             </form>";

// ----- Fin de page ------------
$footer = "</body></html>";

//  ------------------  l'echo de tout ca
if (!Li2xlsSend()) {
   echo $header.$szListe.$footer;
}

?>
