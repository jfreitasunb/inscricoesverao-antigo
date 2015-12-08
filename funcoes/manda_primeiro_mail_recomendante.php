<?php 
include_once("../config/config.php");

function manda_primeiro_mail_recomendante($mail_recomendante,$senha_recomendante,$id_aluno,$programa,$data_limite)
{
	
$query_busca=pg_query("select name||' '||firstname as nome, cr.* 
						from inscricao_pos_dados_candidato dc,inscricao_pos_contatos_recomendante cr 
						where 
						cr.id_aluno=dc.id_aluno and dc.id_aluno='".$id_aluno."' and cr.programa='".$programa."'");

$dados=pg_fetch_row($query_busca);
//print_r($dados);
for ($i=4;$i<9;$i=$i+2)
	{
		if ($dados[$i]==$mail_recomendante) {
			$nome_recomendante=$dados[$i-1];
		}
	}

if (($nome_recomendante!="") and ($mail_recomendante!=""))
	{
	$nome_aluno=$dados[0];
	
	$texto="Prezado(a) Prof(a).".$nome_recomendante.",\n Para poder enviar a carta de recomendação do(a) aluno(a) ".$nome_aluno." para inscrição no Programa de ".$programa." do MAT/UnB, solicitamos que acesse o link http://www.mat.unb.br/inscricoesverao \n Seu login é : ".$mail_recomendante." \n Sua senha é: ".$senha_recomendante."\n O envio desta carta pode ser feito até o dia ".$data_limite." \n Antecipadamente agradecemos, \n Coordenação de Verão do MAT/UnB."; 
	
	$texto=wordwrap($texto,70);

	// mensagem:
	
	$subject = "Dados de acesso para recomendação do(a) aluno(a) ".$nome_aluno." para o Programa de ".$programa." do MAT/UnB";
	$headers = "FROM: verao@mat.unb.br";

	//echo $mail_recomendante.";\n".$subject." ;\n ".$texto." ;\n".$headers."<br>";
	$res_mail=mail($mail_recomendante, mb_convert_encoding($subject,'ISO-8859-1','UTF-8'), mb_convert_encoding($texto,'ISO-8859-1','UTF-8'), $headers);
	if ($res_mail) $devolve="mensagem enviada"; else $devolve="problema no envio de mensagens";
	}
	else $devolve="Nao ha nome ou mail do recomendante";
return $devolve;

}

?>
