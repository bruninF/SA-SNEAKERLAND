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

    try {
        // Atualiza o status do funcionário para "inativo"
        $sql = "UPDATE funcionarios SET status = 'inativo' WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Funcionário demitido com sucesso!'); window.location.href = 'funcionarios.php';</script>";
        } else {
            echo "<script>alert('Erro ao demitir funcionário: ID não encontrado.'); window.location.href = 'funcionarios.php';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Erro ao demitir funcionário: " . $e->getMessage() . "'); window.location.href = 'funcionarios.php';</script>";
    }

    // Fecha o statement
    $stmt->close();
}

// Fecha a conexão
$conn->close();

// Redireciona de volta para a página de funcionários
header("Location: funcionarios.php");
exit();
