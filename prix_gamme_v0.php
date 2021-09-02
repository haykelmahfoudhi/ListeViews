<? php>
session_start();
require ("initvar.php");

   $BASE = "MPA";
   $css="newmecaprotec.css";
   $page2="prix_phase.php";

require ("phplib/LibListe.php");

//-----------------------------------------------------------------------------
//     Entete html 
//-----------------------------------------------------------------------------


$header="<html>
         <head>
         <title> listing prix Octal ". $BASE." </title>
         <link rel=stylesheet type=text/css href=jslib/$css>
         <script language='JavaScript'>

            var IE4 = (document.all) ? 1 : 0;
            var NS4 = (document.layers) ? 1 : 0;
            var VERSION4 = (IE4 | NS4) ? 1 : 0;
           
            pwPopup=null;
            function popup(client,article) {
              var str='prix_phase.php?GO=GO&orderby=-1,2,3&Mask=&lens%5B%5D=10&names%5B%5D=clar_clnt_code&types%5B%5D=VARCHAR&tabselect%5B%5D=@CLIENT@&lens%5B%5D=16&names%5B%5D=arti_code&types%5B%5D=VARCHAR&tabselect%5B%5D=@ARTICLE@';
              str=str.replace('@CLIENT@',client);
              url=str.replace('@ARTICLE@',article);
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

$szListe = "<P1> BASE : ".$BASE."</P1>
            <form name=GoQuest action='$PHP_SELF' method=get>
            <table><tr><td valign=top><table>";

$szListe .= szLiBoutonsQuest ();
$szListe .= szLiBoutonsUpDown ();
$szListe .= "</table></td><td>";
$NbLigParPage = 100;
$where="ARTI_CODE=ARGA_ARTI_CODE AND
        ARTI_CODE=CLAR_ARTI_CODE AND
        clnt_code=Clar_Clnt_code and
        Arga_Arti_Code=Clar_Arti_Code and
        Arga_Gamm_Numero=Clar_gamm_code and
        Arga_Gamm_Numero=Rega_gamm_numero and
        phga_Gamm_Numero=Rega_gamm_numero and
        PHGA_NUMERO_PHASE=rega_phga_numero_phase
        GROUP BY 
        Clar_Clnt_code,
        arga_gamm_numero,
        Arti_Code,Arti_Designation,ARTI_ARSF_CODE";

$filter = szLiFilter ();

if (!isset($filter) || strlen($filter)==0) {
   $_GET["Quest"]=1;
   $_GET["GO"]="GO";
   $tabselect[0]='4001';
   $filter="( (~CLIENT~ LIKE '4001') )";
   $_GET["tabselect"][0]=$tabselect[0];
}
$filterWhere=mb_ereg_replace("and\( \(~T.*","",$filter);
$filterWhere=mb_ereg_replace("~CLIENT~","Clar_Clnt_code",$filterWhere);
$filterWhere=mb_ereg_replace("~ARTICLE~","ARTI_CODE",$filterWhere);
$filterWhere=mb_ereg_replace("~NOM~","clnt_nom",$filterWhere);
if ($filterWhere != "") $filterWhere=$filterWhere." and ";
if ($where != "") $where = " WHERE ".$filterWhere.$where;
if ($filter != "") $where .= " HAVING $filter";

$order = szLiOrder ();
$query = "SELECT 
          Clar_Clnt_code  AS CLIENT,
          min(clnt_nom) AS NOM,
          ARTI_CODE AS ARTICLE, 
	  ARTI_ARSF_CODE AS DONNORDRE,
	  ARTI_DESIGNATION AS DESIGN,
          Arga_GAMM_NUMERO AS GAMME, 
          ROUND(sum(REGA_COEFFICIENT_T1*REGA_PU_TRAITEMENT),2) AS T01_10,
          ROUND(sum(REGA_COEFFICIENT_T2*REGA_PU_TRAITEMENT),2) AS T11_100,
          ROUND(sum(REGA_COEFFICIENT_T3*REGA_PU_TRAITEMENT),2) AS T101_500,
          ROUND(sum(REGA_COEFFICIENT_T4*REGA_PU_TRAITEMENT),2) AS T501,
	  (select max(lccl_dt_derniere_facturation) from ligne_cde_client 
	   where lccl_clnt_code=clar_clnt_code and
	   lccl_arti_code=arti_code ) AS dt_last_fact,
          Pack_Mecapro2.phga2str_trt(Arga_GAMM_NUMERO) AS deroule
          FROM 
	  ARTICLE,
          Article_gamme,
          Phase_gamme,
          Ressource_Gamme,
          Client_Article,Client";
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
