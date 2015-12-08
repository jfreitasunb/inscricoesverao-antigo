
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <META http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link rel="stylesheet" type="text/css" href="http://www.mat.unb.br/inscricoespos/css/common-stylesheet.css" />
  </head>


<?php
	include_once("../../config/config.php");
	echo "<hr><h2 align='center'>Inscritos para o Ver&atilde;o: Introdução à Topologia Geral</h2><hr>";
	$i = 1;
	foreach (glob("edital-".$edital_atual."/Topologia_Geral*.pdf") as $arquivo) {
		$parte = explode("/", $arquivo);
		print $i.") <a href='$arquivo'>$parte[1]</a><br>";
		$i++;
	}
	echo "<hr><h2 align='center'>Inscritos para o Ver&atilde;o: Variáveis Complexas II</h2><hr>";
	$i = 1;
	foreach (glob("edital-".$edital_atual."/Variaveis_Complexas_II*.pdf") as $arquivo) {
		$parte = explode("/", $arquivo);
		print $i.") <a href='$arquivo'>$parte[1]</a><br>";
		$i++;
	}
?>

</html>
