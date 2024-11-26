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

// Consulta para buscar marcas únicas
$sql = "SELECT DISTINCT marca FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcas Disponíveis</title>
    <link rel="stylesheet" href="css/marcas.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="page-marcas-container">
        <h1>Marcas Disponíveis</h1>
        <ul class="brand-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li><a href='produtos_por_marca.php?marca=" . urlencode($row["marca"]) . "'>" . htmlspecialchars($row["marca"]) . "</a></li>";
                }
            } else {
                echo "<p>Nenhuma marca encontrada.</p>";
            }
            ?>
        </ul>
    </div>
</body>



</html>

<?php
$conn->close();
?>