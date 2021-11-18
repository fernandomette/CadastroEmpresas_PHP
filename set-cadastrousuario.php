<?php
    // mostrar errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'banco.php';
    include 'CSS.php';    
        
    if( isset( $_POST["usuario"]) or isset($_POST["senha"]) ) 
        {
          AcessaSistema();
        }
    
    function AcessaSistema()
        {
            global $conn;
            mysqli_set_charset($conn,"utf8");
            
            $usuario = $_POST["usuario"];
            $senha = $_POST["senha"];
            $senhadois = $_POST["senhadois"];
            
            if ($senhadois == $senha)
            
                {
                    $sql = "SELECT id FROM (usuarios) WHERE usuarios.usuario=('$usuario')";
                    
                    $result = mysqli_query($conn, $sql);
                    $resultArr = mysqli_fetch_array($result);
                    
                    $id = (int)@$resultArr["id"];
                    
                    if ($id)
                        {
                            echo("ja existe usuário com este nome, tente novamente");
                            header('Location:');
                        }
                        else
                        {
                            $sql = "INSERT INTO `usuarios` (usuario, senha) VALUES ('$usuario', '$senha')";
                    
                            mysqli_query($conn, $sql);
                            
                             echo("Usuário Cadastrado");
                        }
                    
                }
                else
                {
                   echo("Senhas não conferem, tente novamente");
                }
       
            
        }

        
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Lista de Cadastro de usuario</title>
    </head>
    
    <body>
        <table align=right>
            <tr>
                <td> 
                    <label>Usuario: Teste  </label> <a href="index.php?">Sair</a>
                </td>
            </tr>
        </table>
        
        <label>.</label>
         
        <h1>Mette Business</h1>
        
        <form action="set-cadastrousuario.php" method="post">
            <p>
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" placeholder="mette" required/><br>
            </p>
            <p>
                <label for="usuario">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="1234" minlength="4" required><br>
            </p>
            
            <p>
                <label for="usuario">Repita a senha:</label>
                <input type="password" id="senhadois" name="senhadois" placeholder="1234" minlength="4" required><br>
            </p>

            <p>  
                <input type="submit" value="Cadastrar">
            </p>
            
            <p class="link">
                Ja tem conta?
                <a href="index.php?">Ir para Login</a>
              </p>
        </form> 
        
      
        
    </body>
    
    
</html>
