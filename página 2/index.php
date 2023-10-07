<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <title>Catálogo de Jogos</title>
</head>
<body>
<div class="videos-container">
<?php
$hostname = "localhost"; // Endereço do servidor MySQL
$bancodedados = "catalogo_jogos"; // Nome do banco de dados que você criou
$usuario = "root"; // Nome de usuário do MySQL (geralmente "root" no XAMPP)
$senha = ""; // Senha do usuário (vazia por padrão no XAMPP)

// Conecta-se ao banco de dados usando MySQLi
$mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);

// Verifica se ocorreu algum erro na conexão
if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Lida com o envio do formulário para adicionar um novo jogo
if (isset($_POST['acao'])) {
    $nome_jogo = $_POST['nome_jogo'];
    $link_jogo = $_POST['link_jogo'];
    
    // Verifique se uma imagem foi enviada
    if(isset($_FILES['imagem_jogo']) && $_FILES['imagem_jogo']['error'] === UPLOAD_ERR_OK) {
        $imagem_jogo = $_FILES['imagem_jogo']['tmp_name'];
        $imagem_jogo_nome = $_FILES['imagem_jogo']['name'];
        
        // Diretório de destino para as imagens enviadas
        $diretorio_destino = 'uploads/' . $imagem_jogo_nome;
        
        // Move a imagem para o diretório de destino
        move_uploaded_file($imagem_jogo, $diretorio_destino);
    } else {
        echo "Erro no upload da imagem.";
        $imagem_jogo = ''; // Se não houver imagem, deixe como uma string vazia
    }

    // Verifique se os campos não estão em branco
    if (empty($nome_jogo) || empty($link_jogo)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        // Preparar e executar uma consulta de inserção
        $sql = "INSERT INTO jogos (nome, imagem, sinopse, link) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssss", $nome_jogo, $diretorio_destino, $sinopse, $link_jogo);

        if ($stmt->execute()) {
            echo "Jogo cadastrado com sucesso!";
            
            // Aqui você pode adicionar uma sinopse gerada automaticamente
            $sinopse = "Sinopse do jogo: " . $nome_jogo; // Você pode criar uma lógica mais complexa para gerar a sinopse.
            
            // Atualize a sinopse no banco de dados
            $id_jogo_inserido = $stmt->insert_id; // Recupere o ID do jogo inserido
            $sql = "UPDATE jogos SET sinopse = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $sinopse, $id_jogo_inserido);
            $stmt->execute();
        } else {
            echo "Erro ao cadastrar o jogo: " . $stmt->error;
        }
    }
}

// Função para excluir um jogo por ID
if (isset($_GET['excluir'])) {
    $id_excluir = $_GET['excluir'];
    $sql = "DELETE FROM jogos WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_excluir);
    if ($stmt->execute()) {
        // Jogo excluído com sucesso (não imprime mensagem aqui para evitar duplicação)
    } else {
        echo "Erro ao excluir o jogo: " . $stmt->error;
    }
}

// Consulta para recuperar os jogos cadastrados
$result = $mysqli->query("SELECT * FROM jogos");

// Exibe os jogos com suas imagens e sinopses
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $nome = $row['nome'];
    $imagem = $row['imagem'];
    $sinopse = $row['sinopse']; // Recupera a sinopse do banco de dados
    $link = $row['link'];
    
    // Exibe a imagem junto com o jogo
    echo "<h2>$nome</h2>";
    echo "<img src='$imagem' alt='$nome'>";
    echo "<p>$sinopse</p>"; // Exibe a sinopse
    echo "<iframe width='560' height='315' src='$link' frameborder='0' allowfullscreen></iframe>";
    echo "<form method='post' action='?excluir=$id'><input type='submit' value='Excluir'></form>";
}

// Fecha a conexão com o banco de dados
$mysqli->close();
?>
<div class="container">
    <form method="post" enctype="multipart/form-data"> <!-- Adicione enctype para lidar com o upload da imagem -->
        <input type="text" name="nome_jogo" placeholder="Nome do jogo">
        <input type="text" name="link_jogo" placeholder="Link do jogo">
        <input type="file" name="imagem_jogo" accept="image/*"> <!-- Adicione um campo de upload de imagem -->
        <input type="submit" name="acao" value="Cadastrar">
        <p><a href="página3.php">Página de vídeos</a></p>
    </form>
</div>
</div>
<br>
</body>
</html>
