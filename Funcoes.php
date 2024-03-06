<?php
	function u2iso($tx){
		return iconv("UTF-8", "ISO-8859-1", $tx);
		}
	function i2utf($tx){
		return iconv("ISO-8859-1","UTF-8", $tx);
		}
	header('Content-type: text/html; charset=UTF-8');
	
	$arrTIPOS = ["C"=>"text", "D"=>"date", "I"=>"text", "N"=>"number"];
	
	// 1 - Leitura da configuração em JSON
	// Texto em UTF-8
	//$CONFIG = "./" . $_GET["DTD"];
	$CONFIG = "" . $_GET["DTD"];
	
	$txt = i2utf(file_get_contents($CONFIG) );
	// Conversão em JSON
	$obj = json_decode($txt);
	// Extrai permissões
	$PERMISSOES = $obj->permissao;
	$TABELA = $obj->tabela;
	$TITULO = $obj->titulo;
	$SUBTITULO = $obj->subtitulo;
	$arr = $obj->campos;
	$arrNomes = [];
	$arrDec = json_decode(json_encode($arr), true);
	
	foreach ($arrDec as $chave => $valor) {
		$arrNomes[$arrDec[$chave]["nome"]] = $arrDec[$chave];
		}
	$IDFOUND = 0;
	if( isset($_GET["Id"]) ){
		$IDFOUND = 1;
		}
	
?>