<?php
// Habilita exibição de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Array com os diretórios necessários
$diretorios = array(
    'uploads',
    'uploads/produtos',
    'uploads/produtos/miniaturas',
    'uploads/orcamentos',
    'cache',
    'logs',
    'temp'
);

// Cria os diretórios se não existirem
foreach($diretorios as $diretorio) {
    if(!file_exists($diretorio)) {
        if(!mkdir($diretorio, 0755, true)) {
            die("Erro ao criar o diretório {$diretorio}");
        }
        echo "Diretório {$diretorio} criado com sucesso<br>";
    } else {
        echo "Diretório {$diretorio} já existe<br>";
    }
}

// Verifica se os arquivos de log existem e são graváveis
$arquivos_log = array(
    'logs/system.log',
    'logs/error.log'
);

foreach($arquivos_log as $arquivo) {
    if(!file_exists($arquivo)) {
        if(!touch($arquivo)) {
            die("Erro ao criar o arquivo {$arquivo}");
        }
        if(!chmod($arquivo, 0644)) {
            die("Erro ao definir permissões do arquivo {$arquivo}");
        }
        echo "Arquivo {$arquivo} criado com sucesso<br>";
    } else {
        echo "Arquivo {$arquivo} já existe<br>";
    }
}

// Verifica a conexão com o banco de dados
try {
    $pdo = new PDO(
        "mysql:host=thinforma.mysql.dbaas.com.br;dbname=thinforma;charset=utf8mb4",
        "thinforma",
        "Lordac01#",
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
    echo "Conexão com o banco de dados estabelecida com sucesso<br>";
} catch(PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Verifica se as tabelas necessárias existem
$tabelas = array(
    'categorias',
    'fornecedores',
    'produtos',
    'historico_precos',
    'orcamentos',
    'itens_orcamento'
);

foreach($tabelas as $tabela) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '{$tabela}'");
        if($stmt->rowCount() > 0) {
            echo "Tabela {$tabela} encontrada<br>";
        } else {
            echo "Tabela {$tabela} não encontrada<br>";
        }
    } catch(PDOException $e) {
        die("Erro ao verificar tabela {$tabela}: " . $e->getMessage());
    }
}

// Verifica as extensões do PHP necessárias
$extensoes = array(
    'pdo',
    'pdo_mysql',
    'gd',
    'curl',
    'mbstring',
    'json',
    'xml'
);

foreach($extensoes as $extensao) {
    if(extension_loaded($extensao)) {
        echo "Extensão {$extensao} está carregada<br>";
    } else {
        echo "ATENÇÃO: Extensão {$extensao} não está carregada<br>";
    }
}

// Verifica as configurações do PHP
$configs = array(
    'upload_max_filesize' => '5M',
    'post_max_size' => '6M',
    'max_execution_time' => '300',
    'max_input_time' => '300',
    'memory_limit' => '128M',
    'display_errors' => DEBUG_MODE ? '1' : '0'
);

foreach($configs as $config => $valor_esperado) {
    $valor_atual = ini_get($config);
    echo "Configuração {$config}: valor atual = {$valor_atual}, valor esperado = {$valor_esperado}<br>";
}

// Verifica permissões de escrita
$diretorios_escrita = array(
    'uploads',
    'cache',
    'logs',
    'temp'
);

foreach($diretorios_escrita as $diretorio) {
    if(is_writable($diretorio)) {
        echo "Diretório {$diretorio} tem permissão de escrita<br>";
    } else {
        echo "ATENÇÃO: Diretório {$diretorio} não tem permissão de escrita<br>";
    }
}

echo "<br>Inicialização concluída!<br>";
?> 