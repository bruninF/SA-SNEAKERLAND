<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}
// Conexão com o banco de dados
$servername = "localhost"; // Altere se necessário
$username = "root";  // Substitua pelo seu usuário
$password = "";     // Substitua pela sua senha
$dbname = "sneakerland";     // Nome do banco de dados

// Criando conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Consulta SQL para buscar produtos por nome, considerando a pesquisa
$sql = "SELECT nome, preco, imagem_url, marca 
        FROM produtos 
        WHERE nome LIKE ? 
        GROUP BY nome";
$stmt = $conn->prepare($sql);
$searchParam = '%' . $search . '%';
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Produtos</title>
    <link rel="stylesheet" href="css/vestuario.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>

    <h1>Catálogo de Produtos</h1>
    <form method="GET" action="" class="search-form">
        <input type="text" name="search" placeholder="Pesquise por nome..." value="<?= htmlspecialchars($search) ?>" />
        <button type="submit">Buscar</button>
    </form>
    <div class="catalogo">
        <?php
        if ($result->num_rows > 0) {
            // Exibindo os dados de cada produto
            while ($row = $result->fetch_assoc()) {
                echo "<div class='produto' onclick=\"window.location.href='produto.php?nome=" . urlencode($row["nome"]) . "'\">";
                echo "<h2>" . htmlspecialchars($row["nome"]) . "</h2>";
                echo "<p>Marca: " . htmlspecialchars($row["marca"]) . "</p>"; // Exibe a marca
                echo "<p>Preço: R$ " . number_format($row["preco"], 2, ',', '.') . "</p>";
                echo "<img src='" . htmlspecialchars($row["imagem_url"]) . "' alt='" . htmlspecialchars($row["nome"]) . "'>";
                echo "</div>";
            }
        } else {
            echo "Nenhum produto encontrado.";
        }
        ?>
    </div>

</body>

</html>

<?php
// Fechando a conexão
$conn->close();
?>