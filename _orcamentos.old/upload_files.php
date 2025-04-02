<?php
// Configurações FTP
$ftp_server = 'ftp.thinforma.com.br';
$ftp_user = 'thinforma';
$ftp_pass = 'Lordac01#Lordac01#';
$ftp_path = '/public_html/orcamentos/';

// Lista de arquivos para upload
$files = array(
    'config.php',
    'constants.php',
    'functions.php',
    'init.php',
    'database.sql',
    'import_database.php',
    'menu.php',
    'header.php',
    'footer.php',
    '404.php',
    '500.php',
    'index.php',
    '.htaccess',
    'README.md',
    'LICENSE.md'
);

// Conecta ao FTP
$conn_id = ftp_connect($ftp_server);
if($conn_id === false) {
    die("Não foi possível conectar ao servidor FTP");
}

// Login
if(!ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    die("Não foi possível fazer login no FTP");
}

// Ativa modo passivo
ftp_pasv($conn_id, true);

// Cria diretório se não existir
@ftp_mkdir($conn_id, $ftp_path);

// Upload dos arquivos
$errors = array();
foreach($files as $file) {
    if(file_exists($file)) {
        if(!ftp_put($conn_id, $ftp_path . $file, $file, FTP_ASCII)) {
            $errors[] = "Erro ao enviar arquivo: {$file}";
        } else {
            echo "Arquivo enviado com sucesso: {$file}<br>";
        }
    } else {
        $errors[] = "Arquivo não encontrado: {$file}";
    }
}

// Cria diretórios necessários
$directories = array(
    'uploads',
    'uploads/produtos',
    'uploads/produtos/miniaturas',
    'uploads/orcamentos',
    'cache',
    'logs',
    'temp'
);

foreach($directories as $dir) {
    $full_path = $ftp_path . $dir;
    if(@ftp_mkdir($conn_id, $full_path)) {
        echo "Diretório criado com sucesso: {$dir}<br>";
        // Define permissões
        ftp_chmod($conn_id, 0755, $full_path);
    }
}

// Fecha conexão
ftp_close($conn_id);

// Exibe erros se houver
if(!empty($errors)) {
    echo "<h2>Erros encontrados:</h2>";
    echo "<pre>" . print_r($errors, true) . "</pre>";
} else {
    echo "<h2>Todos os arquivos foram enviados com sucesso!</h2>";
}

// Próximos passos
echo "<h3>Próximos passos:</h3>";
echo "<ol>";
echo "<li>Acesse <a href='https://orcamentos.thinforma.com.br/init.php' target='_blank'>init.php</a> para inicializar o sistema</li>";
echo "<li>Acesse <a href='https://orcamentos.thinforma.com.br/import_database.php' target='_blank'>import_database.php</a> para criar o banco de dados</li>";
echo "<li>Após concluir, acesse <a href='https://orcamentos.thinforma.com.br' target='_blank'>o sistema</a></li>";
echo "</ol>";
?> 