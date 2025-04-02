<?php
require_once 'conexao.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listar_orcamentos.php');
    exit();
}

try {
    $conn->beginTransaction();

    // Obter dados do formulário
    $orcamento_id = $_POST['orcamento_id'];
    $observacoes = $_POST['observacoes'] ?? '';
    
    // Converter valores monetários
    $valor_frete = str_replace(['.', ','], ['', '.'], $_POST['valor_frete'] ?? '0');
    $desconto_aplicado = str_replace(['.', ','], ['', '.'], $_POST['desconto_aplicado'] ?? '0');
    
    // Converter para float
    $valor_frete = (float)$valor_frete;
    $desconto_aplicado = (float)$desconto_aplicado;

    // Validar dados
    if (!is_numeric($orcamento_id) || $orcamento_id <= 0) {
        throw new Exception('ID do orçamento inválido');
    }

    // Atualizar itens do orçamento
    $produto_ids = $_POST['produto_id'] ?? [];
    $quantidades = $_POST['quantidade'] ?? [];
    $valores_unitarios = array_map(function($valor) {
        return (float)str_replace(['.', ','], ['', '.'], $valor);
    }, $_POST['valor_unitario'] ?? []);

    // Calcular subtotal
    $subtotal = 0;
    foreach ($valores_unitarios as $i => $valor_unitario) {
        $quantidade = (float)$quantidades[$i];
        $subtotal += $valor_unitario * $quantidade;
    }

    // Calcular valor total
    $valor_total = $subtotal + $valor_frete - $desconto_aplicado;

    if ($valor_total < 0) {
        throw new Exception('O valor total do orçamento não pode ser negativo');
    }

    // Atualizar itens do orçamento
    foreach ($produto_ids as $i => $produto_id) {
        $quantidade = (float)$quantidades[$i];
        $valor_unitario = $valores_unitarios[$i];
        $valor_total_item = $quantidade * $valor_unitario;

        $sql = "UPDATE itens_orcamento SET 
                quantidade = ?,
                valor_unitario = ?,
                valor_total = ?
                WHERE orcamento_id = ? AND produto_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $quantidade,
            $valor_unitario,
            $valor_total_item,
            $orcamento_id,
            $produto_id
        ]);
    }

    // Atualizar orçamento
    $sql = "UPDATE orcamentos SET 
            valor_frete = ?,
            desconto_aplicado = ?,
            valor_total = ?,
            subtotal = ?,
            observacoes = ?
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $valor_frete,
        $desconto_aplicado,
        $valor_total,
        $subtotal,
        $observacoes,
        $orcamento_id
    ]);

    $conn->commit();
    header('Location: listar_orcamentos.php?success=update');
    exit();

} catch (Exception $e) {
    $conn->rollBack();
    header('Location: editar_orcamento.php?id=' . $orcamento_id . '&error=' . urlencode($e->getMessage()));
    exit();
}
?> 