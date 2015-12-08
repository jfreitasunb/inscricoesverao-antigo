<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	

<body>

<?
include("../pgsql/pgsql.php");
include("gera_senha.php");
include("../config/config.php");

function manda_mail_recomendante($mail_recomendante,$senha_recomendante,$id_aluno,$programa)
{
	
$query_busca=pg_query("select name||' '||firstname as nome, cr.* from inscricao_pos_dados_candidato dc,inscricao_pos_contatos_recomendante cr where cr.id_aluno=dc.id_aluno and dc.id_aluno='".$id_aluno."' and cr.programa='".$programa."'");

$dados=pg_fetch_row($query_busca);
//print_r($dados);
for ($i=4;$i<9;$i=$i+2)
	{
	if ($dados[$i]==$mail_recomendante) $nome_recomendante=$dados[$i-1];
	}

if (($nome_recomendante!="") and ($mail_recomendante!=""))
	{
	$nome_aluno=$dados[0];

	$texto="Prezado(a) Prof(a).".$nome_recomendante.",\n Para poder enviar a carta de recomendação do(a) aluno(a) ".$nome_aluno." para inscrição
	 no Programa de ".$programa." do MAT/UnB, solicitamos que acesse o link http://www.mat.unb.br/inscricoespos \n 
	 Seu login é : ".$mail_recomendante." \n Sua senha é: ".$senha_recomendante."\n O envio desta carta pode ser feito até o dia ".$data_limite.". \n 
	 Antecipadamente agradecemos, \n Coordenação de Pós-Graduação do MAT/UnB."; 
	
	$texto=wordwrap($texto,70);

	// mensagem:
	
	$subject = "Dados de acesso para recomendação do(a) aluno(a) ".$nome_aluno." para o Programa de ".$programa." do MAT/UnB";
	$headers = "FROM: posgrad@mat.unb.br";

	echo $mail_recomendante.";\n".$subject." ;\n ".$texto." ;\n".$headers."<br>";
	$res_mail=mail($mail_recomendante, $subject, $texto, $headers);
	if ($res_mail) $devolve="mensagem enviada"; else $devolve="problema no envio de mensagens";
	}
	else $devolve="Nao ha nome ou mail do recomendante";
return $devolve;

}


function inclusao_login_recomendante($login,$senha)
{
$query_checa=pg_query("select * from inscricao_pos_login where login='".$login."'");
$num_checa=pg_num_rows($query_checa);

if($num_checa==0)
	{
	$query_insere="insert into inscricao_pos_login values(default,'".$login."',md5('".$senha."'),'recomendante')";
	$res_insere=pg_query($query_insere) or die ("problema de conexao com o banco");
	//echo $query_insere."<br>";
	if($res_insere) $devolve="gravado"; else $devolve="problema";
	}
	else $devolve="ja_existe";
return $devolve;
}


$programa=$_POST['programa'];
$manda=$_POST['manda'];
foreach($manda as $key => $value)
	{
	$dados=explode("_-_",$value);
	$id_aluno=$dados[0];
	$mailrecomendante=$dados[1];
	$senha=gera_senha();
	$inclui_login=inclusao_login_recomendante($mailrecomendante,$senha);
	if ($inclui_login=="gravado") $manda_mail=manda_mail_recomendante($mailrecomendante,$senha,$id_aluno,$programa);
	}


pg_close($con);
?>
</body>
</html>
