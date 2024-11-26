<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sneakerland";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$marca = isset($_GET['marca']) ? $_GET['marca'] : '';

$sql = "SELECT id, nome, preco, imagem_url FROM produtos WHERE marca = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $marca);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos da Marca <?php echo htmlspecialchars($marca); ?></title>
    <link rel="stylesheet" href="css/vestuario.css">
</head>

<body>
<?php include 'header.php'; ?>
    <h1>Produtos da Marca <?php echo htmlspecialchars($marca); ?></h1>
    <div class="catalogo">
    <?php
    if ($result->num_rows > 0) {
        $nomesExibidos = []; // Array para armazenar os nomes já exibidos
        while ($row = $result->fetch_assoc()) {
            // Verifica se o nome já foi exibido
            if (!in_array($row["nome"], $nomesExibidos)) {
                echo "<div class='produto'>";
                echo "<a href='produto.php?nome=" . urlencode($row["nome"]) . "'>";
                echo "<h2>" . htmlspecialchars($row["nome"]) . "</h2>";
                echo "<p>Preço: R$ " . number_format($row["preco"], 2, ',', '.') . "</p>";
                echo "<img src='" . htmlspecialchars($row["imagem_url"]) . "' alt='" . htmlspecialchars($row["nome"]) . "'>";
                echo "</a>";
                echo "</div>";

                // Adiciona o nome ao array para evitar duplicação
                $nomesExibidos[] = $row["nome"];
            }
        }
    } else {
        echo "<p>Nenhum produto encontrado para esta marca.</p>";
    }
    ?>
</div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>