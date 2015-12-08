<?php
    include_once("pgsql/pgsql.php");

 include_once("config/config.php");
 
 $usuario = trim($_POST['usuario']);
 $senha = md5($_POST['senha']);


 $sql_acesso = pg_query ("SELECT * FROM inscricao_pos_login WHERE login='$usuario' AND senha='$senha'");

// Pega coduser 
 $pega_coduser=pg_query("select coduser from inscricao_pos_login where login='".$usuario."'");
 $coduser0=pg_fetch_row($pega_coduser); 
 $coduser = $coduser0[0];
// Pega Status  
 $pega_status=pg_query("select status from inscricao_pos_login where login='".$usuario."'");
 $status0=pg_fetch_row($pega_status);
 $status = $status0[0];


if ( (pg_num_rows($sql_acesso) == 1)) {

        if ($status == "candidato")
        {
            $_SESSION['coduser'] = $coduser;

            // Verificar se o candidato completou inscrição
            $sql_verifica_inscricao_completa = pg_query("select finalizada from inscricao_pos_finaliza where coduser='$coduser' and edital='$edital_atual'");
            $inscricao_finalizada0 = pg_fetch_row($sql_verifica_inscricao_completa);
            $inscricao_finalizada = $inscricao_finalizada0[0];
            if ($inscricao_finalizada == "sim"){
                echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=cadastro/status_recebimento_carta_recomendacao.php'>";
            }else{
                echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=cadastro/novo-cadastroinscricao2.php'>";
                 }
        }else{
            $_SESSION['coduser'] = $coduser;
            echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=carta/entrada_recomendante.php'>";
           }

}else{ 
    echo"<script language='javascript' type='text/javascript'>alert('Login e/ou senha incorretos');window.location.href='index.html';</script>";
    unset($_SESSION['coduser']);
    //include "index.html";

 }
?>