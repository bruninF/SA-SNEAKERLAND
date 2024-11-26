<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sneakerland";

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o ID foi enviado
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Exclui da tabela usuarios
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Cliente removido com sucesso!";
    } else {
        echo "Erro ao remover cliente: " . $conn->error;
    }

    $stmt->close();
}

// Fecha a conexão
$conn->close();

// Redireciona de volta para a página de clientes
header("Location: clientes.php");
exit();
?>
