
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

Para rodar os testes, acesse o container do app (1) em seguida rode o comando do pest (2)
```sh
1 - docker-compose exec app bash
2 - ./vendor/bin/pest
```

Acesse o projeto
[http://localhost:8000](http://localhost:8000)
