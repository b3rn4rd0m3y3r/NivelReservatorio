<?php

	// Abre Conexão
	include "connection.php";
	$TABELA = "Volumes";
	$PsqFieldName = "Sistema";
	$PsqFieldCont = $_POST[$PsqFieldName];
	echo $PsqFieldCont . "<br>";
	
	// Constrói o SQL para pesquisa do registro que tem este Id
	$strSQL = "SELECT Sistema,Volume, substr(DtMedicao, 7,4)||substr(DtMedicao, 4,2)||substr(DtMedicao, 1,2) as DtEfet FROM " . $TABELA . " WHERE Id > 0 ";
	$strSQL .= " AND " . $PsqFieldName . " LIKE '%" . $PsqFieldCont . "%' ";
	$strSQL .= " AND DtEfet >= '20230201' ";
	$strSQL .= " AND DtEfet < '20230301' ";
	$strSQL .= " LIMIT 100 ";
	echo $strSQL . "<br>";
		
	$comm = $conn->prepare($strSQL);
	$comm->execute();
	$volume_menor = 999999999;
	$volume_maior = 0;

	while( $row = $comm->fetch() ){
		$volume = (double)$row["Volume"];
		echo (double)$volume . "-" . $row["DtEfet"] . "<br>";
		if( $volume > $volume_maior ){
			$volume_maior = $volume;
			} else {
			$volume_menor = $volume;
			}
		}
	echo "Menor: " . $volume_menor . " - " . "Maior: " . $volume_maior;

	// Fecha Conexão
	include "connection_close.php";

?>