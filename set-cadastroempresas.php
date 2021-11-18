<?php
    // mostrar errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'banco.php';
    include 'CSS.php';
    
    function Listaempresa()
        {
        
        global $conn;
        mysqli_set_charset($conn,"utf8");
        
        $sql = "SELECT empresa.id AS empresa_id, empresa.nome AS empresa_nome, empresa.ativo AS empresa_ativo FROM (empresa) ORDER BY (empresa.id)";
        
        $resultlistempresa = mysqli_query($conn, $sql);
        
        $listempresas = array();
        
        while ($arr = mysqli_fetch_array($resultlistempresa)) 
            {
            
                $empresaId = (int)$arr["empresa_id"];
                
                // verifica se empresa já existe no array
                if (!array_key_exists($empresaId, $listempresas)) 
                    {
                        
                    
                    // adiciona a empresa
                    $empresaNome = $arr["empresa_nome"];
                    
                    if ($arr["empresa_ativo"])
                        {
                        $empresaAtivo = "Ativo";
                        } 
                        else 
                        {
                        $empresaAtivo = "Desativado";
                        }
                        
                    $listempresas[$empresaId] = array(  "id" => $empresaId, "nome" => $empresaNome, "ativo" => $empresaAtivo);
                    
                    }
            
            }
        
        return $listempresas;
            
        } 
        
        
    if(isset($_POST["empresa"])) 
        {
          AdicionaEmpresa();
        }
        

    function AdicionaEmpresa()
        {
            
        $conn = ConectaBacno();     

        $nomeempresa = $_POST["empresa"];
      
        $sql = "SELECT id FROM (empresa) WHERE empresa.nome=('$nomeempresa')";
        
        //busca o valor no banco de dados referente a empresa e serviço
        $result = mysqli_query($conn, $sql);
        
        //pega a primeira linha do resultado da lista buscada no banco de dados
        $resultArr = mysqli_fetch_array($result);
            
        //me retorna o valor 0 caso seja nulo ou ou algum ID existente 
        $id = (int)@$resultArr["id"];
        
        //zero igual a falso ou outro valor igual a verdadeiro
        if ($id)
            {
                echo "Ja exite empresa com o nome cadastrado, Tente Novamente";
            } 
            else 
            {
                $sql = "INSERT INTO empresa (nome, ativo) VALUES ('$nomeempresa', 1)";
                
                if(mysqli_query($conn, $sql)) 
                    {
                        echo "Empresa adicionada"; 
                        Listaempresa();
                    }
                    else
                    {
                        echo mysqli_error($conn);
                    }
            }
   
        }
        
 
        
        
?>

<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <title>Lista de Cadastro de Empresa Mette</title>
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
        
        <h3>Casdastro de empresa</h3>
        
        <form action="set-cadastroempresas.php" method="post">
            <label for="empresa">Nome da Empresa:</label><br>
            <label><br>
            <input type="text" id="empresa" name="empresa" value="" required="required"/><br>
            <label><br>
            <input type="submit" value="Cadastrar Empresa">
        </form> 
        
        <h3>Lista de Empresas Casdastradas</h3>
        
        <table>
            <?php foreach(Listaempresa() as $listemp) { ?>
                <tr>
                    <td align=left>ID:<?=$listemp["id"]?> <?=$listemp["nome"]?> </td>
                    <td> <?= $listemp["ativo"] ?> </td>
                    <td> <a href="setalteraempresa.php?empresaid=<?=$listemp["id"]?>">Alterar</a>  </td>
                <?php } ?>
        </table>
        
        
        <h3> <a href="main.php?"> Voltar </a> </h3>
    </body>
    

    
    
    
    
    
</html>