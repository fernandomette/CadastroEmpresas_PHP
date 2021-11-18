<?php
    
    if(isset($_POST["empresa"])) {
      echo "teste foi";
      echo $_POST["empresa"];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Lista de Castro de Empresa Mette</title>
        <style>
            body 
                {
                text-align: center;
                font-family: arial, sans-serif;
                }
            table 
                {
                margin: 0 auto;
                }
            td 
                {
                padding: 10px;
                }
        </style>
    </head>
    
    <body>
        <h1>Mette Business</h1>
        
        <h3>Casdastro de empresa</h3>
        
        <form action="set-Andrei.php" method="post">
            <label for="empresa">Nome da Empresa:</label><br>
            <input type="text" id="empresa" name="empresa" value=""><br>
            <input type="submit" value="Submit">
        </form> 
        
        
        <h3> <a href="index.php?"> Voltar </a> </h3>
    </body>
</html>