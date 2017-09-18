<?php
require "lessc.inc.php";

/***************************************/

function compileLESS($file,$newfile){
	$less = new lessc;
	$less->setFormatter("compressed");
	try {
	  echo  "<h4>Successfully converted</h4>
	  Lines: ".$less->compileFile($file, $newfile);
	} catch (exception $e) {
	  echo "<h4 style='color:red;'>FATAL ERROR</h4>" . $e->getMessage();
	}
}

/***************************************/

compileLESS("main.less","../styles.css");

/***************************************/

?>