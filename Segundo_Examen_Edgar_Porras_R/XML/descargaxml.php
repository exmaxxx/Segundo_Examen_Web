<?php
	header('Content-disposition: attachment; filename=Reporte_1.xml');
	header('Content-type: application/octet-stream .xml; charset=utf-8');

	//obtiene raiz del sitio
    $ruta = $_SERVER["DOCUMENT_ROOT"]."/XML/Reporte#1.xml";

	readfile($ruta);
?>
