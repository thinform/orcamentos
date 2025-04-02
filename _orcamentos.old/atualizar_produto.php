<?php
require_once 'config.php';

try {
    // Verifica se o ID foi fornecido
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception("ID do produto não fornecido");
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        throw new Exception("ID do produto inválido");
    }

    // Inicia a transação
    $pdo->beginTransaction();

    // Busca informações atuais do produto
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $produto = $stmt->fetch();

    if (!$produto) {
        throw new Exception("Produto não encontrado");
    }

    // Recebe e valida os dados do formulário
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
    $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
    $fornecedor_id = filter_input(INPUT_POST, 'fornecedor_id', FILTER_VALIDATE_INT);
    $preco_custo = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['preco_custo']);
    $margem = filter_input(INPUT_POST, 'margem', FILTER_VALIDATE_FLOAT);
    $preco_venda = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['preco_venda']);
    $estoque_atual = filter_input(INPUT_POST, 'estoque_atual', FILTER_VALIDATE_FLOAT);
    $estoque_minimo = filter_input(INPUT_POST, 'estoque_minimo', FILTER_VALIDATE_FLOAT);
    $unidade = filter_input(INPUT_POST, 'unidade', FILTER_SANITIZE_STRING);

    // Validações básicas
    if (!$nome || !$codigo || !$categoria_id || !$fornecedor_id || 
        !$preco_custo || !$margem || !$preco_venda || 
        !$estoque_atual || !$estoque_minimo || !$unidade) {
        throw new Exception("Todos os campos obrigatórios devem ser preenchidos");
    }

    // Tratamento da imagem
    $imagem_path = $produto['imagem'];

    // Se marcou para remover a imagem
    if (isset($_POST['remover_imagem']) && $_POST['remover_imagem'] == 'on') {
        if ($imagem_path) {
            // Remove a imagem e sua miniatura
            if (file_exists($imagem_path)) {
                unlink($imagem_path);
            }
            $miniatura_path = 'uploads/miniaturas/' . basename($imagem_path);
            if (file_exists($miniatura_path)) {
                unlink($miniatura_path);
            }
            $imagem_path = null;
        }
    }
    // Se enviou uma nova imagem
    elseif (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        // Validar tamanho
        if ($_FILES['imagem']['size'] > 2 * 1024 * 1024) { // 2MB
            throw new Exception("A imagem deve ter no máximo 2MB");
        }

        // Validar extensão
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($ext, $permitidos)) {
            throw new Exception("Tipo de arquivo não permitido. Apenas: " . implode(', ', $permitidos));
        }

        // Gerar nome único para a imagem
        $nova_imagem = 'uploads/produtos/' . uniqid() . '.' . $ext;
        
        // Criar diretório se não existir
        if (!file_exists('uploads/produtos')) {
            mkdir('uploads/produtos', 0777, true);
        }

        // Fazer upload
        if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $nova_imagem)) {
            throw new Exception("Erro ao fazer upload da imagem");
        }

        // Criar miniatura
        $miniatura_path = 'uploads/miniaturas/' . basename($nova_imagem);
        if (!file_exists('uploads/miniaturas')) {
            mkdir('uploads/miniaturas', 0777, true);
        }

        // Redimensionar imagem para miniatura
        list($width, $height) = getimagesize($nova_imagem);
        $ratio = $width / $height;
        $new_width = 150;
        $new_height = 150 / $ratio;

        $thumb = imagecreatetruecolor($new_width, $new_height);
        
        switch($ext) {
            case 'jpg':
            case 'jpeg':
                $source = imagecreatefromjpeg($nova_imagem);
                break;
            case 'png':
                $source = imagecreatefrompng($nova_imagem);
                break;
            case 'gif':
                $source = imagecreatefromgif($nova_imagem);
                break;
        }

        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        
        switch($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($thumb, $miniatura_path, 80);
                break;
            case 'png':
                imagepng($thumb, $miniatura_path, 8);
                break;
            case 'gif':
                imagegif($thumb, $miniatura_path);
                break;
        }

        // Remove imagens antigas se existirem
        if ($produto['imagem']) {
            if (file_exists($produto['imagem'])) {
                unlink($produto['imagem']);
            }
            $miniatura_antiga = 'uploads/miniaturas/' . basename($produto['imagem']);
            if (file_exists($miniatura_antiga)) {
                unlink($miniatura_antiga);
            }
        }

        $imagem_path = $nova_imagem;
    }

    // Verificar se houve alteração no preço
    if ($preco_custo != $produto['preco_custo'] || 
        $margem != $produto['margem_lucro'] || 
        $preco_venda != $produto['preco_venda']) {
        
        // Registrar novo preço no histórico
        $sql = "INSERT INTO historico_precos (produto_id, preco_custo, margem_lucro, preco_venda, data_alteracao) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $preco_custo, $margem, $preco_venda]);
    }

    // Atualizar produto
    $sql = "UPDATE produtos SET 
            nome = ?, descricao = ?, codigo = ?, categoria_id = ?, 
            fornecedor_id = ?, preco_custo = ?, margem_lucro = ?, 
            preco_venda = ?, estoque_atual = ?, estoque_minimo = ?, 
            unidade = ?, imagem = ? 
            WHERE id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nome, $descricao, $codigo, $categoria_id,
        $fornecedor_id, $preco_custo, $margem, $preco_venda,
        $estoque_atual, $estoque_minimo, $unidade,
        $imagem_path, $id
    ]);

    // Commit da transação
    $pdo->commit();

    $_SESSION['sucesso'] = "Produto atualizado com sucesso!";
    header("Location: listar_produtos.php");
    exit();

} catch (Exception $e) {
    // Rollback em caso de erro
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Remove a nova imagem se foi feito upload
    if (isset($nova_imagem) && file_exists($nova_imagem)) {
        unlink($nova_imagem);
    }
    if (isset($miniatura_path) && file_exists($miniatura_path)) {
        unlink($miniatura_path);
    }

    $_SESSION['erro'] = "Erro ao atualizar produto: " . $e->getMessage();
    header("Location: editar_produto.php?id=" . $id);
    exit();
} 