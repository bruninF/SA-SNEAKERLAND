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

    // Inicia uma transação para garantir que as duas exclusões ocorram juntas
    $conn->begin_transaction();

    try {
        // Exclui da tabela 'funcionarios'
        $sql_funcionario = "DELETE FROM funcionarios WHERE usuario_id = ?";
        $stmt_funcionario = $conn->prepare($sql_funcionario);
        $stmt_funcionario->bind_param("i", $id);
        $stmt_funcionario->execute();

        // Exclui da tabela 'usuarios'
        $sql_usuario = "DELETE FROM usuarios WHERE id = ?";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("i", $id);
        $stmt_usuario->execute();

        // Se ambas as exclusões ocorrerem com sucesso, confirma a transação
        $conn->commit();
        echo "Funcionário demitido com sucesso!";
    } catch (Exception $e) {
        // Se ocorrer um erro, desfaz a transação
        $conn->rollback();
        echo "Erro ao demitir funcionário: " . $e->getMessage();
    }

    // Fecha os statements
    $stmt_funcionario->close();
    $stmt_usuario->close();
}

// Fecha a conexão
$conn->close();

// Redireciona de volta para a página de funcionários
header("Location: funcionarios.php");
exit();
?>
