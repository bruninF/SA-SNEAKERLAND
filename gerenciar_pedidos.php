<?php
session_start(); // Inicia a sessão
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    // Se não estiver logado ou não for administrador, redireciona para o login
    header("Location: index.php");
    exit;
}
// Conexão com o banco de dados MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sneakerland";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o usuário está logado e se tem permissão para gerenciar pedidos (por exemplo, se for um administrador)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver logado ou não for admin
    exit();
}

// Consulta para obter todos os pedidos
$query = "
    SELECT p.id AS pedido_id, p.data, p.status, p.total, u.nome AS usuario_nome, ip.quantidade, ip.preco, ip.produto_id, prod.nome AS produto_nome, prod.imagem_url 
    FROM pedidos p 
    JOIN itens_pedido ip ON p.id = ip.pedido_id 
    JOIN produtos prod ON ip.produto_id = prod.id 
    JOIN usuarios u ON p.usuario_id = u.id 
    ORDER BY p.data DESC, p.id
";

$result = $conn->query($query);

// Agrupando pedidos por ID
$pedidos = [];

while ($row = $result->fetch_assoc()) {
    $pedido_id = $row['pedido_id'];

    if (!isset($pedidos[$pedido_id])) {
        $pedidos[$pedido_id] = [
            'data' => $row['data'],
            'status' => $row['status'],
            'total' => $row['total'],
            'usuario_nome' => $row['usuario_nome'],
            'itens' => []
        ];
    }

    $pedidos[$pedido_id]['itens'][] = [
        'produto_id' => $row['produto_id'],
        'nome' => $row['produto_nome'],
        'quantidade' => $row['quantidade'],
        'preco' => $row['preco'],
        'imagem_url' => $row['imagem_url']
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['pedido_id'];
    $novo_status = $_POST['status'];

    // Atualiza o status do pedido
    $update_query = "UPDATE pedidos SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $novo_status, $pedido_id);
    $stmt->execute();

    // Se o pedido for cancelado, restaura o estoque
    if ($novo_status === 'cancelado') {
        $itens_query = "SELECT produto_id, quantidade FROM itens_pedido WHERE pedido_id = ?";
        $itens_stmt = $conn->prepare($itens_query);
        $itens_stmt->bind_param("i", $pedido_id);
        $itens_stmt->execute();
        $itens_result = $itens_stmt->get_result();

        while ($item = $itens_result->fetch_assoc()) {
            $produto_id = $item['produto_id'];
            $quantidade = $item['quantidade'];

            // Atualiza o estoque
            $update_estoque = "UPDATE produtos SET estoque = estoque + ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_estoque);
            $update_stmt->bind_param("ii", $quantidade, $produto_id);
            $update_stmt->execute();
        }
        $itens_stmt->close();
    }

    $stmt->close();
    header("Location: gerenciar_pedidos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Pedidos</title>
    <link rel="stylesheet" href="css/gerenciar_pedidos.css"> <!-- Inclua seu arquivo CSS -->
</head>

<body>
<div class="gerenciar-pedidos">
        <?php include 'headerADM.php'; ?> <!-- Inclui o cabeçalho -->
    <div class="container">
        <h1>Gerenciar Pedidos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID do Pedido</th>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Itens</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido_id => $pedido): ?>
                    <tr>
                        <td><?php echo $pedido_id; ?></td>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($pedido['data'])); ?></td>
                        <td><?php echo $pedido['usuario_nome']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pendente" <?php echo ($pedido['status'] === 'pendente') ? 'selected' : ''; ?>
                                        <?php echo ($pedido['status'] !== 'pendente') ? 'disabled' : ''; ?>>Pendente</option>

                                    <option value="processando" <?php echo ($pedido['status'] === 'processando') ? 'selected' : ''; ?>
                                        <?php echo ($pedido['status'] === 'concluido' || $pedido['status'] === 'cancelado') ? 'disabled' : ''; ?>>Processando</option>

                                    <option value="concluido" <?php echo ($pedido['status'] === 'concluido') ? 'selected' : ''; ?>
                                        <?php echo ($pedido['status'] !== 'processando') ? 'disabled' : ''; ?>>Concluído</option>

                                    <option value="cancelado" <?php echo ($pedido['status'] === 'cancelado') ? 'selected' : ''; ?>
                                        <?php echo ($pedido['status'] === 'concluido' || $pedido['status'] === 'cancelado') ? 'disabled' : ''; ?>>Cancelado</option>
                                </select>
                                <input type="hidden" name="pedido_id" value="<?php echo $pedido_id; ?>">
                            </form>
                        </td>

                        <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                        <td>
                            <ul>
                                <?php foreach ($pedido['itens'] as $item): ?>
                                    <li>
                                        <img src="<?php echo $item['imagem_url']; ?>" alt="<?php echo $item['nome']; ?>" width="50">
                                        <?php echo $item['nome']; ?> - Quantidade: <?php echo $item['quantidade']; ?> - Preço: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>