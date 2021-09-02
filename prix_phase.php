<? php>
session_start();
require ("initvar.php");
// hpx
// devischiffrage

$BASE="MPA";
if ($BASE == "TEST") {
   define (DSN,"oci8://proginfo:qlzM9zr3M@TEST");
   $css="mecaprotec.css";
   $page2="prix_phaseTEST.php";
   } else {
   $BASE = "MPA";
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
         <title> Prix Phase ". $BASE." </title>
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

$where=" Arga_Arti_Code = Arti_Code and
clnt_code = substr(arga_gamm_numero,1,4) and
Arga_Gamm_Numero = Rega_gamm_numero and
phga_Gamm_Numero = Rega_gamm_numero and
PHGA_NUMERO_PHASE = rega_phga_numero_phase and
h.ARSP_ARTI_CODE(+) = arti_code AND
h.ARSP_SPTE_CODE(+) = 'HAUTEUR' AND
lo.ARSP_ARTI_CODE(+) = arti_code AND
lo.ARSP_SPTE_CODE(+) = 'LONGUEUR' AND
la.ARSP_ARTI_CODE(+) = arti_code AND
la.ARSP_SPTE_CODE(+) = 'LARGEUR' AND
LDCL_ARTI_CODE(+) = Arga_Arti_Code AND
LDCL_CLNT_CODE(+) = substr(arga_gamm_numero,1,4) AND
LDCL_GAMM_NUMERO(+) = Arga_Gamm_Numero";

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
$query = " SELECT DISTINCT
                Clnt_code  AS CLIENT ,
                Arga_Arti_Code AS article,
                PHGA_NUMERO_PHASE AS phase,
                REGA_RESS_CODE AS RES,
                PHGA_GAMM_NUMERO AS GAMME,
                PHGA_POST_CODE AS Traitement,
                PHGA_GF AS GF,
                ARTI_ARSF_CODE AS DONORDRE,
                PHGA_NORME_CLIENT AS NORME,
                PHGA_DESIGNATION AS Libelle,
                --Pack_Mecapro2.COEF_Revente_peinture_PHGA (PHGA_GAMM_NUMERO,PHGA_NUMERO_PHASE) AS COEF,
                TO_CHAR((REGA_COEFFICIENT_T1* REGA_PU_TRAITEMENT),'9999.99') AS T1_10,
                TO_CHAR((REGA_COEFFICIENT_T2* REGA_PU_TRAITEMENT),'9999.99') AS T11_100,
                TO_CHAR((REGA_COEFFICIENT_T3* REGA_PU_TRAITEMENT),'9999.99') AS T101_500,
                TO_CHAR((REGA_COEFFICIENT_T4* REGA_PU_TRAITEMENT),'9999.99') AS T501,
                h.ARSP_VALEUR_N AS HAUT,
                lo.ARSP_VALEUR_N AS LON,
                la.ARSP_VALEUR_N AS LARG,
                LDCL_DVCL_NUMERO AS DEVIS_CLIENT
                FROM
                Article_gamme,
                Phase_gamme,
                Ressource_Gamme,
                Client
                ,ARTICLE
                ,ARTICLE_SPECIFICATION h
                ,ARTICLE_SPECIFICATION lo
                ,ARTICLE_SPECIFICATION la
                ,LIGNE_DEVIS_CLIENT";

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
