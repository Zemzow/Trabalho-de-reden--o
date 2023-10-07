<?php
    // Inclui o arquivo "conexao.php" que deve conter a configuração da conexão com o banco de dados
    include("conexao.php");

    // Define uma string de texto com o conteúdo "Olá Mundo!"
    $texto = "\n Olá Mundo!";

    // A função nl2br() é usada para inserir tags <br> em uma string onde há quebras de linha (\n)
    // Isso permite que as quebras de linha sejam exibidas corretamente em HTML
    echo nl2br($texto);
?>
