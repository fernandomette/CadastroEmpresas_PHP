<?php
    // mostrar errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'banco.php';
    include 'CSS.php';
    
    $empresaid = (int)$_GET["empresaid"];
    
    $nomeempresa = NomeEmpresa(); 
    $situacaoempresa = SituacaoEmpresa();
    
    function NomeEmpresa()
        {
            global $nomeempresa;
            global $empresaid;
            
            global $conn;
            mysqli_set_charset($conn,"utf8");
                    
            $sql = "SELECT empresa.nome as empresa_nome FROM (empresa) WHERE empresa.id=($empresaid)";
            
            
            $result = mysqli_query($conn, $sql);
            
            $resultArr = mysqli_fetch_array($result);
            
            
            $nomeempresa = @$resultArr["empresa_nome"];
            
            
            return $nomeempresa;
        }
    
    function SituacaoEmpresa()
        {
            global $situacaoempresa;
            global $empresaid;

            global $conn;
            mysqli_set_charset($conn,"utf8");
            
            $sql = "SELECT empresa.ativo as empresa_ativo FROM (empresa) WHERE empresa.id=($empresaid)";
            
            
            $result = mysqli_query($conn, $sql);
            
            $resultArr = mysqli_fetch_array($result);
            
            
            $situacaoempresa = @$resultArr["empresa_ativo"];
            
            return $situacaoempresa;
        }


    if( isset( $_POST["empresanome"]) or isset($_POST["empresasituacao"]) ) 
        {
          AlteraEmpresa();
        }//se houver um post de ou outro aciona a função abaixo
        

    function AlteraEmpresa()
        {
            
        global $nomeempresa;
        global $situacaoempresa;    
        global $empresaid; 
           
        global $conn;
        mysqli_set_charset($conn,"utf8");
    
        $nomeempresa = $_POST["empresanome"];
        $empresaativa = (int)$_POST["empresasituacao"];
        
        $sql = "SELECT id FROM (empresa) WHERE empresa.nome=('$nomeempresa')";
        
        $result = mysqli_query($conn, $sql);
        $resultArr = mysqli_fetch_array($result);
        $id = (int)@$resultArr["id"];
        
        if ($id == $empresaid)
            {
                $sql = "UPDATE empresa SET nome = ('$nomeempresa'), ativo = ($empresaativa) WHERE empresa.id=($empresaid)"; 
                
                if(mysqli_query($conn, $sql)) 
                    {
                        echo "Empresa Alterada";
                        $nomeempresa = NomeEmpresa(); 
                        $situacaoempresa = SituacaoEmpresa();
                    }
                    else
                    {
                        echo mysqli_error($conn);
                    }
            }
            else
            {
                if ($id)
                    {
                        echo "Ja exite empresa com o nome cadastrado, Tente Novamente";
                        
                    } 
                    else 
                    {
                        $sql = "UPDATE empresa SET nome = ('$nomeempresa'), ativo = ($empresaativa) WHERE empresa.id=($empresaid)";
                        
                        if(mysqli_query($conn, $sql)) 
                            {
                                echo "Empresa Alterada";
                                $nomeempresa = NomeEmpresa(); 
                                $situacaoempresa = SituacaoEmpresa();
                            }
                            else
                            {
                                echo mysqli_error($conn);
                            }
                        
                    }
            }
            
        }//final da função altera empresa
        
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Alteração de empresa na Mette</title>
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
        <h3>Alteração de cadastro da empresa</h3>
        
        <form action="setalteraempresa.php?empresaid=<?=$empresaid?>" method="post">
            <label for="alteraempresa">Nome da Empresa:</label><br>
            <label><br>
            <input type="text" id="empresanome" name="empresanome" value="<?=$nomeempresa?>" required="required"/><br>
            <label><br>
            <label for="alteraempresa">Situação da Empresa:</label><br>
            <label><br>
            <select id="empresasituacao" name="empresasituacao" required="required"/>

                <? if ($situacaoempresa)
                    {?>
                        <option value="1" selected>Ativo</option>
                        <option value="0">Desativo</option>
                    <?}
                    else
                    {?>
                        <option value="1">Ativo</option>
                        <option value="0" selected>Desativo</option> 
                    <?}?>

            </select>
            <label><br>
            <label><br>
            <input type="submit" value="Alterar Empresa">
        </form> 
        
        
        <h3> <a href="set-cadastroempresas.php?"> Voltar </a> </h3>
    </body>
    

</html>
