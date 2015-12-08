
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <META http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link rel="stylesheet" type="text/css" href="http://www.mat.unb.br/inscricoespos-completo/css/common-stylesheet.css" />
  </head>


<?php

	echo "<hr><h2 align='center'>Inscritos para Topologia Geral</h2><hr>";
	$i = 1;
	foreach (glob("edital-4-2013/Top*.pdf") as $arquivo) {
		$parte = explode("/", $arquivo);
		print $i.") <a href='$arquivo'>$parte[1]</a><br>";
		$i++;
	}
	$i = 1;
	echo "<hr><h2 align='center'>Inscritos para Variedades Difenreciaveis</h2><hr>";
	foreach (glob("edital-4-2013/Var*.pdf") as $arquivo) {
		$parte = explode("/", $arquivo);
		print $i.") <a href='$arquivo'>$parte[1]</a><br>";
		$i++;
	}
?>

</html>
