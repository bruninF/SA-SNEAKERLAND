<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'cliente') {
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

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Consulta para obter os pedidos do usuário
$query = "
    SELECT p.id AS pedido_id, p.data, p.status, p.total, ip.quantidade, ip.preco, prod.nome, prod.descricao, prod.preco AS preco_produto, prod.imagem_url, prod.marca, prod.tamanho, prod.cor 
    FROM pedidos p 
    JOIN itens_pedido ip ON p.id = ip.pedido_id 
    JOIN produtos prod ON ip.produto_id = prod.id 
    WHERE p.usuario_id = ?
    ORDER BY p.data DESC, p.id
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id); // Bind do usuário logado
$stmt->execute();
$result = $stmt->get_result();

$pedidos = []; // Array para armazenar os pedidos

// Agrupando os itens dos pedidos
while ($row = $result->fetch_assoc()) {
    $pedido_id = $row['pedido_id'];
    if (!isset($pedidos[$pedido_id])) {
        $pedidos[$pedido_id] = [
            'data' => $row['data'],
            'status' => $row['status'],
            'total' => $row['total'],
            'itens' => []
        ];
    }
    $pedidos[$pedido_id]['itens'][] = [
        'nome' => $row['nome'],
        'quantidade' => $row['quantidade'],
        'preco' => $row['preco'],
        'imagem_url' => $row['imagem_url']
    ];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="css/pedidos.css"> <!-- Inclua seu arquivo CSS -->
</head>

<body>

    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Meus Pedidos</h1>
        <?php foreach ($pedidos as $pedido_id => $pedido): ?>
            <div class="pedido">
                <h2>Pedido ID: <?php echo $pedido_id; ?></h2>
                <p><strong>Data:</strong> <?php echo date('d/m/Y H:i:s', strtotime($pedido['data'])); ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($pedido['status']); ?></p>
                <p><strong>Total:</strong> R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>

                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Imagem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedido['itens'] as $item): ?>
                            <tr>
                                <td><?php echo $item['nome']; ?></td>
                                <td><?php echo $item['quantidade']; ?></td>
                                <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                                <td>
                                    <img src="<?php echo $item['imagem_url']; ?>" alt="<?php echo $item['nome']; ?>" width="100">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <hr> <!-- Linha horizontal para separar os pedidos -->
        <?php endforeach; ?>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>