<?php
session_start(); // Iniciar a sessão

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'funcionario') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}

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

// Recebe o valor da busca
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Consulta SQL com filtro por nome
$sql = "SELECT id, nome, email, telefone, cpf, sexo 
        FROM usuarios 
        WHERE tipo_usuario = 'cliente' 
        AND nome LIKE ?";

$stmt = $conn->prepare($sql);
$searchParam = '%' . $search . '%';
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'headerFuncionario.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Consulta</title>
    <link rel="stylesheet" href="css/clientes.css">
</head>

<body>
    <div class="container">
        <h1>Clientes</h1>

        <!-- Barra de Pesquisa -->
        <form method="GET" action="" class="search-form">
            <input type="text" name="search" placeholder="Pesquise por nome..." value="<?= htmlspecialchars($search) ?>" />
            <button type="submit">Buscar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>Sexo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Exibe dados de cada linha
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["nome"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>" . $row["telefone"] . "</td>
                                <td>" . $row["cpf"] . "</td>
                                <td>" . $row["sexo"] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum cliente encontrado.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
