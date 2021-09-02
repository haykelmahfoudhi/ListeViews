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
         <title> listing LIGNES DE COMMANDES Octal ". $BASE." </title>
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


$szListe = "<P1> BASE : ".$BASE." Lignes de COMMANDES </P1>
            <form name=GoQuest action='$PHP_SELF' method=get>
            <table><tr><td valign=top><table>";

$szListe .= szLiBoutonsQuest ();
$szListe .= szLiBoutonsUpDown ();
$szListe .= "</table></td><td>";
$NbLigParPage = 100;

$where = "";
$filter = szLiFilter ();

if (!isset($filter) || strlen($filter)==0) {
   $_GET["Quest"]=1;
   $_GET["GO"]="GO";
   $tabselect[0]='0003';
   $_GET["filter"]=$filter;
}
$filterWhere=mb_ereg_replace("and\( \(~T.*","",$filter);
if ($filterWhere != "") $filterWhere=$filterWhere." and ";
if ($where != "") $where = " WHERE ".$filterWhere.$where.$filter;

$order = szLiOrder ();
if (!isset($orderby)) { $order=""; }
$query = "SELECT 
          mcsap_ncde,
          mcsap_poste,
          mcsap_reference,
          mcsap_qte,
          mcsap_prix_net,
          lccl.*
          FROM 
          MPA.meca_cde_sap m
          left join
          LIGNE_CDE_CLIENT lccl
          on
          to_char(lccl_user_a1)=to_char(mcsap_ordre)
          ";
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
