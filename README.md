
# Backend Laravel - Gerenciamento de Pedidos

Este projeto é um backend desenvolvido em Laravel para gerenciamento de pedidos, incluindo cadastro de clientes, endereços, produtos, pagamentos e pedidos.

## Requisitos

- PHP (>= 8.0)
- Composer
- PostgreSQL
- Redis
- Docker

## Configuração do Projeto

#### 1. Clonar o repositório

```bash
git clone https://gitlab.com/appetite2/api-laravel
cd api-laravel
```

#### 2. copiar .env
```bash
cp .env.example .env


DB_CONNECTION=pgsql
DB_HOST=db 
DB_PORT=5432
DB_DATABASE=appetite  
DB_USERNAME=admin     
DB_PASSWORD=admin123 

PGADMIN_DEFAULT_EMAIL=admin@admin.com
PGADMIN_DEFAULT_PASSWORD=admin123

REDIS_HOST=redis-appetite
REDIS_PORT=6379
REDIS_PASSWORD=null
REDIS_CLIENT=phpredis

QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=01dda1bef1287e
MAIL_PASSWORD=63c66ab263226e
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME="${APP_NAME}"

```

#### 3. Subir os containers do projeto
```bash
docker-compose up -d
```
#### 4. Acessar o container
```bash
docker-compose exec app bash
```
#### 5. Instalar as dependências do projeto
```bash
composer install
```
#### 6. Gerar a key do projeto Laravel
```bash
php artisan key:generate
```
#### 7. Executar as migrations

```bash
php artisan migrate:fresh
```
#### 8. Executar os seeders

```bash
php artisan db:seed --class=CustomerSeeder
php artisan db:seed --class=AddressSeeder
php artisan db:seed --class=ProductSeeder
php artisan db:seed --class=PaymentSeeder
php artisan db:seed --class=OrderSeeder
```

#### 9. Executar os testes

```bash
php artisan test

```

### 10. Api appetite

```bash
http://localhost:8989/api

```

