<?php
    // Definição das informações de conexão com o MySQL
    $hostname = "localhost"; // Endereço do servidor MySQL
    $bancodedados = "teste"; // Nome do banco de dados que você deseja conectar
    $usuario = "root"; // Nome de usuário do MySQL (geralmente "root" no XAMPP)
    $senha = ""; // Senha do usuário (vazia por padrão no XAMPP)

    // Cria uma nova instância da classe MySQLi para estabelecer a conexão
    $mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);

    // Verifica se ocorreu um erro na conexão
    if ($mysqli->connect_errno) {
        echo "Falha ao conectar: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    } else {
        // Se a conexão foi bem-sucedida, exibe uma mensagem de sucesso
        echo "Conectado ao Banco de Dados";
    }
?>
