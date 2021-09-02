<? php>
session_start();
require ("initvar.php");

$BASE="MECAPROTEC";

if ($BASE == "TEST") { 
   define (DSN,"oci8://proginfo:qlzM9zr3M@TEST");
   $css="mecaprotec.css";
   } else {
   define (DSN,"oci8://proginfo:qlzM9zr3M@MECAPROTEC");
   $css="newmecaprotec.css";
   $BASE="MECAPROTEC";
}


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
if (!isset($orderby)) { $orderby="-1,2,3,4"; }

$szListe = "<P1> BASE : ".$BASE."</P1>
   <form name=GoQuest action='$PHP_SELF' method=get>
   <table><tr><td valign=top><table>";

$szListe .= szLiBoutonsQuest ();
$szListe .= szLiBoutonsUpDown ();
$szListe .= "</table></td><td>";
$NbLigParPage = 100;
$where="
orfa_numero=phof_orfa_numero and
clnt_code=substr(orfa_gamm_numero,1,4) and
phof_orfa_Numero=Reof_orfa_numero and
PHOF_NUMERO_PHASE=reof_phof_numero_phase and
h.ARSP_ARTI_CODE(+)=arti_code AND
h.ARSP_SPTE_CODE(+)='HAUTEUR' AND 
lo.ARSP_ARTI_CODE(+)=arti_code AND
lo.ARSP_SPTE_CODE(+)='LONGUEUR' AND
la.ARSP_ARTI_CODE(+)=arti_code AND
la.ARSP_SPTE_CODE(+)='LARGEUR' 
and rownum < 20
";
$filter = szLiFilter ();
if ($where != "" && $filter !="" ) $where= "(".$where.") and (".$filter.")";
if ($where == "" && $filter !="" ) $where=$filter;
if ($where != "") $where = " WHERE ".$where;

$order = szLiOrder ();
$query = "
SELECT
Clnt_code  AS Client ,
Arti_Code AS article,
orfa_numero as OF,
PHOF_NUMERO_PHASE AS phase,
REOF_RESS_CODE AS RES,
ORFA_GAMM_NUMERO AS GAMME,
PHOF_POST_CODE AS Traitement,
PHOF_GF AS GF,
ARTI_ARSF_CODE AS DONORDRE,
PHOF_NORME_CLIENT AS NORME,
PHOF_DESCRIPTIF_OPERATION AS Libelle,
--Pack_Mecapro2.COEF_Revente_peinture_PHGA (ORFA_GAMM_NUMERO,PHOF_NUMERO_PHASE) AS COEF,
TO_CHAR((REOF_COEFFICIENT_T1* REOF_PU_TRAITEMENT),'9999.99') AS T1_10,
TO_CHAR((REOF_COEFFICIENT_T2* REOF_PU_TRAITEMENT),'9999.99') AS T11_100,
TO_CHAR((REOF_COEFFICIENT_T3* REOF_PU_TRAITEMENT),'9999.99') AS T101_500,
TO_CHAR((REOF_COEFFICIENT_T4* REOF_PU_TRAITEMENT),'9999.99') AS T501,
h.ARSP_VALEUR_N AS HAUT,
lo.ARSP_VALEUR_N AS LON,
la.ARSP_VALEUR_N AS LARG
FROM 
ordre_fabrication,
Phase_Of,
Ressource_Of,
Client
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
