<?php
require_once 'config.php';

try {
    // Iniciar transação
    $pdo->beginTransaction();

    // Processar upload da imagem
    $imagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $arquivo = $_FILES['imagem'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        
        // Validar extensão
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extensao, $extensoes_permitidas)) {
            throw new Exception('Formato de imagem não permitido');
        }
        
        // Validar tamanho
        if ($arquivo['size'] > 2 * 1024 * 1024) { // 2MB
            throw new Exception('A imagem deve ter no máximo 2MB');
        }
        
        // Gerar nome único para o arquivo
        $nome_arquivo = uniqid() . '.' . $extensao;
        $caminho_arquivo = 'uploads/' . $nome_arquivo;
        
        // Mover o arquivo
        if (!move_uploaded_file($arquivo['tmp_name'], $caminho_arquivo)) {
            throw new Exception('Erro ao fazer upload da imagem');
        }
        
        // Criar miniatura
        $largura_miniatura = 100;
        $altura_miniatura = 100;
        
        list($largura_original, $altura_original) = getimagesize($caminho_arquivo);
        
        // Calcular proporção
        if ($largura_original > $altura_original) {
            $altura_miniatura = ($altura_original / $largura_original) * $largura_miniatura;
        } else {
            $largura_miniatura = ($largura_original / $altura_original) * $altura_miniatura;
        }
        
        // Criar imagem miniatura
        $miniatura = imagecreatetruecolor($largura_miniatura, $altura_miniatura);
        
        switch ($extensao) {
            case 'jpg':
            case 'jpeg':
                $origem = imagecreatefromjpeg($caminho_arquivo);
                break;
            case 'png':
                $origem = imagecreatefrompng($caminho_arquivo);
                break;
            case 'gif':
                $origem = imagecreatefromgif($caminho_arquivo);
                break;
        }
        
        // Redimensionar
        imagecopyresampled(
            $miniatura, $origem,
            0, 0, 0, 0,
            $largura_miniatura, $altura_miniatura,
            $largura_original, $altura_original
        );
        
        // Salvar miniatura
        $caminho_miniatura = 'uploads/miniaturas/' . $nome_arquivo;
        switch ($extensao) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($miniatura, $caminho_miniatura, 90);
                break;
            case 'png':
                imagepng($miniatura, $caminho_miniatura, 9);
                break;
            case 'gif':
                imagegif($miniatura, $caminho_miniatura);
                break;
        }
        
        // Liberar memória
        imagedestroy($miniatura);
        imagedestroy($origem);
        
        $imagem = $nome_arquivo;
    }

    // Preparar dados do produto
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_SANITIZE_NUMBER_INT);
    $fornecedor_id = filter_input(INPUT_POST, 'fornecedor_id', FILTER_SANITIZE_NUMBER_INT);
    $preco_custo = str_replace(['R$ ', '.', ','], ['', '', '.'], $_POST['preco_custo']);
    $margem_lucro = filter_input(INPUT_POST, 'margem_lucro', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $preco_venda = str_replace(['R$ ', '.', ','], ['', '', '.'], $_POST['preco_venda']);
    $unidade = filter_input(INPUT_POST, 'unidade', FILTER_SANITIZE_STRING);
    $estoque_atual = filter_input(INPUT_POST, 'estoque_atual', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $estoque_minimo = filter_input(INPUT_POST, 'estoque_minimo', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Validar dados obrigatórios
    if (!$nome || !$codigo || !$categoria_id || !$fornecedor_id || !$preco_custo || !$margem_lucro || !$preco_venda || !$unidade) {
        throw new Exception('Todos os campos obrigatórios devem ser preenchidos');
    }

    // Inserir produto
    $sql = "INSERT INTO produtos (
        nome, codigo, descricao, categoria_id, fornecedor_id,
        preco_custo, margem_lucro, preco_venda, unidade,
        estoque_atual, estoque_minimo, imagem
    ) VALUES (
        :nome, :codigo, :descricao, :categoria_id, :fornecedor_id,
        :preco_custo, :margem_lucro, :preco_venda, :unidade,
        :estoque_atual, :estoque_minimo, :imagem
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nome' => $nome,
        'codigo' => $codigo,
        'descricao' => $descricao,
        'categoria_id' => $categoria_id,
        'fornecedor_id' => $fornecedor_id,
        'preco_custo' => $preco_custo,
        'margem_lucro' => $margem_lucro,
        'preco_venda' => $preco_venda,
        'unidade' => $unidade,
        'estoque_atual' => $estoque_atual,
        'estoque_minimo' => $estoque_minimo,
        'imagem' => $imagem
    ]);

    $produto_id = $pdo->lastInsertId();

    // Registrar histórico de preços
    $sql = "INSERT INTO historico_precos (
        produto_id, preco_custo, margem_lucro, preco_venda
    ) VALUES (
        :produto_id, :preco_custo, :margem_lucro, :preco_venda
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'produto_id' => $produto_id,
        'preco_custo' => $preco_custo,
        'margem_lucro' => $margem_lucro,
        'preco_venda' => $preco_venda
    ]);

    // Commit da transação
    $pdo->commit();

    $_SESSION['sucesso'] = 'Produto cadastrado com sucesso!';
    header('Location: listar_produtos.php');
    exit;

} catch (Exception $e) {
    // Rollback em caso de erro
    $pdo->rollBack();

    // Se houver imagem, remover
    if (isset($imagem)) {
        @unlink('uploads/' . $imagem);
        @unlink('uploads/miniaturas/' . $imagem);
    }

    $_SESSION['erro'] = 'Erro ao cadastrar produto: ' . $e->getMessage();
    header('Location: cadastrar_produto.php');
    exit;
} 