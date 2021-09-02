<?php

//----------------------------------------------------------------------------
//       Module Init Variable
//----------------------------------------------------------------------------

if (!defined ("MODULEINITCONST"))
{
define ("MODULEINITCONST",1);


$PHP_SELF=$_SERVER["PHP_SELF"];
$HTTP_HOST=$_SERVER["HTTP_HOST"];
$REMOTE_ADDR=$_SERVER["REMOTE_ADDR"];


//-------------------------------------------------
// Fin du register global possible
//-------------------------------------------------
while (list($key, $val) = each($_GET)) {$$key=$val;}

define ("DSN","oci8://proginfo:qlzM9zr3M@MPA");
}// Fin ModuleInitVar

?>
