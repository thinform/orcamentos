# Sistema de Orçamentos

Sistema web para gerenciamento de orçamentos, produtos, categorias e fornecedores.

## Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache 2.4 ou superior
- Extensões PHP:
  - PDO
  - PDO_MySQL
  - GD
  - cURL
  - mbstring
  - json
  - xml

## Instalação

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/orcamentos.git
cd orcamentos
```

2. Crie o banco de dados e as tabelas:
```bash
mysql -u seu_usuario -p < database.sql
```

3. Configure as credenciais do banco de dados em `constants.php`:
```php
define('DB_HOST', 'seu_host');
define('DB_NAME', 'seu_banco');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```

4. Execute o script de inicialização:
```bash
php init.php
```

5. Configure o Apache para apontar para o diretório do projeto.

6. Acesse o sistema pelo navegador:
```
http://localhost/orcamentos
```

## Estrutura de Diretórios

```
orcamentos/
├── config.php          # Configurações gerais
├── constants.php       # Constantes do sistema
├── functions.php       # Funções utilitárias
├── init.php           # Script de inicialização
├── database.sql       # Estrutura do banco de dados
├── uploads/           # Diretório para uploads
│   ├── produtos/      # Imagens de produtos
│   └── orcamentos/    # Arquivos de orçamentos
├── cache/             # Cache do sistema
├── logs/              # Logs do sistema
└── temp/             # Arquivos temporários
```

## Funcionalidades

### Orçamentos
- Cadastro de orçamentos
- Listagem e busca
- Edição e exclusão
- Impressão em PDF
- Envio por e-mail
- Status (Pendente, Aprovado, Rejeitado, Cancelado)
- Cálculo automático de totais
- Desconto e frete
- Validade
- Condições de pagamento

### Produtos
- Cadastro de produtos
- Upload de imagens
- Categorias
- Fornecedores
- Preço de custo e venda
- Margem de lucro
- Controle de estoque
- Histórico de preços

### Categorias
- Cadastro de categorias
- Listagem e busca
- Edição e exclusão

### Fornecedores
- Cadastro de fornecedores
- Listagem e busca
- Edição e exclusão
- Dados completos (endereço, contatos, etc.)

### Relatórios
- Vendas
- Produtos
- Estoque

## Segurança

- Proteção contra CSRF
- Validação de dados
- Sanitização de inputs
- Logs de atividades
- Backup automático
- Controle de sessão

## Configuração

### Configurações Gerais
Edite o arquivo `constants.php` para ajustar:
- Conexão com banco de dados
- E-mail
- Upload de arquivos
- Cache
- Logs
- Debug

### Configurações do Apache
O arquivo `.htaccess` inclui:
- Forçar HTTPS
- Remover www
- Proteção de arquivos
- Compressão GZIP
- Cache de arquivos estáticos
- Headers de segurança

## Desenvolvimento

### Padrões
- PSR-1: Basic Coding Standard
- PSR-2: Coding Style Guide
- PSR-4: Autoloader

### Bibliotecas
- Bootstrap 5.3.2
- jQuery 3.7.1
- DataTables 1.13.7
- Select2 4.1.0
- Bootstrap Icons 1.11.3
- PHPMailer 6.8.0

## Suporte

Para suporte, entre em contato através do e-mail:
suporte@thinforma.com.br

## Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo [LICENSE.md](LICENSE.md) para detalhes. 