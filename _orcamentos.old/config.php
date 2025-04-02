<?php
// Inicia a sessão
session_start();

// Carrega as constantes
require_once 'constants.php';

// Configurações de erro
error_reporting(ERROR_REPORTING);
ini_set('display_errors', DISPLAY_ERRORS ? 1 : 0);

// Configuração do fuso horário
date_default_timezone_set(DEFAULT_TIMEZONE);

// Configurações da sessão
ini_set('session.name', SESSION_NAME);
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
ini_set('session.cookie_lifetime', SESSION_LIFETIME);

// Configurações de cabeçalho
header('Content-Type: text/html; charset=utf-8');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');

// Carrega as funções utilitárias
require_once 'functions.php';

// Conexão com o banco de dados usando PDO
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
} catch(PDOException $e) {
    if(DEBUG_MODE) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    } else {
        die("Erro na conexão com o banco de dados. Por favor, tente novamente mais tarde.");
    }
}

// Cria diretórios necessários se não existirem
$diretorios = array(
    UPLOAD_DIR,
    PRODUTOS_DIR,
    MINIATURAS_DIR,
    ORCAMENTOS_DIR,
    CACHE_DIR,
    LOG_DIR
);

foreach($diretorios as $diretorio) {
    if(!file_exists($diretorio)) {
        mkdir($diretorio, 0777, true);
    }
}

// Função para registrar logs
function registraLog($mensagem, $tipo = 'info') {
    if(!LOG_ENABLED) return;

    $data = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $usuario = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Não autenticado';
    
    $log = "[{$data}] [{$tipo}] [{$ip}] [{$usuario}] {$mensagem}\n";
    
    $arquivo = LOG_DIR . '/' . ($tipo == 'error' ? ERROR_LOG_FILE : LOG_FILE);
    file_put_contents($arquivo, $log, FILE_APPEND);
}

// Função para limpar o cache
function limpaCache($chave = null) {
    if(!CACHE_ENABLED) return;

    if($chave === null) {
        array_map('unlink', glob(CACHE_DIR . '/*'));
    } else {
        $arquivo = CACHE_DIR . '/' . md5($chave);
        if(file_exists($arquivo)) {
            unlink($arquivo);
        }
    }
}

// Função para obter dados do cache
function getCache($chave) {
    if(!CACHE_ENABLED) return false;

    $arquivo = CACHE_DIR . '/' . md5($chave);
    
    if(file_exists($arquivo)) {
        $conteudo = file_get_contents($arquivo);
        $dados = unserialize($conteudo);
        
        if($dados['expira'] > time()) {
            return $dados['valor'];
        }
        
        unlink($arquivo);
    }
    
    return false;
}

// Função para definir dados no cache
function setCache($chave, $valor, $tempo = null) {
    if(!CACHE_ENABLED) return;

    $tempo = $tempo ?? CACHE_LIFETIME;
    $arquivo = CACHE_DIR . '/' . md5($chave);
    
    $dados = array(
        'valor' => $valor,
        'expira' => time() + $tempo
    );
    
    file_put_contents($arquivo, serialize($dados));
}

// Função para calcular o preço de venda
function calculaPrecoVenda($preco_custo, $margem = null) {
    $margem = $margem ?? MARGEM_PADRAO;
    return $preco_custo * (1 + ($margem / 100));
}

// Função para calcular o desconto
function calculaDesconto($valor, $desconto) {
    if($desconto > DESCONTO_MAXIMO) {
        $desconto = DESCONTO_MAXIMO;
    }
    return $valor * ($desconto / 100);
}

// Função para calcular o valor das parcelas
function calculaParcelas($valor, $parcelas) {
    if($parcelas <= PARCELAS_SEM_JUROS) {
        return $valor / $parcelas;
    }
    
    $juros = JUROS_MENSAIS / 100;
    return $valor * (($juros * pow(1 + $juros, $parcelas)) / (pow(1 + $juros, $parcelas) - 1));
}

// Função para verificar se o frete é grátis
function verificaFreteGratis($valor) {
    return $valor >= FRETE_GRATIS_VALOR_MINIMO;
}

// Função para verificar estoque baixo
function verificaEstoqueBaixo($quantidade) {
    return ALERTA_ESTOQUE_BAIXO && $quantidade <= ESTOQUE_MINIMO;
}

// Função para gerar token único
function geraToken() {
    return bin2hex(random_bytes(32));
}

// Função para gerar senha aleatória
function geraSenha($tamanho = 8) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=';
    $senha = '';
    
    for($i = 0; $i < $tamanho; $i++) {
        $senha .= $caracteres[random_int(0, strlen($caracteres) - 1)];
    }
    
    return $senha;
}

// Função para criptografar senha
function criptografaSenha($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}

// Função para verificar senha
function verificaSenha($senha, $hash) {
    return password_verify($senha, $hash);
}

// Função para formatar número do orçamento
function formataNumeroOrcamento($numero) {
    return str_pad($numero, 6, '0', STR_PAD_LEFT);
}

// Função para calcular prazo de entrega
function calculaPrazoEntrega($data_pedido, $prazo_dias) {
    $data = new DateTime($data_pedido);
    $data->add(new DateInterval("P{$prazo_dias}D"));
    
    while(!verificaDiaUtil($data)) {
        $data->add(new DateInterval('P1D'));
    }
    
    return $data->format('Y-m-d');
}

// Função para verificar se é dia útil
function verificaDiaUtil($data) {
    if(is_string($data)) {
        $data = new DateTime($data);
    }
    
    $dia_semana = $data->format('N');
    return $dia_semana >= 1 && $dia_semana <= 5;
}

// Função para adicionar dias úteis
function adicionaDiasUteis($data, $dias) {
    if(is_string($data)) {
        $data = new DateTime($data);
    }
    
    $dias_adicionados = 0;
    
    while($dias_adicionados < $dias) {
        $data->add(new DateInterval('P1D'));
        
        if(verificaDiaUtil($data)) {
            $dias_adicionados++;
        }
    }
    
    return $data->format('Y-m-d');
}

// Função para calcular idade
function calculaIdade($data_nascimento) {
    $data = new DateTime($data_nascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($data);
    return $idade->y;
}

// Função para buscar endereço por CEP
function buscaEnderecoCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
    
    if(strlen($cep) != 8) {
        return false;
    }
    
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $dados = json_decode($response, true);
    
    if(isset($dados['erro'])) {
        return false;
    }
    
    return $dados;
}

// Função para formatar tamanho de arquivo
function formataTamanhoArquivo($bytes) {
    $unidades = array('B', 'KB', 'MB', 'GB', 'TB');
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($unidades) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, 2) . ' ' . $unidades[$pow];
}

// Função para gerar código aleatório
function geraCodigoAleatorio($tamanho = 6) {
    return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $tamanho));
}

// Função para redimensionar imagem
function redimensionaImagem($arquivo, $largura = 800, $altura = 600) {
    if(!file_exists($arquivo)) {
        return false;
    }
    
    $info = getimagesize($arquivo);
    if($info === false) {
        return false;
    }
    
    list($largura_original, $altura_original) = $info;
    $tipo = $info[2];
    
    if($largura_original <= $largura && $altura_original <= $altura) {
        return true;
    }
    
    $ratio_original = $largura_original / $altura_original;
    
    if(($largura / $altura) > $ratio_original) {
        $largura = $altura * $ratio_original;
    } else {
        $altura = $largura / $ratio_original;
    }
    
    $imagem_nova = imagecreatetruecolor($largura, $altura);
    
    switch($tipo) {
        case IMAGETYPE_JPEG:
            $imagem_original = imagecreatefromjpeg($arquivo);
            break;
        case IMAGETYPE_PNG:
            $imagem_original = imagecreatefrompng($arquivo);
            imagealphablending($imagem_nova, false);
            imagesavealpha($imagem_nova, true);
            break;
        case IMAGETYPE_GIF:
            $imagem_original = imagecreatefromgif($arquivo);
            break;
        default:
            return false;
    }
    
    if($imagem_original === false) {
        return false;
    }
    
    if(imagecopyresampled(
        $imagem_nova, $imagem_original,
        0, 0, 0, 0,
        $largura, $altura,
        $largura_original, $altura_original
    ) === false) {
        return false;
    }
    
    switch($tipo) {
        case IMAGETYPE_JPEG:
            $resultado = imagejpeg($imagem_nova, $arquivo, 90);
            break;
        case IMAGETYPE_PNG:
            $resultado = imagepng($imagem_nova, $arquivo, 9);
            break;
        case IMAGETYPE_GIF:
            $resultado = imagegif($imagem_nova, $arquivo);
            break;
    }
    
    imagedestroy($imagem_original);
    imagedestroy($imagem_nova);
    
    return $resultado;
}

// Função para gerar miniatura
function geraMiniatura($arquivo_origem, $arquivo_destino, $largura = 150, $altura = 150) {
    if(!file_exists($arquivo_origem)) {
        return false;
    }
    
    $info = getimagesize($arquivo_origem);
    if($info === false) {
        return false;
    }
    
    list($largura_original, $altura_original) = $info;
    $tipo = $info[2];
    
    $ratio_original = $largura_original / $altura_original;
    
    if(($largura / $altura) > $ratio_original) {
        $largura_final = $altura * $ratio_original;
        $altura_final = $altura;
    } else {
        $largura_final = $largura;
        $altura_final = $largura / $ratio_original;
    }
    
    $imagem_nova = imagecreatetruecolor($largura_final, $altura_final);
    
    switch($tipo) {
        case IMAGETYPE_JPEG:
            $imagem_original = imagecreatefromjpeg($arquivo_origem);
            break;
        case IMAGETYPE_PNG:
            $imagem_original = imagecreatefrompng($arquivo_origem);
            imagealphablending($imagem_nova, false);
            imagesavealpha($imagem_nova, true);
            break;
        case IMAGETYPE_GIF:
            $imagem_original = imagecreatefromgif($arquivo_origem);
            break;
        default:
            return false;
    }
    
    if($imagem_original === false) {
        return false;
    }
    
    if(imagecopyresampled(
        $imagem_nova, $imagem_original,
        0, 0, 0, 0,
        $largura_final, $altura_final,
        $largura_original, $altura_original
    ) === false) {
        return false;
    }
    
    switch($tipo) {
        case IMAGETYPE_JPEG:
            $resultado = imagejpeg($imagem_nova, $arquivo_destino, 90);
            break;
        case IMAGETYPE_PNG:
            $resultado = imagepng($imagem_nova, $arquivo_destino, 9);
            break;
        case IMAGETYPE_GIF:
            $resultado = imagegif($imagem_nova, $arquivo_destino);
            break;
    }
    
    imagedestroy($imagem_original);
    imagedestroy($imagem_nova);
    
    return $resultado;
} 