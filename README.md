## Introdução

API de produto criada em Laravel 8, com base no Curso Laravel Web Services Restful especializaTi

### Criação banco de dados
`web_service` 

### Instação de dependencias
```sh
composer install
```

### Arquivo de configuração
Crie o arquivo `.env` na pasta do framework, e configure a conexão com o banco

### Crie o link simbólico para o disco público
```sh
php artisan storage:link
```

### Crie tabelas e dados fake 

```sh
php artisan migrate --seed
```