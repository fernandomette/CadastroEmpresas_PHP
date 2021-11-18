<?php
    // mostrar errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    //puxa o banco de dados esta sendo usado
    //phpinfo();
    
    require 'banco.php';
    require 'CSS.php';
    
    mysqli_set_charset($conn,"utf8");
    
    $listempresas = array();
    $listtarefas = array();
    
    listaempresacompleta();
    
    function listaempresacompleta()
    {
        
        global $listempresas;
        global $listtarefas;
        global $conn;
        
        $sql = "
            SELECT 
        	    empresa.id AS empresa_id,
        	    empresa.nome AS empresa_nome,
                empresa.ativo AS empresa_ativo,
        	    empresa_servico.servico AS servico_id,
        	    empresa_servico.situacao AS situacao_id,
        	    situacao.nome AS situacao_nome,
        	    tarefas.id AS tarefas_id, 
        	    tarefas.descricao AS tarefas_descricao, 
        	    tarefas.empresaid AS tarefaempresa_id
            FROM empresa
                LEFT OUTER JOIN empresa_servico ON empresa_servico.empresa = empresa.id
                LEFT OUTER JOIN situacao ON empresa_servico.situacao = situacao.id
                LEFT OUTER JOIN tarefas ON tarefas.id = tarefas.id
            WHERE
            	empresa.ativo = (1)
            ORDER BY 
                empresa.id
            ";
            
        $resultlistempresa = mysqli_query($conn, $sql);

        while ($arr = mysqli_fetch_array($resultlistempresa)) 
             {
            
                $empresaId = (int)$arr["empresa_id"];
                
                // verifica se empresa já existe no array
                if (!array_key_exists($empresaId, $listempresas)) 
                    {
                    // adiciona a empresa
                    $empresaNome = $arr["empresa_nome"];
                    $listempresas[$empresaId] = array(  "id" => $empresaId, "nome" => $empresaNome, "servicos" => array(), "tarefas" => array());
                    }
            
                // adiciona serviço se houver
                $servicoId = (int)$arr["servico_id"];
                
                if ($servicoId) 
                    {
                    $situacaoId = (int)$arr["situacao_id"];
                    $situacaoNome = $arr["situacao_nome"];
                    $listempresas[$empresaId]["servicos"][$servicoId] = array("servico" => $servicoId,"situacao" => array( "id" => $situacaoId,"nome" => $situacaoNome));
                    }
                    
                    
                $tarefaId = (int)$arr["tarefas_id"];   
                
                if ($tarefaId) 
                    {
                    $descricao = $arr["tarefas_descricao"];
                    $empresaid = (int)$arr["tarefaempresa_id"];
                    
                    $listtarefas[$tarefaId] = array( "tarefas_id" => $tarefaId, "tarefas_descricao" => $descricao, "tarefaempresa_id" => $empresaid);
                    }
                    
            }//fim do while que percorre a lista no BD
        
    }
    
    
    $sql = "SELECT id, nome FROM servico";
    $resultlistservico = mysqli_query($conn, $sql);
    
    $listservicos = array();
    
    while ($arr = mysqli_fetch_array($resultlistservico))  
        {
        $listservicos[] = array("id" => $arr["id"],"nome" => $arr["nome"]);
        }
        

    if(isset($_POST["tarefasempresa"])) 
        {
          excluitarefasempresa();
        }
        

    function excluitarefasempresa()
        {
            global $conn;
            
            $tarefaexluida = $_POST["tarefasempresa"];
            
            $sql = "DELETE FROM tarefas WHERE tarefas.id = '$tarefaexluida'";
             
            mysqli_query($conn, $sql);
             
            header('Location: main.php?');
             
        }
        
    if(isset($_POST["tarefacadastrada"])) 
        {
          CadastraTarefaEmpresa();
        }
        
    function CadastraTarefaEmpresa()
        {
            global $conn;
            
            $tarefaAdiconada = $_POST["tarefacadastrada"];
            
            $EmpresaIDtarefaAdiconada = $_POST["EmpresaIDtarefaAdiconada"];
            
            $sql = "INSERT INTO tarefas (id, descricao, empresaid) VALUES (NULL, '$tarefaAdiconada', '$EmpresaIDtarefaAdiconada')";
            
            mysqli_query($conn, $sql);
             
            header('Location: main.php?');
             
        }    
   
?>

<!DOCTYPE html>
<html>
    <head>
            
        <meta charset="utf-8">
        <title>Lista de Empresa Ativa e Servicos</title>
        
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
        
        <h3> <a href="set-cadastroempresas.php?"> Cadastro e Alteração de Empresa </a> </h3>
        
        <h3>Lista de Empresas Ativas e Serviços</h3>
        <table>
            <tr>
                <td></td>
                <?php foreach($listservicos as $listserv) { ?>
                    <td><?=$listserv["nome"]?></td>
                <?php } ?>
            </tr>
            
                <?php foreach($listempresas as $listemp) { ?>
                    <tr>
                        <td align=left>ID:<?=$listemp["id"]?> <?=$listemp["nome"]?></td>
                        
                            <?php foreach($listservicos as $listserv) { ?>
                            <td>
                                <?=@$listemp["servicos"][$listserv["id"]]["situacao"]["nome"]?><br>
                                <a href="set-situacao.php?empresa=<?=$listemp["id"]?>&servico=<?=$listserv["id"]?>&situacao=100">Feito</a>
                                <a href="set-situacao.php?empresa=<?=$listemp["id"]?>&servico=<?=$listserv["id"]?>&situacao=300">A Fazer</a>
                                <a href="set-situacao.php?empresa=<?=$listemp["id"]?>&servico=<?=$listserv["id"]?>&situacao=700">Alerta</a>
                             </td>

                            <?php } ?>
                        
                        <td>

                           <form action="main.php?" method="post" name="deletatarefa" id="deletatarefa">
                               
                                <legend> lista de tarefas </ legend><br>
                                
                                <?php foreach($listtarefas as $listemptarefa) { ?>
                                   
                                        <?php 
                                            $idempresatarefa = $listemptarefa["tarefaempresa_id"]; 
                                            $idempresa = $listemp["id"];
                                            $Existetarefa = 0;
                                        ?>
                                        
                                        <?php if ($idempresatarefa == $idempresa) { ?>
                                        
                                            <input type = "radio" id = "tarefasempresa" name = "tarefasempresa" value = "<?=$listemptarefa["tarefas_id"]?>">
                                            <label for = "coding"><?=$listemptarefa["tarefas_descricao"]?></ label><br>
                                        
                                        <?php } ?>
                                    
                                <?php } ?>    
                                
                                <input type="submit" value="Apaga o Selecionado"><br>
    
                            </form>
                            
                            <form action="main.php?" method="post" name="tarefacadastrada" id="tarefacadastrada">
                                
                                <input type=hidden id="EmpresaIDtarefaAdiconada" name="EmpresaIDtarefaAdiconada" value="<?=$listemp["id"]?>"></label>
                                <input type="text" id="tarefacadastrada" name="tarefacadastrada" value="" required="required"><br>
                                <input type="submit" value="Cadastrar Tarefa">
                                
                            </form>
                            
   
                        </td>                
                    </tr>

                <?php } ?>
                

        </table>
        
    </body>
    
    
</html>
