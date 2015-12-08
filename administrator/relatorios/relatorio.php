<?php

include_once("../../pgsql/pgsql.php");
include_once("../../config/config.php");
$arquivospdf = array();
$contapdf = 0;
$dir_atual = "edital-".$edital_atual;

$caminho_diretorio = "/home/PAGINAS/WWW/site-mat/inscricoesverao/administrator/relatorios/".$dir_atual."/";
//$caminho_diretorio = "/Arquivos/Dropbox/php/vagrant/rivendel/www/inscricoesverao/administrator/relatorios/".$dir_atual."/";
// Gerando a lista de codusers a partir da tabela finaliza
//exec("cd ".$caminho_diretorio."; pwd;");	

	if (!file_exists($dir_atual) and !is_dir($dir_atual)) {
    	mkdir($dir_atual, 0777);
    	exec("cp -R base-relatorios/* ".$dir_atual."");
    	exec("chmod 777 ".$dir_atual."");
    	//exec("cd /paginas/WWW/site-mat/inscricoespos/relatorios/edital-2-2014/; pwd; chmod 777 ".$arquivotex.".tex; pdflatex ".$arquivotex."; rm ".$arquivotex." *.log *.aux");
	}
exec("cd ".$caminho_diretorio."/; pwd;");
$query_coduser_finaliza = pg_query("select coduser from inscricao_pos_finaliza where edital='$edital_atual' and coduser <> '110' and coduser <> '592' and coduser <> '564' and coduser <> '210' and coduser <> '1264' and coduser <> '561' and coduser <> '618' and coduser <> '1434' and coduser <> '1435'");
$tab_coduser = pg_fetch_all($query_coduser_finaliza);
//var_dump($tab_coduser);
$num_linhas = pg_num_rows($query_coduser_finaliza);

for ($i=0;$i < $num_linhas;$i++){
echo "<hr>Inscri&ccedil&atildeo: ".$i."<hr>";

		$coduser=$tab_coduser[$i]['coduser'];
		//$coduser="1512";

		$query_cadas1 = pg_query("select * from inscricao_pos_dados_candidato where id_aluno='".$coduser."'");
		$cadas1 = pg_fetch_assoc($query_cadas1);
		
		$query_cadas2 = pg_query("select * from inscricao_pos_dados_profissionais_candidato where id_aluno='".$coduser."' and edital='$edital_atual'");
		$cadas2 = pg_fetch_assoc($query_cadas2);
		
		$query_cadas3 = pg_query("select * from inscricao_pos_contatos_recomendante where id_aluno='".$coduser."' and edital='$edital_atual'");
		$cadas3 = pg_fetch_assoc($query_cadas3);
		
		$query_carta_motivacao = pg_query("select * from inscricao_pos_carta_motivacao where id_aluno='".$coduser."' and edital='$edital_atual'");
		$cadas31 = pg_fetch_assoc($query_carta_motivacao);
		
			
			
		$query_arquivos=pg_query("select * from inscricao_pos_anexos where coduser='".$coduser."'");

// Arrumando os nomes dos estados
$ufnatura = explode("_",$cadas1['ufnaturalidade']);
$cadas1['ufnaturalidade'] = $ufnatura[1];

$ufend = explode("_",$cadas1['ufendereco']);
$cadas1['ufendereco'] = $ufend[1];

$estaoemissao = explode("_",$cadas1['estadoemissaoid']);
$cadas1['estadoemissaoid'] = $ufend[1];

// Coloca o nome em formato padrão - Título (primeira letra de cada string maíuscula)
$nome =$cadas1['name']." ".$cadas1['firstname'];
$nome = ucwords(strtolower($nome));
$nome =str_replace(' ','',$nome);
echo "<br> $nome";

// Calcula Idade
$data_nascimento = $cadas1['anonascimento']."-".$cadas1['mesnascimento']."-".$cadas1['dianascimento'];
if ($data_nascimento == "--"){ 
								$idade="N Info";
							}else{ 
								$idade = floor( (strtotime(date('Y-m-d')) - strtotime($data_nascimento)) / 31556926);
							}
// Arrumando e-mails

$cadas1['mail1'] = str_replace('_','\_',$cadas1['mail1']);
$cadas1['mail2'] = str_replace('_','\_',$cadas1['mail2']);
//echo $cadas1['mail1']."<br>".$cadas1['mail2'];



//$arquivotex = str_replace(' ','',$cadas3['programa'])."-".$coduser."-".$nome;

if ($cadas2['cursoverao'] == "Introdução a Topologia Geral" or $cadas2['cursoverao'] == "Topologia Geral") {
	$cadas2['cursoverao'] = "Introdução à Topologia Geral";
	$nome_arquivo = "Topologia_Geral";
}else{
	$cadas2['cursoverao'] = "Variáveis Complexas II";
	$nome_arquivo = "Variaveis_Complexas_II";;
}	

$arquivotex = $nome_arquivo."-".$cadas1['ufendereco']."-".$coduser."-".$nome;


// Velho fo funciona na outra pasta $fo = fopen('../ficha_inscricao/'.$arquivotex.'.tex', 'w') or die("Nao foi possivel abrir o arquivo.");
$fo = fopen($dir_atual."/".$arquivotex.'.tex', 'w') or die("Nao foi possivel abrir o arquivo.");

$nome = $cadas1['name']." ".$cadas1['firstname'];


$textotex = "\\documentclass[11pt]{article}
\\usepackage{graphicx,color}
\\usepackage{pdfpages}
\\usepackage[brazil]{babel}
\\usepackage[utf8]{inputenc}
\\addtolength{\\hoffset}{-3cm} \\addtolength{\\textwidth}{6cm}
\\addtolength{\\voffset}{-.5cm} \\addtolength{\\textheight}{1cm}
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  To use Colors 
\\title{\\vspace*{-4cm} Ficha de Inscrição: \\\\";
$textotex .="Cod: ".$coduser."\\ \\ ";
$textotex .= $cadas1['name']." ".$cadas1['firstname'];
//$textotex .="\\ \\ - \\ \\ ".$cadas3['programa'];
$textotex .=" 
 }
\\date{}

\\begin{document}
\\maketitle
\\vspace*{-1.5cm}
\\noindent Data de Nascimento: ".$cadas1['dianascimento']."/".$cadas1['mesnascimento']."/".$cadas1['anonascimento']."
\\ \\ \\ Idade: ".$idade."   \\ \\ \\ Sexo: ".$cadas1['sexo']."
\\\\
Naturalidade: ".$cadas1['naturalidade']."  
\\ \\ \\  Estado: ".$cadas1['ufnaturalidade']."
\\ \\ \\  Nacionalidade: ".$cadas1['nacionalidade']."
\\ \\ \\ País: ".$cadas1['paisnacionalidade']."
\\\\        
Nome do pai: ".$cadas1['nome_pai']."
\\ \\ \\ Nome da mãe: ".$cadas1['nome_mae']."          
\\\\[0.2cm]                     
\\textbf{Endereço Pessoal} 
\\\\ 
\\noindent Endereço residencial: ".$cadas1['adresse']."
\\\\
        CEP: ".$cadas1['cpendereco']." 
\\ \\ \\ Cidade: ".$cadas1['cityendereco']." 
\\ \\ \\ Estado: ".$cadas1['ufendereco']." 
\\ \\ \\ País: ".$cadas1['country']."
\\\\		
		Telefone comercial: +".$cadas1['ddi_phonework']."(".$cadas1['ddd_phonework'].")".$cadas1['phonework']."
\\ \\ \\ Telefone residencial: +".$cadas1['ddi_phonehome']."(".$cadas1['ddd_phonehome'].")".$cadas1['phonehome']."
\\ \\ \\ Telefone celular: +".$cadas1['ddi_cel']."(".$cadas1['ddd_cel'].")".$cadas1['telcelular']."
\\\\
E-mail principal: ".$cadas1['mail1']."
\\ \\ \\ E-mail alternativo: ".$cadas1['mail2']." 
\\\\[0.2cm] 
\\textbf{Documentos Pessoais}
\\\\
\\noindent Número de CPF: ".$cadas1['cpf']."
\\ \\ \\ Número de Identidade (ou Passaporte para estrangeiros): ".$cadas1['identity']."
\\\\
Orgão emissor: ".$cadas1['id_emissor']."
\\ \\ \\ Estado: ".$cadas1['estadoemissaoid']."
\\ \\ \\ Data de emissão: ".$cadas1['diaemissaoid']."/".$cadas1['mesemissaoid']."/".$cadas1['anoemissaoid']."
\\\\[0.3cm]
\\textbf{Grau acadêmico mais alto obtido}
\\\\	
Curso: ".$cadas2['instrucaocurso']."
\\ \\ \\ Grau: ".$cadas2['instrucaograu']."
\\ \\ \\ Instituição: ".$cadas2['instrucaoinstituicao']."
\\\\			
Ano de Conclusão ou Previsão: ".$cadas2['instrucaoanoconclusao']."
\\\\ 
Experiência Profissional mais recente. \\ \\  
Tem experiência: ".$cadas2['experienciatipo1']." ".$cadas2['experienciatipo2']."  
\\ \\ \\ Instituição: ".$cadas2['experienciainstituicao']."
\\\\  
Período - início: ".$cadas2['experienciaperiodoiniciosemestre']."-".$cadas2['experienciaperiodoinicioano']."
\\ \\ \\ fim: ".$cadas2['experienciaperiodofimsemestre']."-".$cadas2['experienciaperiodofimano']."
\\\\[0.2cm] 
\\textbf{Curso de Verão:} ".$cadas2['cursoverao'];

$textotex .="\\\\
Interesse em bolsa: ".$cadas2['interessebolsa'];


// Esta query esta repetida. Verificar se realmente precisamos dela
$query_arquivos=pg_query("select * from inscricao_pos_anexos where coduser='".$coduser."' order by data DESC");

while($registro=pg_fetch_row($query_arquivos)){
				
				if ($registro[2]!=""){
					$ext = pathinfo($registro[2], PATHINFO_EXTENSION);
					if ($ext =="pdf"){
						//$textotex .= "\\includepdf[pages={-},offset=35mm 0mm]{/Arquivos/Dropbox/php/vagrant/rivendel/www/inscricoesverao/upload/".$registro[2]."}";
						$textotex .= "\\includepdf[pages={-},offset=35mm 0mm]{/home/PAGINAS/WWW/site-mat/inscricoesverao/upload/".$registro[2]."}";

					}else{ if ( ($ext=="jpeg") or ($ext=="jpg") or ($ext=="png") ){ 
							
								$textotex .="	
\\begin{figure}[!htb]
\\includegraphics{/home/PAGINAS/WWW/site-mat/inscricoesverao/upload/".$registro[2]."}
\\end{figure}";
							}
					} 

				}else{ $textotex .="\\\\ \\textbf{Faltam documentos obrigatórios.}\\\\";}
			
			
}

$textotex .=" 
\\begin{center}
Anexos.
\\end{center}
\\end{document}";

//$textotex = utf8_decode($textotex);
//echo $textotex;
fwrite($fo, $textotex);
echo "<br><br><br><br><br> $textotex";
fclose($fo);
//sleep(10);

$arquivospdf[] = $arquivotex;

//$erropdf = fopen("/vagrant/www/inscricoespos/administrator/relatorios/".$dir_atual."/erropdf.txt","wb");
//exec("cd /paginas/WWW/site-mat/inscricoespos/relatorios/edital-2-2014/; pwd; chmod 777 ".$arquivotex.".tex; pdflatex ".$arquivotex."; rm ".$arquivotex." *.log *.aux");
exec("cd ".$caminho_diretorio."; pwd; chmod 777 ".$arquivotex.".tex; pdflatex ".$arquivotex." .tex");

// Primeiro vamos ter certeza de que o arquivo existe e pode ser alterado

//echo $resultadocompila;
//exec('pdflatex ../ficha_inscricao/'.$arquivotex);
}
echo "<hr> Cheguei no final do arquivo";


sleep(300);

$filename = $caminho_diretorio."erropdf.txt";
if (is_writable($filename)) {

 // Em nosso exemplo, nós vamos abrir o arquivo $filename
 // em modo de adição. O ponteiro do arquivo estará no final
 // do arquivo, e é pra lá que $conteudo irá quando o 
 // escrevermos com fwrite().
    if (!$handle = fopen($filename, 'a')) {
         echo "Não foi possível abrir o arquivo ($filename)";
         exit;
    }

    // Escreve $conteudo no nosso arquivo aberto.
    foreach ($arquivospdf as $key) {
    	if (!file_exists($caminho_diretorio.$key.".pdf")) {
    		$contapdf = $contapdf + 1;
    		if (fwrite($handle, $key.".tex\n") === FALSE) {
        		echo "Não foi possível escrever no arquivo ($filename)";
        		exit;
    		}
    	}
    }
    fclose($handle);

} else {
    echo "O arquivo $filename não pode ser alterado";
}


echo '<h2 align="center"> <span style="color:#FF0000">Houveram '.$num_linhas.' inscri&ccedil;&otilde;es para o edital '.$edital_atual.'.<br>
Desse total, existem '.$contapdf.' arquivos pdf que n&atilde;o foram gerados.<br>
Verifique o arquivo erropdf.txt no diret&oacute;rio '.$caminho_diretorio.' para 
saber quais est&atilde;o faltando!</span></h2>';

//echo "Verifique o arquivo erropdf.txt no diretório ".$caminho_diretorio."para verificar se existe algum pdf que não foi gerado!";

?>
