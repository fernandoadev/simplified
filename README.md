# Simplified

### Passo a passo

Suba os containers do projeto

```sh
docker-compose up -d
```

Crie o Arquivo .env

```sh
cp .env.example .env
```

Acesse o container app

```sh
docker-compose exec app bash
```

Instale as dependências do projeto

```sh
composer install
```

Gere a key do projeto Laravel

```sh
php artisan key:generate
```

Rodar as migrations

```sh
php artisan migrate
```

Rodar as seeders

```sh
php artisan db:seed
```

Para rodar o PHPCS, acesse o container do app (1) em seguida rode o comando phpcs (2), caso exista algo que possa ser fixado automaticamente rode o comando phpcbf (3)

```sh
1 - docker-compose exec app bash
2 - vendor/bin/phpcs -s --standard=PSR12 app
3 - vendor/bin/phpcbf --standard=PSR12 app
```

Para rodar os testes, acesse o container do app (1) em seguida rode o comando do pest (2)

```sh
1 - docker-compose exec app bash
2 - ./vendor/bin/pest
```

Para gerar ou atualizar a documentação da API, acesse o container (1) em seguida rode o comando (2)

```sh
1 - docker-compose exec app bash
2 - php artisan l5-swagger:generate
```

Acesse o projeto
[http://localhost:8000](http://localhost:8000)

Documentação da api
[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

OBS: Para testar o endpoint de transfer usar:

```sh
POST /transfer
Content-Type: application/json

{
  "value": 100.0,
  "payer": "dd22e8cd-8b15-495b-a3dc-9ebdeadebf59", //id do usuário comum criado na seeder
  "payee": "c3726ccb-7de7-4424-ac86-d9498dd8163c" //id do usuário lojista criado na seeder
}
```
