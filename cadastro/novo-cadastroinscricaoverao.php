<?php
	session_start();
	include_once("../config/config.php");
	if( !isset($_SESSION['coduser']) ){
		echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=../index.php'>";
		exit;
	}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<link rel="stylesheet" type="text/css" href="../css/common-stylesheet.css" />
	</head>
	
	<script type="text/javascript" src="../js/LiveValidation.js"></script>
	<script type="text/javascript" src="../js/remove_caracteres.js"></script>
	
	
	<body style="background-image: url('../imagens/bg_pixel.png'); padding: 20px">
		<h2 align="center">
			Cadastro para Processo Seletivo 
			<?php
			echo $curso_config;
			?>
			/ Pós-Graduação 
			<br>
			MAT-UnB 
			<?php echo $ano_config;?>
		</h2>
		
		
		
		<?php
		include_once("../config/config.php");


		if ( !(isset( $_SESSION['resvalcad2']))){ 
			for ($i=0; $i
				<15; $i++ ){ $resultadovalidacadastro2[$i]=1;} 
		}	else{
			$resultadovalidacadastro2 = $_SESSION['resvalcad2'];
		}



		if ( !(isset( $_SESSION['resvalcad3']))){ 
					for ($i=0; $i<8; $i++ ){ $resultadovalidacadastro3[$i]=1;} 
				}	else{
						$resultadovalidacadastro3 = $_SESSION['resvalcad3'];
					}

		

		if ( !(isset ($_SESSION['repopula2'])) and !(isset ($_SESSION['repopula3'])) ){

			include_once("../pgsql/pgsql.php");

			$coduser =$_SESSION['coduser'];
			$query_pesca_cadas2 = pg_query("select * from inscricao_pos_dados_profissionais_candidato 
				where id_aluno='$coduser' and edital='$edital_atual' " );
			$num_resultados_query_pesca_cadas2_edital_novo = pg_num_rows($query_pesca_cadas2);

			

			$query_pesca_cadas31 = pg_query("select * from inscricao_pos_contatos_recomendante 
														where id_aluno='$coduser' and edital='$edital_atual' " );
			
			$num_resultados_query_pesca_cadas31_edital_novo = pg_num_rows($query_pesca_cadas31);

			
			
			if ($num_resultados_query_pesca_cadas2_edital_novo == 1){

				$repop2_provisorio = pg_fetch_assoc($query_pesca_cadas2);

			}else{
				$query_pesca_cadas2_velho = pg_query("select * from inscricao_pos_dados_profissionais_candidato 
					where id_aluno='$coduser' and edital='$edital_anterior' " );
				$num_resultados_query_pesca_cadas2_edital_velho = pg_num_rows($query_pesca_cadas2_velho);

				if ($num_resultados_query_pesca_cadas2_edital_velho == 1){
					$repop2_provisorio = pg_fetch_assoc($query_pesca_cadas2_velho);
				}
			}
			


			if ($num_resultados_query_pesca_cadas31_edital_novo == 1){
											
							$repop3_provisorio = pg_fetch_assoc($query_pesca_cadas31);
							
						}else{
							$query_pesca_cadas31_velho = pg_query("select * from inscricao_pos_contatos_recomendante 
														where id_aluno='$coduser' and edital='$edital_anterior'" );
							$num_resultados_query_pesca_cadas31_edital_velho = pg_num_rows($query_pesca_cadas31_velho);
							
							if ($num_resultados_query_pesca_cadas31_edital_velho == 1){
								$repop3_provisorio = pg_fetch_assoc($query_pesca_cadas31_velho);
							}
						}

						$query_pesca_cadas32 = pg_query("select * from inscricao_pos_carta_motivacao 
														where id_aluno='$coduser' and edital='$edital_atual'");
						$repop_motivacao = pg_fetch_array($query_pesca_cadas32);
						
						//usa o banco para fazer a repopulacao dos dados
						$repop3["NomeProfRecomendante1"]= $repop3_provisorio['nomeprofrecomendante1'];
						$repop3["NomeProfRecomendante2"]= $repop3_provisorio['nomeprofrecomendante2'];
						$repop3["NomeProfRecomendante3"]= $repop3_provisorio['nomeprofrecomendante3'];
						$repop3["EmailProfRecomendante1"]= $repop3_provisorio['emailprofrecomendante1'];
						$repop3["EmailProfRecomendante2"]= $repop3_provisorio['emailprofrecomendante2'];
						$repop3["EmailProfRecomendante3"]= $repop3_provisorio['emailprofrecomendante3'];
						$repop3["MotivacaoProgramaPretendido"] = $repop_motivacao['motivacaoprogramapretendido'];


						//usa o banco para fazer a repopulacao dos dados
						$repop2["InstrucaoCurso"]= $repop2_provisorio['instrucaocurso'];
						$repop2["InstrucaoGrau"]= $repop2_provisorio['instrucaograu'];
						$repop2["InstrucaoInstituicao"]= $repop2_provisorio['instrucaoinstituicao'];
						$repop2["InstrucaoAnoConclusao"]= $repop2_provisorio['instrucaoanoconclusao'];
						$repop2["ExperienciaTipo1"]= $repop2_provisorio['experienciatipo1'];
						$repop2["ExperienciaTipo2"]= $repop2_provisorio['experienciatipo2'];
						$repop2["ExperienciaInstituicao"]= $repop2_provisorio['experienciainstituicao'];
						$repop2["ExperienciaPeriodoInicioSemestre"]= $repop2_provisorio['experienciaperiodoiniciosemestre'];
						$repop2["ExperienciaPeriodoInicioAno"]= $repop2_provisorio['experienciaperiodoinicioano'];
						$repop2["ExperienciaPeriodoFimSemestre"]= $repop2_provisorio['experienciaperiodofimsemestre'];
						$repop2["ExperienciaPeriodoFimAno"]= $repop2_provisorio['experienciaperiodofimano'];
						$repop2["CursoPos"]= $repop2_provisorio['cursopos'];
						$repop2["AreaDoutorado"]= $repop2_provisorio['areadoutorado'];
						$repop2["InteresseBolsa"]= $repop2_provisorio['interessebolsa'];
						$repop2["Verao"] = $repop2_provisorio['verao'];
						$repop2["CursoVerao"] = $repop2_provisorio['cursoverao'];

						
					}else{
						$repop2 = $_SESSION['repopula2'];
						$repop3 = $_SESSION['repopula3'];
					}				
					//var_dump($repop2);
		 ?>

		
		
		
		<?php 
		include_once("../pgsql/pgsql.php");
		$coduser =$_SESSION['coduser'];
		$query_pesca_cadas2 = pg_query("select * from inscricao_pos_dados_profissionais_candidato 
			where id_aluno='$coduser'");
		$result_pesca_cadas2 =pg_num_rows($query_pesca_cadas2);

		$query_pesca_cadas3 = pg_query("select * from inscricao_pos_contatos_recomendante 
														where id_aluno='$coduser'");
		$result_pesca_cadas3 = pg_num_rows($query_pesca_cadas3);
				

		if(  (isset( $_SESSION['resvalcad2'])) and ($result_pesca_cadas2 == 0) and (isset( $_SESSION['resvalcad3'])) and ($result_pesca_cadas3 == 0)   ){
			echo '<h2 align="center"> <span style="color:#FF0000">
					Atenção !Alguns campos obrigatórios não foram preenchido ou 
					<br>
					caracteres inválidos como $?%,!# e etc... foram usados. 
					<br> Todos os campos que precisam ser corrigidos ou preenchidos estão destacados em vermelho.
					</span>
					</h2>';
		}
			
		?>
		
		
		<form method="POST" action="novo-processacadastro.php" 
		onsubmit="return window.confirm(&quot;Confirma o envio deste formulário.\n para o MAT-UnB?&quot;);">
		
		<b>
			Grau acadêmico mais alto obtido:
		</b>

		<p>
			<span style=" 
			<?php if ($resultadovalidacadastro2[0] == 1){
				echo"";
			}else{
				echo "color:#FF0000; font-weight:bold";}
				?>">
				Curso (Matemática, Física, etc.):
			</span>
			<input autofocus type="text" id="graduacao" name="cadas2[InstrucaoCurso]" value="<?php echo $repop2["InstrucaoCurso"];?>" onkeyup="valida_caracteres(this)" 
            onblur="valida_caracteres(this)" size="40" maxlength="60">
			<br>
			
			<script type="text/javascript">
			var graduacao = new LiveValidation('graduacao');
			graduacao.add(Validate.Presence, {failureMessage: "Não pode ser vazio!"});
			</script>
			
			<span style=" 
			<?php if ($resultadovalidacadastro2[1] == 1){
				echo"";
			}else{
				echo "color:#FF0000; font-weight:bold";}
				?>
				">
				
				Grau :
			</span>


			<select name="cadas2[InstrucaoGrau]" size="1">
				<option  value="nselecionado">
					Selecione uma opção:
				</option>
				<option value="doutor" 
				<?php if ($repop2 ["InstrucaoGrau"]== "doutor" ){echo"selected";}?>
				>
				Doutor
			</option>
			<option value="mestre" 
			<?php if ($repop2 ["InstrucaoGrau"]== "mestre" ){echo"selected";}?>
			>
			Mestre
		</option>
		<option value="especialista" 
		<?php if ($repop2 ["InstrucaoGrau"]== "especialista" ){echo"selected";}?>
		>
		Especialista
	</option>
	<option value="bacharel" 
	<?php if ($repop2 ["InstrucaoGrau"]== "bacharel" ){echo"selected";}?>
	>
	Bacharel
</option>
<option value="licenciado" 
<?php if ($repop2 ["InstrucaoGrau"]== "licenciado" ){echo"selected";}?>
>
Licenciado
</option>
<option value="outro" 
<?php if ($repop2 ["InstrucaoGrau"]== "outro" ){echo"selected";}?>
>
Outro
</option>
</select>
<br>


<span style=" 
<?php if ($resultadovalidacadastro2[2] == 1){
	echo"";
}else{
	echo "color:#FF0000; font-weight:bold";}
	?>
	">
	Instituição :
</span>


<input type="text" id="instituicao" name="cadas2[InstrucaoInstituicao]" value="<?php echo $repop2["InstrucaoInstituicao"];?>
" onkeyup="valida_caracteres(this)" onblur="valida_caracteres(this)" size="70" maxlength="90">
<br>

<script type="text/javascript">
var instituicao = new LiveValidation('instituicao');
instituicao.add(Validate.Presence, {
	failureMessage: "Não pode ser vazio!"});
</script>

<span style=" 
<?php if ($resultadovalidacadastro2[3] == 1){
	echo"";
}else{
	echo "color:#FF0000; font-weight:bold";}
	?>
	">
	Ano de Conclusão ou Previsão:
</span>


<select name="cadas2[InstrucaoAnoConclusao]" >
	<?php
	$ano_candidato;
	echo '
	<option  value="nselecionado">
	------
	</option>
	';
	do
	{
		echo '
		<option value='.$ano_candidato; if ($repop2["InstrucaoAnoConclusao"]== $ano_candidato ){echo" selected" ;} echo">
		".$ano_candidato."
		</option>
		";
		$ano_candidato--;
	} while ($ano_candidato>1980);
	?>
</select>



<p>

	<b>
		Experiência Profissional mais recente (se for o caso):
	</b>
</p>

<input type="checkbox" name="cadas2[ExperienciaTipo1]" value="Docente" <?php if ( isset($repop2["ExperienciaTipo1"])){echo "checked";} ?>>Docente &nbsp &nbsp
<input type="checkbox" name="cadas2[ExperienciaTipo2]" value="Discente" <?php if ( isset($repop2["ExperienciaTipo2"])){echo "checked";} ?>>Discente 

<br>

Instituição: 
<input type="text" name="cadas2[ExperienciaInstituicao]" value="<?php if ($repop2["ExperienciaInstituicao"]!="0"){echo $repop2["ExperienciaInstituicao"];}?>
" onkeyup="valida_caracteres(this)" onblur="valida_caracteres(this)" size="70" maxlength="90">



<br>
Período:
&nbsp;
início:
<select name="cadas2[ExperienciaPeriodoInicioSemestre]" >
	<option value="nselecionado">
		semestre 
	</option>

	<option value="1" 
	<?php if ($repop2 ["ExperienciaPeriodoInicioSemestre"]== "1" ){echo"selected";}?>
	>
	01 
</option>

<option value="2" 
<?php if ($repop2 ["ExperienciaPeriodoInicioSemestre"]== "2" ){echo"selected";}?>
>
02 
</option>
</select>
<select name="cadas2[ExperienciaPeriodoInicioAno]" >
	<?php
	$ano=2013;
	echo '
	<option  value="nselecionado">
	ano 
	</option>
	';
	do
	{
		echo '
		<option value='.$ano; if ($repop2["ExperienciaPeriodoInicioAno"]== $ano ){echo" selected";} echo">
		".$ano."
		</option>";
		$ano--;
	} while ($ano>1980);
	?>
</select>
&nbsp &nbsp 
fim:
<select name="cadas2[ExperienciaPeriodoFimSemestre]" >
	<option value="nselecionado">
		Semestre 
	</option>

	<option value="1" 
	<?php if ($repop2["ExperienciaPeriodoInicioSemestre"]== "1" ){echo"selected";}?>
	>
	01 
</option>

<option value="2">
	02 
</option>
</select>
<select name="cadas2[ExperienciaPeriodoFimAno]" >
	<?php
	$ano=2013;
	echo '
	<option  value="nselecionado">
	Ano 
	</option>
	';
	do
	{
		echo '
		<option value='.$ano; if ($repop2["ExperienciaPeriodoFimAno"]== $ano ){echo" selected";} echo">
		".$ano."
		</option>";
		$ano--;
	} while ($ano>1980);
	?>
</select>



<p>

	<b>

		<span style=" 
		<?php if ($resultadovalidacadastro2[4] == 1){
			echo"";
		}else{
			echo "color:#FF0000; font-weight:bold";}
			?>
			">
			Programa Pretendido:
		</span>

	</b>
</p>

<?php 
unset($cadas2["CursoPos"]);
unset($_SESSION['repopula2']["CursoPos"]);
?>

<input type="checkbox" name="cadas2[Verao]" 
<?php if (trim($repop2["Verao"]) == "sim"){echo"checked ";} ?>
value="sim">Verão:

	<select name="cadas2[CursoVerao]">
		<option  value="nselecionado">
			Selecione uma opção:
		</option>
		<option value="Topologia Geral" 
		<?php  if ($repop2["CursoVerao"]== "Topologia Geral" ){echo"selected";}?>
		>
		Topologia Geral (Seleção para o mestrado!)
		</option>
		<!-- <option value="Variedades Diferenciáveis" 
		<?php//  if ($repop2["CursoVerao"]== "Variedades Diferenciáveis" ){echo"selected";}?>
		>
		Variedades Diferenciáveis
		</option>-->
	</select>
<br>

<p>

	<span style=" 
	<?php if ($resultadovalidacadastro2[6] == 1){
		echo"";
	}else{
		echo "color:#FF0000; font-weight:bold";}
		?>
		">
		Interesse em bolsa ?
	</span>
	<input type="radio" name="cadas2[InteresseBolsa]" <?php if ( $repop2["InteresseBolsa"] == "Sim"){echo"checked";}  ?> value="Sim">Sim &nbsp
	<input type="radio" name="cadas2[InteresseBolsa]" <?php if ( $repop2["InteresseBolsa"] == "Nao"){echo"checked";}  ?> value="Nao">Não
</p>



<p>

	<span style=" 
	<?php 
		if ($resultadovalidacadastro3[0] == 1){
			echo"";
		}else{
			echo "color:#FF0000; font-weight:bold";}
			?>
			">
			1- Nome:
		</span>

<?php

$cadas3[NomeProfRecomendante1] = "Jose Antonio";
$cadas3[EmailProfRecomendante1] = "jfreitas.mat@gmail.com";

$cadas3[NomeProfRecomendante2] = "Jose Antonio";
$cadas3[EmailProfRecomendante2] = "jfreitas.mat@gmail.com";

$cadas3[NomeProfRecomendante3] = "Jose Antonio";
$cadas3[EmailProfRecomendante3] = "jfreitas.mat@gmail.com";


?>
							<span style=" 
							<?php 
								if ($resultadovalidacadastro3[6] == 1){
									echo"";
								}else{
									echo "color:#FF0000; font-weight:bold";}
									?>
									">
									Escreva no espaço abaixo a sua motivação e expectativa em relação ao programa pretendido:
								</span>
								<br>
								<textarea style="background:#EEE9E9" id="justificativa" name="cadas3[MotivacaoProgramaPretendido]" 
								onkeyup="valida_caracteres(this)" onblur="valida_caracteres(this)" rows="15" cols="80" ><?php echo $repop3["MotivacaoProgramaPretendido"];?></textarea>
							</p>

							<script type="text/javascript">
							var justificativa = new LiveValidation('justificativa');
							justificativa.add(Validate.Presence, {failureMessage: "Não pode ser vazio!"});
							</script>

							<p>
								<b>


									<?php $assinatura = 1;?>


							<p align="center">
								<input type="hidden" name="cadas2[edital]" value="<?php echo $edital_atual;?>">
								<input type="hidden" name="cadas3[edital]" value="<?php echo $edital_atual;?>">
								<input type="submit" name="rota" value="Enviar Formulário">
								
							</p>


						</form>

					</body>
					</html>
