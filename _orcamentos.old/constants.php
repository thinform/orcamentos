<?php
// Detecta se está rodando localmente
$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

// Configurações do Banco de Dados
define('DB_HOST', $is_local ? 'localhost' : 'thinforma.mysql.dbaas.com.br');
define('DB_NAME', 'thinforma');
define('DB_USER', 'thinforma');
define('DB_PASS', 'Lordac01#');
define('DB_CHARSET', 'utf8mb4');

// URLs e Caminhos
define('SITE_URL', $is_local ? 'http://localhost/orcamentos' : 'https://orcamentos.thinforma.com.br');
define('UPLOAD_DIR', 'uploads');
define('PRODUTOS_DIR', UPLOAD_DIR . '/produtos');
define('MINIATURAS_DIR', PRODUTOS_DIR . '/miniaturas');
define('ORCAMENTOS_DIR', UPLOAD_DIR . '/orcamentos');

// Configurações de Upload
define('ALLOWED_EXTENSIONS', serialize(array('jpg', 'jpeg', 'png', 'gif')));
define('ALLOWED_MIMES', serialize(array('image/jpeg', 'image/png', 'image/gif')));
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('THUMB_WIDTH', 150);
define('THUMB_HEIGHT', 150);

// Configurações de Orçamento
define('VALIDADE_PADRAO', 15); // dias
define('MARGEM_PADRAO', 30); // porcentagem
define('DESCONTO_MAXIMO', 20); // porcentagem
define('FRETE_GRATIS_VALOR_MINIMO', 1000);
define('PARCELAS_SEM_JUROS', 3);
define('JUROS_MENSAIS', 2.99); // porcentagem

// Configurações de Estoque
define('ESTOQUE_MINIMO', 5);
define('ALERTA_ESTOQUE_BAIXO', true);

// Configurações de Cache
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600); // 1 hora
define('CACHE_DIR', 'cache');

// Configurações de Log
define('LOG_ENABLED', true);
define('LOG_DIR', 'logs');
define('LOG_FILE', 'system.log');
define('ERROR_LOG_FILE', 'error.log');

// Configurações de Formatação
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i:s');
define('DECIMAL_PLACES', 2);
define('DECIMAL_SEPARATOR', ',');
define('THOUSAND_SEPARATOR', '.');
define('MOEDA', 'R$');

// Configurações de Email
define('EMAIL_FROM', 'thinform@gmail.com');
define('EMAIL_FROM_NAME', 'Sistema de Orçamentos - THINFORMA');

// Configurações de Segurança
define('SALT', 'thinforma2024');
define('SESSION_NAME', 'THSESSID');
define('SESSION_LIFETIME', 3600);

// Configurações de Debug
define('DEBUG_MODE', true);
define('ERROR_REPORTING', E_ALL);
define('DISPLAY_ERRORS', true);

// Configurações do Sistema
define('SITE_NAME', 'Sistema de Orçamentos');
define('ITEMS_PER_PAGE', 10);

// Status do Orçamento
define('STATUS_PENDENTE', 'P');
define('STATUS_APROVADO', 'A'); 
define('STATUS_REJEITADO', 'R');
define('STATUS_CANCELADO', 'C');

// Status do Pagamento
define('PAGAMENTO_PENDENTE', 'P');
define('PAGAMENTO_CONFIRMADO', 'C');
define('PAGAMENTO_REJEITADO', 'R');

// Formas de Pagamento
define('PAGAMENTO_DINHEIRO', 'D');
define('PAGAMENTO_CARTAO', 'C');
define('PAGAMENTO_BOLETO', 'B');
define('PAGAMENTO_PIX', 'P');
define('PAGAMENTO_TRANSFERENCIA', 'T');

// Tipos de Pessoa
define('PESSOA_FISICA', 'F');
define('PESSOA_JURIDICA', 'J');

// Configurações de Email
define('EMAIL_HOST', 'smtp.gmail.com');
define('EMAIL_PORT', 587);
define('EMAIL_USER', 'thinform@gmail.com');
define('EMAIL_PASS', 'sua-senha');

// Configurações de Segurança
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_TIME', 900);

// Configurações de Paginação
define('PAGE_LIMIT', 10);
define('PAGE_RANGE', 2);

// Configurações de Validade
define('TOKEN_VALIDADE_HORAS', 24);
define('SENHA_RESET_VALIDADE_HORAS', 1);

// Configurações de Margem
define('MARGEM_MINIMA', 10); // 10%
define('MARGEM_MAXIMA', 200); // 200%

// Configurações de Frete
define('FRETE_PADRAO', 0);

// Configurações de Desconto
define('DESCONTO_AVISTA', 5); // 5%

// Configurações de Parcelas
define('PARCELAS_MAXIMAS', 12);

// Configurações de Data/Hora
define('DEFAULT_TIMEZONE', 'America/Sao_Paulo');
define('TIME_FORMAT', 'H:i:s');

// Configurações de Cache
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600);
define('CACHE_DIR', 'cache');

// Configurações de Log
define('LOG_ENABLED', true);
define('LOG_DIR', 'logs');
define('LOG_FILE', 'system.log');
define('ERROR_LOG_FILE', 'error.log');

// Configurações de Debug
define('DEBUG_MODE', true);
define('DISPLAY_ERRORS', true);
define('ERROR_REPORTING', E_ALL);

// Configurações de Paginação
define('PAGE_LIMIT', 10);
define('PAGE_RANGE', 2);

// Configurações de Validade
define('ORCAMENTO_VALIDADE_DIAS', 15);
define('TOKEN_VALIDADE_HORAS', 24);
define('SENHA_RESET_VALIDADE_HORAS', 1);

// Configurações de Estoque
define('ESTOQUE_MINIMO', 5);
define('ALERTA_ESTOQUE_BAIXO', true);

// Configurações de Margem
define('MARGEM_PADRAO', 30); // 30%
define('MARGEM_MINIMA', 10); // 10%
define('MARGEM_MAXIMA', 200); // 200%

// Configurações de Frete
define('FRETE_PADRAO', 0);
define('FRETE_GRATIS_VALOR_MINIMO', 1000);

// Configurações de Desconto
define('DESCONTO_AVISTA', 5); // 5%

// Configurações de Parcelas
define('PARCELAS_MAXIMAS', 12);
define('PARCELAS_SEM_JUROS', 3);
define('JUROS_MENSAIS', 2.99); // 2.99%

// Configurações de Moeda
define('DECIMAL_SEPARATOR', ',');
define('THOUSAND_SEPARATOR', '.');
define('DECIMAL_PLACES', 2); 