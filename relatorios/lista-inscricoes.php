
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <META http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <link rel="stylesheet" type="text/css" href="http://www.mat.unb.br/inscricoespos/css/common-stylesheet.css" />
  </head>


<?php
$listaalunos = $_POST['listainscritos'];
if ($listaalunos == 'Todos') {
	echo "<hr><h2 align='center'>Inscritos para o Doutorado</h2><hr>";
	$i = 1;
	foreach (glob("edital-4-2013/Doutorado*.pdf") as $arquivo) {
		$parte = explode("/", $arquivo);
		print $i.") <a href='$arquivo'>$parte[1]</a><br>";
		$i++;
	}
	$i = 1;
	echo "<hr><h2 align='center'>Inscritos para o Mestrado</h2><hr>";
	foreach (glob("edital-4-2013/Mestrado*.pdf") as $arquivo) {
		$parte = explode("/", $arquivo);
		print $i.") <a href='$arquivo'>$parte[1]</a><br>";
		$i++;
	}
	$i = 1;
	echo "<hr><h2 align='center'>Inscritos para o Verão</h2><hr>";
	foreach (glob("edital-4-2013/Verão*.pdf") as $arquivo) {
		$parte = explode("/", $arquivo);
		print $i.") <a href='$arquivo'>$parte[1]</a><br>";
		$i++;
	}
}else{
	$i = 1;
	echo "<hr><h2 align='center'>Inscritos para o ".$listaalunos."</h2><hr>";
	foreach (glob("edital-4-2013/".$listaalunos."*.pdf") as $arquivo) {
		$parte = explode("/", $arquivo);
		print $i.") <a href='$arquivo'>$parte[1]</a><br>";
		$i++;
	}
}
?>
      
</html>