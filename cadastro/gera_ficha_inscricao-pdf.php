<?php
define('FPDF_FONTPATH', 'font/');
require('fpdf/fpdf.php');
require('fpdi/fpdi.php'); 

include_once("../pgsql/pgsql.php"); 

include_once("../config/config.php");


	

	$pdf=new FPDI('P','cm','A4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetTitle(utf8_decode('Ficha de Inscrição'));

	//Set font and colors
	$pdf->SetFont('Arial', '', 12);
	//$pdf->SetFillColor(255, 0, 0);
	//$pdf->SetTextColor(255);
	//$pdf->SetDrawColor(128, 0, 0);
	//$pdf->SetLineWidth(.3);

	//Table header
	//$pdf->Cell(20, 10, 'coduser', 1, 0, 'L', 1);
	//$pdf->Cell(50, 10, 'Login', 1, 0, 'L', 1);
	//$pdf->Cell(50, 10, 'Status', 1, 1, 'L', 1);

	//Restore font and colors
	$pdf->SetFont('Arial', '', 10);
	//$pdf->SetFillColor(224, 235, 255);
	$pdf->SetTextColor(0);
			
			
	$coduser=$_SESSION['coduser'];
	//$coduser='592';

	$id_aluno = $_SESSION['coduser'];

	$query_cadas1 = pg_query("select * from inscricao_pos_dados_candidato where id_aluno='".$coduser."'");
	$cadas1 = pg_fetch_assoc($query_cadas1);
	
	$query_cadas2 = pg_query("select * from inscricao_pos_dados_profissionais_candidato where id_aluno='".$coduser."' and edital='".$edital_atual."'");
	$cadas2 = pg_fetch_assoc($query_cadas2);
	
	$query_cadas3 = pg_query("select * from inscricao_pos_contatos_recomendante where id_aluno='".$coduser."' and edital='".$edital_atual."'");
	$cadas3 = pg_fetch_assoc($query_cadas3);
	
	$query_carta_motivacao = pg_query("select * from inscricao_pos_carta_motivacao where id_aluno='".$coduser."' and edital='".$edital_atual."'");
	$cadas31 = pg_fetch_assoc($query_carta_motivacao);
	
		
	$query_arquivos=pg_query("select * from inscricao_pos_anexos where coduser='".$coduser."'");

	// Arrumando os nomes dos estados
	$ufnatura = explode("_",$cadas1['ufnaturalidade']);
	$cadas1['ufnaturalidade'] = $ufnatura[1];

	$ufend = explode("_",$cadas1['ufendereco']);
	$cadas1['ufendereco'] = $ufend[1];

	$estaoemissao = explode("_",$cadas1['estadoemissaoid']);
	$cadas1['estadoemissaoid'] = $ufend[1];


	// Arrumando e-mails

	$cadas1['mail1'] = str_replace('_','\_',$cadas1['mail1']);
	$cadas1['mail2'] = str_replace('_','\_',$cadas1['mail2']);
	$cadas3['emailprofrecomendante1'] = str_replace('_','\_',$cadas3['emailprofrecomendante1']);
	$cadas3['emailprofrecomendante2'] = str_replace('_','\_',$cadas3['emailprofrecomendante2']);
	$cadas3['emailprofrecomendante3'] = str_replace('_','\_',$cadas3['emailprofrecomendante3']);
	//echo $cadas1['mail1']."<br>".$cadas1['mail2'];


	$arquivopdf = str_replace(' ','',$cadas3['programa'])."-".$coduser.md5($cadas1['name']);

	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20, 0.5, utf8_decode('Ficha de Inscrição'),0,1,'C');	
	$texto = ucwords(strtolower($cadas1['name']))." ".ucwords(strtolower($cadas1['firstname']))." - ".$cadas3['programa'];
	$pdf->Cell(20, 0.5, utf8_decode($texto),0,1,'C');	
	$pdf->SetFont('Arial', '', 10);

	$nascimento= "Data de Nascimento: ".$cadas1['dianascimento']."/".$cadas1['mesnascimento']."/".$cadas1['anonascimento']." Idade: ".$idade. " Sexo: ".$cadas1['sexo'];
		
	$naturalidade = "Naturalidade: ".ucwords(strtolower($cadas1['naturalidade'])). " Estado: ".$cadas1['ufnaturalidade'];
	
	$nacionalidade = "Nacionalidade: ".ucwords(strtolower($cadas1['nacionalidade'])). " País: ".ucwords(strtolower($cadas1['paisnacionalidade']));
	
	$pai = "Nome do pai: ".ucwords(strtolower($cadas1['nome_pai']));
	
	$mae = "Nome da mãe: ".ucwords(strtolower($cadas1['nome_mae']));
	
	$pdf->Cell(20,0.5,'',0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($nascimento),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($naturalidade),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($nacionalidade),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($pai),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($mae),0,1,'L');

	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20,1,utf8_decode('Endereço Pessoal'),0,1,'L');
	$pdf->SetFont('Arial', '', 10);

	$endereco = "Endereço residencial: ".ucwords(strtolower($cadas1['adresse']))." CEP: ".$cadas1['cpendereco'];
	$complementoendereco = "Cidade: ".ucwords(strtolower($cadas1['cityendereco']))." Estado: ".$cadas1['ufendereco']." País: ".ucwords(strtolower($cadas1['country']));
    $telefonecomercial = "Telefone comercial: +".$cadas1['ddi_phonework']."(".$cadas1['ddd_phonework'].")".$cadas1['phonework'];
    $telefoneresidencial = "Telefone residencial: +".$cadas1['ddi_phonehome']."(".$cadas1['ddd_phonehome'].")".$cadas1['phonehome'];
    $telefonecelular = "Telefone celular: +".$cadas1['ddi_cel']."(".$cadas1['ddd_cel'].")".$cadas1['telcelular'];
    $emailprincipal = "E-mail principal: ".strtolower($cadas1['mail1']);
 	$emailalternativo = "E-mail alternativo: ".strtolower($cadas1['mail2']);

 	$pdf->Cell(20,0.5,utf8_decode($endereco),0,1,'L');
 	$pdf->Cell(20,0.5,utf8_decode($complementoendereco),0,1,'L');
	$pdf->Cell(20,0.5,$telefonecomercial,0,1,'L');
	$pdf->Cell(20,0.5,$telefoneresidencial,0,1,'L');
	$pdf->Cell(20,0.5,$telefonecelular,0,1,'L');
	$pdf->Cell(20,0.5,$emailprincipal,0,1,'L');
	$pdf->Cell(20,0.5,$emailalternativo,0,1,'L');

	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20,1,'Documentos Pessoais',0,1,'L');
	$pdf->SetFont('Arial', '', 10);

	$cpf = "Número de CPF: ".$cadas1['cpf'];
    $identidade = "Número de Identidade (ou Passaporte para estrangeiros): ".$cadas1['identity']." Orgão emissor: ".$cadas1['id_emissor'];
    $estadoemissaoid = "Estado: ".$cadas1['estadoemissaoid'].
    " Data de emissão:".$cadas1['diaemissaoid']."/".$cadas1['mesemissaoid']."/".$cadas1['anoemissaoid'];

    $pdf->Cell(20,0.5,utf8_decode($cpf),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($identidade),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($estadoemissaoid),0,1,'L');

	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20,1,utf8_decode('Grau acadêmico mais alto obtido'),0,1,'L');
	$pdf->SetFont('Arial', '', 10);

 	$curso = "Curso: ".ucwords(strtolower($cadas2['instrucaocurso']))." Grau: ".ucwords(strtolower($cadas2['instrucaograu'])).
	" Instituição: ".ucwords(strtolower($cadas2['instrucaoinstituicao']));
    $anoconclusao = "Ano de Conclusão ou Previsão: ".$cadas2['instrucaoanoconclusao'];
 	$experiencia = "Experiência Profissional mais recente. Tem experiência: ".$cadas2['experienciatipo1']." ".$cadas2['experienciatipo2'];
 	$instituiçãoexperiencia = "Instituição: ".ucwords(strtolower($cadas2['experienciainstituicao']));
 	$periodo = "Período - início: ".$cadas2['experienciaperiodoiniciosemestre']."-".$cadas2['experienciaperiodoinicioano'].
	" fim: ".$cadas2['experienciaperiodofimsemestre']."-".$cadas2['experienciaperiodofimano'];

	$pdf->Cell(20,0.5,utf8_decode($curso),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($anoconclusao),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($experiencia),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($instituiçãoexperiencia),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($periodo),0,1,'L');

	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20,1,'Programa',0,1,'L');
	$pdf->SetFont('Arial', '', 10);

	
	$programa = "Programa Pretendido: ".$cadas3['programa'];
	$pdf->Cell(20,0.5,utf8_decode($programa),0,1,'L');
	if ($cadas2['cursopos']=="Doutorado") {
		if ($cadas2['areadoutorado']=="" or $cadas2['areadoutorado']=="0" or $cadas2['areadoutorado']=="nselecionado") {
		}else{
			$area = "Área: ".$cadas2['areadoutorado'];
			$pdf->Cell(20,0.5,utf8_decode($area),0,1,'L');			
		}
	}

	if ($cadas2['cursopos']=="0" or $cadas2['verao']=="" or $cadas2['verao']=="sim") {
		$verao = "Curso de Verão: ".$cadas2['cursoverao'];
		$pdf->Cell(20,0.5,utf8_decode($verao),0,1,'L');
	}

	$bolsa = "Interesse em bolsa: ".$cadas2['interessebolsa'];
    $pdf->Cell(20,1,'Dados dos Recomendantes:',0,1,'L');

    $recomendante1 = "1-Nome: ".ucwords(strtolower($cadas3['nomeprofrecomendante1']))." E-mail: ".strtolower($cadas3['emailprofrecomendante1']);
	$recomendante2 = "2-Nome: ".ucwords(strtolower($cadas3['nomeprofrecomendante2']))." E-mail: ".strtolower($cadas3['emailprofrecomendante2']);
	$recomendante3 = "3-Nome: ".ucwords(strtolower($cadas3['nomeprofrecomendante3']))." E-mail: ".strtolower($cadas3['emailprofrecomendante3']);

	$pdf->Cell(20,0.5,utf8_decode($recomendante1),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($recomendante2),0,1,'L');
	$pdf->Cell(20,0.5,utf8_decode($recomendante3),0,1,'L');

	$motivacao = $cadas31['motivacaoprogramapretendido'];
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20,1,utf8_decode('Motivação e expectativa do candidato em relação ao programa pretendido:'),0,1,'L');
	$pdf->SetFont('Arial', '', 10);

	$pdf->MultiCell(17,0.5,utf8_decode($motivacao));

	//$pdf->AddPage();
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(20,1,utf8_decode('Documentos'),0,1,'C');
	$pdf->SetFont('Arial', '', 10);
	$pdf->AddPage();

	$query_arquivos=pg_query("select * from inscricao_pos_anexos where coduser='".$coduser."'");
	$contapagina=0;
	$num_paginas = pg_num_rows($query_arquivos);
		
	while($registro=pg_fetch_row($query_arquivos)){
			if ($registro[2]!=""){
				$ext = pathinfo($registro[2], PATHINFO_EXTENSION);
				if ($ext =="pdf"){
					$localimagem = "../upload/".$registro[2];
					$pagecount = $pdf->setSourceFile($localimagem);
					for ($i=1; $i < $pagecount + 1; $i++) {
						$tplidx = $pdf->importPage($i); 
						$pdf->useTemplate($tplidx, 0, 0, 0, 0, true); 
						if ($i <> $pagecount + 1) {
							$pdf->AddPage();
						}
					}
				}else{
					 if ( ($ext=="jpeg") or ($ext=="jpg") or ($ext=="png") ){
					 	$localimagem = "../upload/".$registro[2];
						$pdf->Image($localimagem, $pdf->GetX(), $pdf->GetY(), 15);
						if ($contapagina <> $num_paginas - 1) {
							$pdf->AddPage();
				
						}
					 }
				} 
				}else{
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->Cell(20,1,utf8_decode('Faltam documentos obrigatórios!'),0,1,'L');
					$pdf->SetFont('Arial', '', 10);
				}
			$contapagina++;
		}

$localarquivo = "../ficha_inscricao/".$arquivopdf.'.pdf';
$pdf->Output($localarquivo,'F');
?>
