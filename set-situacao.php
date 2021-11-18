<?php
    // mostrar errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include 'banco.php';
    mysqli_set_charset($conn,"utf8");
    
    // pega os valores da URL
    $empresa = (int)$_GET["empresa"];
    $servico = (int)$_GET["servico"];
    $situacao = (int)$_GET["situacao"];
    // tenta pegar o id (de empresa_servico) caso exista
    $sql = "SELECT id FROM (empresa_servico) WHERE (empresa=$empresa) AND (servico=$servico)";
    
    //busca o valor no banco de dados referente a empresa e serviço
    $result = mysqli_query($conn, $sql);
    
    //pega a primeira linha do resultado da lista buscada no banco de dados
    $resultArr = mysqli_fetch_array($result);
    
    //me retorna o valor 0 caso seja nulo ou ou algum ID existente 
    $id = (int)@$resultArr["id"];
    
    //zero igual a falso ou outro valor igual a verdadeiro
    if ($id)
        {
            $sql = "UPDATE empresa_servico SET situacao = $situacao WHERE empresa_servico.empresa = $empresa AND empresa_servico.servico = $servico";
        } 
        else 
        {
            $sql = "INSERT INTO empresa_servico (id, empresa, servico, situacao) VALUES (null, $empresa, $servico, $situacao)";
            
        }
        
    //comando para para a base dados conforme o if acima    
    mysqli_query($conn, $sql); 

    //retorna para a pagina inicial
    header('Location: main.php');
    
?>