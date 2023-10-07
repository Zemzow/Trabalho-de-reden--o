<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <title>Pesquisa de Livros</title>
</head>
<body>
<div class="videos-container">
<?php
// Configurações de conexão ao banco de dados
$hostname = "localhost"; // Endereço do servidor MySQL
$bancodedados = "youtube"; // Nome do banco de dados que você criou
$usuario = "root"; // Nome de usuário do MySQL (geralmente "root" no XAMPP)
$senha = ""; // Senha do usuário (vazia por padrão no XAMPP)

// Conecta-se ao banco de dados usando MySQLi
$mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);

// Verifica se ocorreu algum erro na conexão
if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Lida com o envio do formulário para adicionar um novo vídeo
if (isset($_POST['acao'])) {
    $nome_video = $_POST['nome_video'];
    $link_video = $_POST['link_video'];

    // Verifique se os campos não estão em branco
    if (empty($nome_video) || empty($link_video)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        // Preparar e executar uma consulta de inserção
        $sql = "INSERT INTO videos (nome, link) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $nome_video, $link_video);

        if ($stmt->execute()) {
            echo "Vídeo cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar o vídeo: " . $stmt->error;
        }
    }
}

// Função para excluir um vídeo por ID
if (isset($_GET['excluir'])) {
    $id_excluir = $_GET['excluir'];
    $sql = "DELETE FROM videos WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_excluir);
    if ($stmt->execute()) {
        // Vídeo excluído com sucesso (não imprime mensagem aqui para evitar duplicação)
    } else {
        echo "Erro ao excluir o vídeo: " . $stmt->error;
    }
}

// Consulta para recuperar os vídeos cadastrados
$result = $mysqli->query("SELECT * FROM videos");

// Exibe os vídeos como players do YouTube e botão para excluir
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $nome = $row['nome'];
    $link = $row['link'];
    echo "<h2>$nome</h2>";
    echo "<iframe width='560' height='315' src='$link' frameborder='0' allowfullscreen></iframe>";
    echo "<form method='post' action='?excluir=$id'><input type='submit' value='Excluir'></form>";
}
// Fecha a conexão com o banco de dados
$mysqli->close();
?>
<div class="container">
    <form method="post">
        <input type="text" name="nome_video" placeholder="Nome do seu vídeo">
        <input type="text" name="link_video" placeholder="Link do seu vídeo">
        <input type="submit" name="acao" value="Cadastrar">
        <p><a href="página 2.php">Página de Jogos</a></p>
    </form>
    <!-- Exibe os vídeos cadastrados com os botões de exclusão -->
    <div class="videos-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="video">
                <h2><?php echo $row['nome']; ?></h2>
                <div class="video-iframe">
                    <iframe src="<?php echo $row['link']; ?>" frameborder="0" allowfullscreen></iframe>
                </div>
                <form method="post" action="?excluir=<?php echo $row['id']; ?>">
                    <br>
                    <input type="submit" value="Excluir">
                </form>
            </div>
        <?php } ?>
    </div>
</div>
</div>
<br>
</body>
</html>
