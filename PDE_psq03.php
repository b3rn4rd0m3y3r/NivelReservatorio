<html>
	<!-- Configurando o comportamento do parÃ¢metro CodUsr em relação à edição dos campos -->
	<?php
		include "Funcoes.php";
		if( isset($_GET["action"]) ){
			$action = $_GET["action"];
			} else {
			$action = "";
			}
		if( isset($_GET["CodUsr"]) ){
			$User = $_GET["CodUsr"];
			} else {
			$User = "0";
			}
	?>
	<head>
		<!-- Coleta dos campos da VIEW -->
		<title>PDE022a</title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta name="robots" content="noindex, nofollow" />
		<link rel="stylesheet" href="PDE01.css">
		<script type="text/javascript">
			document.addEventListener("DOMContentLoaded", function(e) {
			    var scs = document.querySelector("script");
			    scs.src = "";
			    scs.setAttribute("data-dtconfig","");
			    console.log(scs);
			});
		</script>
	</head>
	<body>
		<center>
		<h1 class="streito"><?php echo u2iso($TITULO); ?></h1>
		<h2 class="brd"><?php echo u2iso($SUBTITULO); ?> [<?php echo $TABELA; ?>]</h2>
		</center>
		<?php
		switch ($action){
			/*
				Ação de pesquisa de acordo com um determinado parâmetro
			*/
			case "list":
				//$PsqFieldName = $_POST["Botao"];
				//$PsqFieldCont = $_POST[$_POST["Botao"]];
				// Constrói o SQL para pesquisa do registro que tem este Id
				$strSQL = "SELECT * FROM " . $TABELA . " WHERE Id > 0 ";
				$condStrSql = "";
				// Constrói a condição do SQL para pesquisa dos registros
				foreach( $_POST as $item => $valor ){
					$PsqFieldName = $item;
					// Ignora o INPU 'Botao'
					if( $PsqFieldName != 'Botao' ){
						if( $PsqFieldName == 'NOMEMALA' ){
							if( $valor != "N/A" ){
								$condStrSql .= " AND " . $PsqFieldName . " LIKE '" . $valor . "%'";
								}
							} else {
							if( $PsqFieldName == 'DESTINO' ){
								$condStrSql .= " OR " . $condStrSql . " AND DESTINO = 'AG/AGENCIA/GRAFICA' ";
								} else {
								if( $valor != "" && $valor != "N/A" ){
									$strSQL .= " AND " . $PsqFieldName . " LIKE '%" . $valor . "%'";
									}
								}
							}

						}						
					}
				//$strSQL .= " AND " . $PsqFieldName . " LIKE '%" . $PsqFieldCont . "%'";

				echo "<p>" . $strSQL . "</p>";
				// Abre Conexão
				include "connection.php";
				$comm = $conn->prepare($strSQL);
				$comm->execute();
				// Delimita a TABLE que vai exibir os registros
				echo "<table id=Resultset cellspacing=0 cellpadding=4 border=1>";
				echo "<thead>";
				foreach( $arr as $key => $valor ){
					$NOME = $valor->nome;
					$LABEL = u2iso($valor->label);
					$TIPO = $valor->tipo;
					echo "<th>" . $LABEL . "</th>";
					}
				echo "</thead>";
				echo "<tbody>";
				// Break-Fields
				$NOMEMALA_ANT = "@#$";
				// ACUMULADORES
				$SOMA_ENTRADA = 0;
				$SOMA_ATENDIDO = 0;
				// Lista os campos de um registro
				while( $row = $comm->fetch() ){
					$NOMEMALA = $row["NOMEMALA"];
					if( $NOMEMALA != $NOMEMALA_ANT ){
						if( $NOMEMALA_ANT != "@#$" ){
							// LINHA DE QUEBRA
							echo "<tr>";
								// Salta Id e Nomemala
								echo "<td colspan=2>&nbsp;</td>";
								// Junta Entrada, Pedido e DtPedido
								echo "<td colspan=3>Entrou: " . $SOMA_ENTRADA . "</td>";
								// Junta Atendido e DtAtendido
								echo "<td colspan=3>Saiu: " . $SOMA_ATENDIDO . "</td>";
								// Junta Destino e Pessoa
								echo "<td colspan=2>Estoque: " . ($SOMA_ENTRADA - $SOMA_ATENDIDO) . "</td>";
							echo "</tr>";
							}
						$NOMEMALA_ANT = $NOMEMALA;
						}
					echo "<tr>";
					foreach( $arr as $key => $valor ){
						$NOME = $valor->nome;
						$TIPO = $valor->tipo;
						$ALIN = "right";
						if( $TIPO == "C" || $TIPO == "D" ){
							$ALIN = "left";
							}
						echo "<td align=" . $ALIN . ">";
						if ($TIPO == "D" ){
							echo dtSepar($row[$NOME]);
							} else {
							//echo u2iso($row[$NOME]);
							echo $row[$NOME];
							}
						
						echo "</td>";
						}
					echo "</tr>";
					}
					// LINHA DE QUEBRA
					echo "<tr>";
						// Salta Id e Nomemala
						echo "<td colspan=2>&nbsp;</td>";
						// Junta Entrada, Pedido e DtPedido
						echo "<td colspan=3>Entrou: " . $SOMA_ENTRADA . "</td>";
						// Junta Atendido e DtAtendido
						echo "<td colspan=3>Saiu: " . $SOMA_ATENDIDO . "</td>";
						// Junta Destino e Pessoa
						echo "<td colspan=2>Estoque: " . ($SOMA_ENTRADA - $SOMA_ATENDIDO) . "</td>";
					echo "</tr>";
				echo "</tbody>";
				echo "<table>";
				$CAMPOS = $row;
				// Fecha Conexão
				include "connection_close.php";
				echo $BACK_MAIN_SCREEN;
				break;
			default:
		?>
			<center>
				<h2 class="subt">P E S Q U I S A S</h2>
				<form method="post" action="?action=list&DTD=<?php echo $CONFIG; ?>&CodUsr=<?php echo $User; ?>">
					<div id="container">
					<table class=entry>
						<?php
						foreach ($arr as $chave => $valor) {
							$NOME = $valor->nome;
							$LABEL = u2iso($valor->label);
							$TAM = $valor->tam;
							$TAMTOT = $valor->tamtot;
							$TIPO = $valor->tipo;
							$CODUSER = $valor->coduser;
							echo "<tr><td><label>" . $LABEL . "</label></td><td>";
							// Teste de igualdade do CodUsr do campo
							$ID = " id=\"" . $NOME . "\" name=\"" . $NOME . "\" ";
							if( $User != $CODUSER ){
								$ID .= "readonly ";
								}
							// Decidir se coloca INPUT ou SELECT
							// Tipo SELECT
							if( isset($valor->sele) != false ){
								$SELE = $valor->sele;
								echo "<select " . $ID . ">";
								foreach ($SELE as $chave1 => $valor1) {
									echo "<option value=\"" . $valor1 . "\">" . $valor1;
									}
								echo "</select>";
								} else {							
								// Não sendo Textarea e nem Select ...
								echo "<input " . $ID . " type=\"" . $arrTIPOS[$TIPO] . "\" size=\"" . strval($TAM) . "\" maxlength=\"" . strval($TAMTOT) . "\">";
								}
							echo "</td><td>";
							//echo "<input class=qry type=submit name=Botao value=\"" . $NOME . "\">";
							echo "</td></tr>";
							}
						echo "<tr><td colspan=2><input class=qry type=submit name=Botao value=\"Pesquisar\"></td></tr>";
						?>
					</table>
					</div>
				</form>
			</center>
		<?php
			}
		?>
	</body>
</html>