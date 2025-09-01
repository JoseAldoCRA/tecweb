<?php
include("encabezado.inc.php");  // (1)
echo "<hr />";
include_once("cuerpo.inc.php"); // (2)
require("cuerpo.html");         // (3)
require_once("pie.inc.php");    // (4)
?>
