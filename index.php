<?php
    // mostrar errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'banco.php';
    include 'CSS.php';
    
    function Acessodeusuario($id)
        {
            header('Location: main.php');
        }
    
    if( isset( $_POST["usuario"]) or isset($_POST["senha"]) ) 
        {
          AcessaSistema();
        }
    
    function AcessaSistema()
        {
            
            $usuario = $_POST["usuario"];
            $senha = $_POST["senha"];
            
            global $conn;
            mysqli_set_charset($conn,"utf8");
            
            $sql = "SELECT id FROM (usuarios) WHERE usuarios.usuario=('$usuario') and usuarios.senha=('$senha') ";
            
            $result = mysqli_query($conn, $sql);
            $resultArr = mysqli_fetch_array($result);
            
            $id = (int)@$resultArr["id"];
            
            if ($id)
                {
                    echo("Bem Vindo ao sistema: $usuario");
                    Acessodeusuario($id);
                }
                else
                {
                    echo("Usuário ou senha não encontrado, favor digite novamente");  
                    header('Location:');
                }
            
        }
    
    
        
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Acesso a Empresa Mette</title>
    </head>
    
    <body>
         
        <h1><img src="Imagens/LOGO METE PRIM GIIIF.gif" alt="ERRO NA IMAGEM" width=300 height=200></h1>
        
        <form action="index.php" method="post">
            <p>
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" placeholder="mette" required/><br>
            </p>
            <p>
                <label for="usuario">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="1234" minlength="4" required><br>
            </p>
            
            <p> 
                <input type="checkbox" name="manterlogado" id="manterlogado" value="" /> 
                <label for="manterlogado">Manter-me logado</label>
            </p>
              
            <p>  
                <input type="submit" value="Entrar">
            </p>
            
            <p class="link">
                Ainda não tem conta?
                <a href="set-cadastrousuario.php?">Cadastre-se</a>
              </p>
        </form> 
        
    </body>
    
    
</html>
