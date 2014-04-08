<?php
function escapeXML($strItem, $forDataURL) {
	if ($forDataURL) {
		$strItem = str_replace("'","&apos;", $strItem);        
	} else {
		$findStr = array("%", "'", "&", "<", ">");
		$repStr  = array("%25", "%26apos;", "%26", "&lt;", "&gt;");
		$strItem = str_replace($findStr, $repStr, $strItem);        
	}
    $findStr = array("<", ">");
    $repStr  = array("&lt;", "&gt;");
    $strItem = str_replace($findStr, $repStr, $strItem);        
	return $strItem;
}

function obtenerPaleta() {
	return (((!isset($_SESSION['palette'])) || ($_SESSION['palette']=="")) ? "2" : $_SESSION['palette']);
}

function obtenerEstadoAnimacion() {
	return (($_SESSION['animation']<>"0") ? "1" : "0");
}

function obtenerColorFuente() {
    return "666666";
}
?>