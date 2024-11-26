<?php
session_start();
date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para Brasília

$conn = new mysqli("localhost", "root", "", "sneakerland");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o botão de finalizar compra foi pressionado e se o método de pagamento foi selecionado
if (isset($_POST['finalizar_compra']) && !empty($_POST['payment_method']) && !empty($_SESSION['carrinho'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];

    // Verifica se os campos do endereço estão preenchidos
    if (empty($endereco) || empty($cidade) || empty($estado) || empty($cep)) {
        $_SESSION['error_message'] = "Todos os campos de endereço devem ser preenchidos.";
        header("Location: pagamento.php");
        exit;
    }

    // Verificar se o endereço já existe
    $sqlCheckEndereco = "SELECT id FROM enderecos_entrega WHERE usuario_id = ? AND endereco = ? AND cidade = ? AND estado = ? AND cep = ?";
    $stmtCheckEndereco = $conn->prepare($sqlCheckEndereco);
    $stmtCheckEndereco->bind_param("issss", $usuario_id, $endereco, $cidade, $estado, $cep);
    $stmtCheckEndereco->execute();
    $resultCheckEndereco = $stmtCheckEndereco->get_result();

    if ($resultCheckEndereco->num_rows > 0) {
        $row = $resultCheckEndereco->fetch_assoc();
        $endereco_entrega_id = $row['id'];
    } else {
        $sqlEndereco = "INSERT INTO enderecos_entrega (usuario_id, endereco, cidade, estado, cep) VALUES (?, ?, ?, ?, ?)";
        $stmtEndereco = $conn->prepare($sqlEndereco);
        $stmtEndereco->bind_param("issss", $usuario_id, $endereco, $cidade, $estado, $cep);
        $stmtEndereco->execute();
        $endereco_entrega_id = $conn->insert_id;
    }

    // Continue o restante do código da finalização da compra...
    $payment_method = $_POST['payment_method'];

    if ($payment_method === "credit-debit") {
        if (empty($_POST['card_name']) || empty($_POST['card_number']) || empty($_POST['card_expiry']) || empty($_POST['card_cvv'])) {
            $_SESSION['error_message'] = "Por favor, preencha todos os dados do cartão.";
            header("Location: pagamento.php");
            exit;
        }
    }

    $total = 0;
    $itens = []; // Array para armazenar os itens do pedido

    foreach ($_SESSION['carrinho'] as $produtoId => $item) {
        $sql = "SELECT preco, estoque FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $produtoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $produto = $result->fetch_assoc();

        // Verifica se o produto está em estoque suficiente
        if ($produto['estoque'] <= 0 || $produto['estoque'] < $item['quantidade']) {
            $_SESSION['error_message'] = "O produto " . $produtoId . " está sem estoque ou possui quantidade insuficiente.";
            header("Location: pagamento.php");
            exit;
        }

        $subtotal = $produto['preco'] * $item['quantidade'];
        $total += $subtotal;

        // Atualiza o estoque do produto
        $novoEstoque = max(0, $produto['estoque'] - $item['quantidade']);

        // Armazena os detalhes do item
        $itens[] = [
            'produto_id' => $produtoId,
            'quantidade' => $item['quantidade'],
            'preco' => $produto['preco']
        ];

        // Atualiza a quantidade do estoque sem remover o produto
        $updateEstoque = $conn->prepare("UPDATE produtos SET estoque = ? WHERE id = ?");
        $updateEstoque->bind_param("ii", $novoEstoque, $produtoId);
        $updateEstoque->execute();
    }

    $data = date("Y-m-d H:i:s");
    $status = 'Pendente';

    $insertPedido = $conn->prepare("INSERT INTO pedidos (usuario_id, endereco_entrega_id, data, status, total) VALUES (?, ?, ?, ?, ?)");
    $insertPedido->bind_param("iissd", $usuario_id, $endereco_entrega_id, $data, $status, $total);

    if ($insertPedido->execute()) {
        $pedido_id = $insertPedido->insert_id;

        $insertItem = $conn->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco) VALUES (?, ?, ?, ?)");
        foreach ($itens as $item) {
            $insertItem->bind_param("iiid", $pedido_id, $item['produto_id'], $item['quantidade'], $item['preco']);
            $insertItem->execute();
        }

        $_SESSION['carrinho'] = [];
        $_SESSION['success_message'] = "Compra realizada com sucesso!";
        header("Location: HomeUsuario.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Erro ao finalizar o pedido: " . $conn->error;
        header("Location: pagamento.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Por favor, selecione um método de pagamento e preencha os dados do cartão, se aplicável.";
    header("Location: pagamento.php");
    exit;
}

$conn->close();
?>
