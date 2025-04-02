# Instruções para Deploy

## 1. Preparação Local
1. Remova estas pastas antes do upload:
   - `vendor/`
   - `node_modules/`
   - `storage/logs/*` (mantenha a pasta, remova só os arquivos)
   - `storage/framework/cache/*`
   - `storage/framework/sessions/*`
   - `storage/framework/views/*`

2. Renomeie `.env.production` para `.env` no servidor

## 2. Upload via FTP
Use o FileZilla com estas configurações:
- Host: ftp.thinforma.com.br
- Usuário: thinforma
- Senha: Lordac01#Lordac01#
- Porta: 21
- Diretório: /public_html/orcamentos

## 3. Configurações no Servidor
Execute estes comandos via SSH ou pelo terminal da Locaweb:

```bash
cd /home/thinforma/public_html/orcamentos

# Instalar dependências
composer install --optimize-autoloader --no-dev

# Limpar e otimizar
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Recriar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Criar link simbólico para storage
php artisan storage:link
```

## 4. Permissões
Configure as permissões:
```bash
chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 5. Banco de Dados
O banco já está configurado e em uso.

## 6. Verificação
Acesse https://thinforma.com.br/orcamentos e verifique:
1. Se a página inicial carrega
2. Se o login funciona
3. Se consegue criar/editar orçamentos
4. Se as imagens e uploads funcionam 