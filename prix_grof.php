<? php>
session_start();
require ("initvar.php");
// hpx
// devischiffrage

$BASE="MECAPROTEC";
if ($BASE == "TEST") {
   define (DSN,"oci8://proginfo:qlzM9zr3M@TEST");
   $css="mecaprotec.css";
   $page2="prix_phaseTEST.php";
   } else {
   $BASE = "MECAPROTEC";
   define (DSN,"oci8://proginfo:qlzM9zr3M@MECAPROTEC");
   $css="newmecaprotec.css";
   $page2="prix_phase.php";
}

require ("phplib/LibListe.php");

//-----------------------------------------------------------------------------
//     Entete html
//-----------------------------------------------------------------------------


$header="<html>
         <head>
         <title> Prix Grof ". $BASE." </title>
         <link rel=stylesheet type=text/css href=jslib/$css>
         <script language='JavaScript'>

            var IE4 = (document.all) ? 1 : 0;
            var NS4 = (document.layers) ? 1 : 0;
            var VERSION4 = (IE4 | NS4) ? 1 : 0;

            pwPopup=null;
            function popup(client,article,of) {
              var str='prix_phase_of.php?GO=GO&Quest=1&orderby=-1,2,3,4&Mask=&lens%5B%5D=10&names%5B%5D=clnt_code&types%5B%5D=VARCHAR&tabselect%5B%5D=@CLIENT@&lens%5B%5D=16&names%5B%5D=arti_code&types%5B%5D=VARCHAR&tabselect%5B%5D=@ARTICLE@&=&lens[]=22&names[]=~OF~&types[]=NUMBER&tabselect[]=@OF@';
              str=str.replace('@CLIENT@',client);
              str=str.replace('@OF@',of);
              url=str.replace('@ARTICLE@',article);
              options= 'toolbar=no,location=no,directories=no,menubar=no,resizable=yes,scrollbars=yes,alwaysRaised=yes,width=1500,height=500,dependent=no';
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
/*$where="ARTI_CODE=ARGA_ARTI_CODE AND
        clnt_code=substr(arga_gamm_numero,1,4) and
        Arga_Gamm_Numero=Rega_gamm_numero and
        phga_Gamm_Numero=Rega_gamm_numero and
        PHGA_NUMERO_PHASE=rega_phga_numero_phase
        GROUP BY
        Clnt_code,
        arga_gamm_numero,
        Arti_Code,Arti_Designation,ARTI_ARSF_CODE";*/
$where="ARTI_CODE=orfa_ARTI_CODE AND
        clnt_code=substr(orfa_gamm_numero,1,4) and
	ofgr_orfa_numero=orfa_numero and
	ofgr_grof_numero = grof_numero and
 --       la.arsp_arti_code = arti_code and
 --       la.arsp_spte_code = 'LARGEUR' and
 --       lo.arsp_arti_code = arti_code and
 --       lo.arsp_spte_code = 'LONGUEUR' and
 --       h.arsp_arti_code = arti_code and
 --       h.arsp_spte_code = 'HAUTEUR' and
	orfa_cdcl_numero = lfcl_cdcl_numero and
	orfa_lccl_ligne = lfcl_lccl_ligne
        ";

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
$filterWhere=mb_ereg_replace("~ARTICLE~","ARTI_CODE",$filterWhere);
$filterWhere=mb_ereg_replace("~NOM~","clnt_nom",$filterWhere);
if ($filterWhere != "") $filterWhere=$filterWhere." and ";
if ($where != "") $where = " WHERE ".$filterWhere.$where;
//if ($filter != "") $where .= " HAVING $filter";

$order = szLiOrder ();
/*$query = "SELECT
          Clnt_code  AS CLIENT,
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
	   where lccl_clnt_code=clnt_code and
	   lccl_arti_code=arti_code ) AS dt_last_fact,
          Pack_Mecapro2.phga2str_trt(Arga_GAMM_NUMERO) AS deroule
          FROM
	  ARTICLE,
          Article_gamme,
          Phase_gamme,
          Ressource_Gamme,
          Client";*/
$query = "SELECT
          Clnt_code  AS CLIENT,
          clnt_nom AS NOM,
	  ofgr_grof_numero as Grof,
	  orfa_numero as Of,
          ARTI_CODE AS ARTICLE,
          ARTI_ARSF_CODE AS DONNORDRE,
          ARTI_DESIGNATION AS DESIGN,
          orfa_GAMM_NUMERO AS GAMME,
	  grof_cycle_prevu AS Cycle,
	  ORFA_DT_LANCEMENT_REEL as dt_lanc,
	  ORFA_DT_FIN as dt_solde,
	  lfcl_qte_facturee_uv as qte,
	  lfcl_prix_unitaire_uv as Prix_Unit,
	  lfcl_qte_facturee_uv*lfcl_prix_unitaire_uv as Prix_Fact,
	  lfac.dt_cre as dt_fact,
    Pack_Mecapro2.phof2str_trt(orfa_NUMERO) AS deroule
          --la.arsp_valeur_n as largeur,
          --lo.arsp_valeur_n as longueur,
          --h.arsp_valeur_n as hauteur
          FROM
	  ligne_facture_client lfac,
          ARTICLE,
	  of_groupe,
	  groupe_of,
          ordre_fabrication,
          Client
          --article_specification la,
          --article_specification lo,
          --article_specification h
		";

$query .= " ".$where." ".$order;

$dest="'\$row[0]','\$row[4]','\$row[3]'";
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
