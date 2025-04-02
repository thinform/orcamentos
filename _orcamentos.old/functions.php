<?php
require_once 'config.php';

/**
 * Formata um valor monetário para exibição
 */
function formataMoeda($valor) {
    return MOEDA . ' ' . number_format($valor, DECIMAL_PLACES, DECIMAL_SEPARATOR, THOUSAND_SEPARATOR);
}

/**
 * Formata uma data do banco para exibição
 */
function formataData($data) {
    return date(DATE_FORMAT, strtotime($data));
}

/**
 * Formata uma data e hora do banco para exibição
 */
function formataDataHora($data) {
    return date(DATETIME_FORMAT, strtotime($data));
}

/**
 * Converte uma data do formato brasileiro para o formato do banco
 */
function converteDataParaBanco($data) {
    if(empty($data)) return null;
    $partes = explode('/', $data);
    if(count($partes) != 3) return null;
    return "{$partes[2]}-{$partes[1]}-{$partes[0]}";
}

/**
 * Limpa uma string removendo caracteres especiais
 */
function limpaString($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

/**
 * Verifica se uma string está vazia
 */
function isEmpty($str) {
    return (!isset($str) || trim($str) === '');
}

/**
 * Gera um slug a partir de uma string
 */
function geraSlug($texto) {
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
    $texto = preg_replace('/[^a-zA-Z0-9\s]/', '', $texto);
    $texto = strtolower(trim($texto));
    $texto = preg_replace('/[\s]+/', '-', $texto);
    return $texto;
}

/**
 * Verifica se o usuário está logado
 */
function verificaLogin() {
    if(!isset($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Gera um token CSRF
 */
function geraTokenCSRF() {
    if(!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida um token CSRF
 */
function validaTokenCSRF($token) {
    if(!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        header('HTTP/1.1 403 Forbidden');
        exit('Token CSRF inválido');
    }
}

/**
 * Retorna uma mensagem de erro formatada
 */
function mensagemErro($mensagem) {
    return "<div class='alert alert-danger'>{$mensagem}</div>";
}

/**
 * Retorna uma mensagem de sucesso formatada
 */
function mensagemSucesso($mensagem) {
    return "<div class='alert alert-success'>{$mensagem}</div>";
}

/**
 * Retorna uma mensagem de alerta formatada
 */
function mensagemAlerta($mensagem) {
    return "<div class='alert alert-warning'>{$mensagem}</div>";
}

/**
 * Retorna uma mensagem de informação formatada
 */
function mensagemInfo($mensagem) {
    return "<div class='alert alert-info'>{$mensagem}</div>";
}

/**
 * Faz upload de um arquivo
 */
function uploadArquivo($arquivo, $pasta, $tipos_permitidos = array()) {
    if(!isset($arquivo['error']) || $arquivo['error'] !== 0) {
        return false;
    }

    if(!empty($tipos_permitidos) && !in_array($arquivo['type'], $tipos_permitidos)) {
        return false;
    }

    $nome_arquivo = uniqid() . '_' . $arquivo['name'];
    $caminho_completo = $pasta . '/' . $nome_arquivo;

    if(!move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
        return false;
    }

    return $nome_arquivo;
}

/**
 * Remove um arquivo
 */
function removeArquivo($arquivo, $pasta) {
    $caminho_completo = $pasta . '/' . $arquivo;
    if(file_exists($caminho_completo)) {
        return unlink($caminho_completo);
    }
    return false;
}

/**
 * Retorna o status do orçamento formatado
 */
function formataStatusOrcamento($status) {
    $status_formatado = array(
        'P' => '<span class="badge bg-warning">Pendente</span>',
        'A' => '<span class="badge bg-success">Aprovado</span>',
        'R' => '<span class="badge bg-danger">Rejeitado</span>',
        'C' => '<span class="badge bg-secondary">Cancelado</span>'
    );
    return isset($status_formatado[$status]) ? $status_formatado[$status] : '';
}

/**
 * Retorna o status do pagamento formatado
 */
function formataStatusPagamento($status) {
    $status_formatado = array(
        'P' => '<span class="badge bg-warning">Pendente</span>',
        'C' => '<span class="badge bg-success">Confirmado</span>',
        'R' => '<span class="badge bg-danger">Rejeitado</span>'
    );
    return isset($status_formatado[$status]) ? $status_formatado[$status] : '';
}

/**
 * Retorna a forma de pagamento formatada
 */
function formataFormaPagamento($forma) {
    $formas = array(
        'D' => 'Dinheiro',
        'C' => 'Cartão de Crédito',
        'B' => 'Boleto',
        'P' => 'PIX',
        'T' => 'Transferência'
    );
    return isset($formas[$forma]) ? $formas[$forma] : '';
}

/**
 * Retorna o tipo de pessoa formatado
 */
function formataTipoPessoa($tipo) {
    return $tipo == 'F' ? 'Física' : 'Jurídica';
}

/**
 * Formata um CPF/CNPJ
 */
function formataCPFCNPJ($str) {
    $str = preg_replace('/[^0-9]/', '', $str);
    if(strlen($str) === 11) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $str);
    }
    return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $str);
}

/**
 * Valida um CPF
 */
function validaCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if(strlen($cpf) != 11) {
        return false;
    }
    
    if(preg_match('/^(\d)\1+$/', $cpf)) {
        return false;
    }
    
    for($t = 9; $t < 11; $t++) {
        for($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        
        $d = ((10 * $d) % 11) % 10;
        
        if($cpf[$c] != $d) {
            return false;
        }
    }
    
    return true;
}

/**
 * Valida um CNPJ
 */
function validaCNPJ($cnpj) {
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    
    if(strlen($cnpj) != 14) {
        return false;
    }
    
    if(preg_match('/^(\d)\1+$/', $cnpj)) {
        return false;
    }
    
    $t = 12;
    $d = 0;
    
    for($i = 0; $i < $t; $i++) {
        $d += $cnpj[$i] * (($t + 1 - $i) >= 10 ? ($t + 1 - $i) - 8 : ($t + 1 - $i));
    }
    
    $d = ((10 * $d) % 11) % 10;
    
    if($cnpj[$t] != $d) {
        return false;
    }
    
    $t = 13;
    $d = 0;
    
    for($i = 0; $i < $t; $i++) {
        $d += $cnpj[$i] * (($t + 1 - $i) >= 10 ? ($t + 1 - $i) - 8 : ($t + 1 - $i));
    }
    
    $d = ((10 * $d) % 11) % 10;
    
    if($cnpj[$t] != $d) {
        return false;
    }
    
    return true;
}

/**
 * Valida um email
 */
function validaEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Valida um telefone
 */
function validaTelefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    return strlen($telefone) >= 10 && strlen($telefone) <= 11;
}

/**
 * Valida um CEP
 */
function validaCEP($cep) {
    $cep = preg_replace('/[^0-9]/', '', $cep);
    return strlen($cep) == 8;
}

/**
 * Valida uma data
 */
function validaData($data) {
    if(empty($data)) return false;
    
    $partes = explode('/', $data);
    if(count($partes) != 3) return false;
    
    return checkdate($partes[1], $partes[0], $partes[2]);
}

/**
 * Valida extensão de arquivo
 */
function validaExtensaoArquivo($nome_arquivo) {
    $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
    return in_array($extensao, EXTENSOES_PERMITIDAS);
}

/**
 * Valida tipo de arquivo
 */
function validaTipoArquivo($tipo) {
    return in_array($tipo, TIPOS_PERMITIDOS);
}

/**
 * Formata um telefone
 */
function formataTelefone($str) {
    $str = preg_replace("/[^0-9]/", "", $str);
    if(strlen($str) === 11) {
        return preg_replace("/(\d{2})(\d{5})(\d{4})/", "(\$1) \$2-\$3", $str);
    } else if(strlen($str) === 10) {
        return preg_replace("/(\d{2})(\d{4})(\d{4})/", "(\$1) \$2-\$3", $str);
    }
    return $str;
}

/**
 * Formata um CEP
 */
function formataCEP($str) {
    $str = preg_replace("/[^0-9]/", "", $str);
    return preg_replace("/(\d{5})(\d{3})/", "\$1-\$2", $str);
}

/**
 * Retorna o nome do mês
 */
function getNomeMes($mes) {
    $meses = array(
        1 => 'Janeiro',
        2 => 'Fevereiro', 
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    );
    return isset($meses[$mes]) ? $meses[$mes] : '';
}

/**
 * Retorna a data por extenso
 */
function getDataPorExtenso($data) {
    $dia = date('d', strtotime($data));
    $mes = getNomeMes(date('n', strtotime($data)));
    $ano = date('Y', strtotime($data));
    return "{$dia} de {$mes} de {$ano}";
}

/**
 * Retorna o valor por extenso
 */
function getValorPorExtenso($valor) {
    $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
    $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

    $z = 0;

    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    $cont = count($inteiro);

    for($i=0;$i<$cont;$i++)
        for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
            $inteiro[$i] = "0".$inteiro[$i];

    $fim = $cont - ($inteiro[$cont-1] > 0 ? 1 : 2);
    $rt = '';

    for ($i=0;$i<$cont;$i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
        $t = $cont-1-$i;
        $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000")$z++; elseif ($z > 0) $z--;
        if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
        if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    return($rt ? $rt : "zero");
}

/**
 * Retorna o total de orçamentos
 */
function getTotalOrcamentos() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM orcamentos");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch(PDOException $e) {
        error_log("Erro ao buscar total de orçamentos: " . $e->getMessage());
        return 0;
    }
}

/**
 * Retorna o total de produtos
 */
function getTotalProdutos() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch(PDOException $e) {
        error_log("Erro ao buscar total de produtos: " . $e->getMessage());
        return 0;
    }
}

/**
 * Retorna o total de categorias
 */
function getTotalCategorias() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM categorias");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch(PDOException $e) {
        error_log("Erro ao buscar total de categorias: " . $e->getMessage());
        return 0;
    }
}

/**
 * Retorna o total de fornecedores
 */
function getTotalFornecedores() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM fornecedores");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch(PDOException $e) {
        error_log("Erro ao buscar total de fornecedores: " . $e->getMessage());
        return 0;
    }
} 