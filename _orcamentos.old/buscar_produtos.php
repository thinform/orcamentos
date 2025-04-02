<?php
require_once 'config.php';

try {
    $termo = isset($_GET['termo']) ? $_GET['termo'] : '';
    
    $sql = "SELECT p.*, c.nome as categoria_nome 
            FROM produtos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.codigo LIKE :termo 
               OR p.descricao LIKE :termo 
               OR c.nome LIKE :termo 
            ORDER BY p.descricao";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['termo' => "%{$termo}%"]);
    $produtos = $stmt->fetchAll();
    
    echo '<option value="">Selecione um produto</option>';
    foreach ($produtos as $produto) {
        $preco = number_format($produto['preco_venda'], 2, ',', '.');
        echo "<option value='{$produto['id']}' 
                      data-codigo='{$produto['codigo']}'
                      data-preco='{$produto['preco_venda']}'>
                {$produto['codigo']} - {$produto['descricao']} (R$ {$preco})
              </option>";
    }
} catch(PDOException $e) {
    echo '<option value="">Erro ao carregar produtos</option>';
    registraLog("Erro ao buscar produtos: " . $e->getMessage(), 'error');
} 