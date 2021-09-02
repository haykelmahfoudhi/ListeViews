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
   <title> detail prix octal base : ".$BASE."</title>
   <link rel=stylesheet type=text/css href=jslib/$css>
   </head>
";
// ----- LA Liste ------------
/* critere de tri par defaut non pas 1 mais toujours societe */
if (!isset($orderby)) { $orderby="-1,2,3"; }

$szListe = "<P1> BASE : ".$BASE."</P1>
   <form name=GoQuest action='$PHP_SELF' method=get>
   <table><tr><td valign=top><table>";

$szListe .= szLiBoutonsQuest ();
$szListe .= szLiBoutonsUpDown ();
$szListe .= "</table></td><td>";
$NbLigParPage = 100;
$where="
Arga_Arti_Code=Clar_Arti_Code and
Clar_gamm_code=Arga_Gamm_Numero and
Arga_Gamm_Numero=Rega_gamm_numero and
phga_Gamm_Numero=Rega_gamm_numero and
PHGA_NUMERO_PHASE=rega_phga_numero_phase and
ARTI_CODE=CLAR_ARTI_CODE AND
h.ARSP_ARTI_CODE(+)=arti_code AND
h.ARSP_SPTE_CODE(+)='HAUTEUR' AND 
lo.ARSP_ARTI_CODE(+)=arti_code AND
lo.ARSP_SPTE_CODE(+)='LONGUEUR' AND
la.ARSP_ARTI_CODE(+)=arti_code AND
la.ARSP_SPTE_CODE(+)='LARGEUR' 
";
$filter = szLiFilter ();
if ($where != "" && $filter !="" ) $where= "(".$where.") and (".$filter.")";
if ($where == "" && $filter !="" ) $where=$filter;
if ($where != "") $where = " WHERE ".$where;

$order = szLiOrder ();
$query = "
SELECT 
Clar_Clnt_code  AS Client ,
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
la.ARSP_VALEUR_N AS LARG
FROM 
Article_gamme,
Phase_gamme,
Ressource_Gamme,
Client_Article
,ARTICLE
,ARTICLE_SPECIFICATION h
,ARTICLE_SPECIFICATION lo
,ARTICLE_SPECIFICATION la
";
$query .= " ".$where." ".$order;

$urlstring="";
$szListe .= szLiTableAncre ($query,$NbLig,$urlstring,$NbLigParPage);

$szListe .= "</td></tr></table>
             </form>";

// ----- Fin de page ------------
$footer = "
</body>
</html>";

//  ------------------  l'echo de tout ca
if (!Li2xlsSend()) {
   echo $header.$szListe.$footer;
}

?>
