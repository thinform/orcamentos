<?php
// Habilita exibição de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Conecta ao banco de dados
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
    
    echo "Conexão com o banco de dados estabelecida com sucesso!<br>";
    
    // Lê o arquivo SQL
    $sql = file_get_contents('database.sql');
    if($sql === false) {
        throw new Exception("Erro ao ler o arquivo database.sql");
    }
    
    echo "Arquivo SQL lido com sucesso!<br>";
    
    // Divide o SQL em comandos individuais
    $comandos = array_filter(
        array_map(
            'trim',
            explode(';', $sql)
        ),
        'strlen'
    );
    
    echo "Total de comandos SQL: " . count($comandos) . "<br>";
    
    // Executa cada comando
    foreach($comandos as $comando) {
        try {
            $pdo->exec($comando);
            echo "Comando executado com sucesso: " . substr($comando, 0, 50) . "...<br>";
        } catch(PDOException $e) {
            echo "Erro ao executar comando: " . $e->getMessage() . "<br>";
            echo "Comando que falhou: " . $comando . "<br>";
        }
    }
    
    echo "<br>Importação concluída com sucesso!";
    
} catch(Exception $e) {
    die("Erro: " . $e->getMessage());
}
?> 