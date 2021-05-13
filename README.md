## Tecnologias
- MySQL 8 (https://www.mysql.com/)
- PHP 7.4 (https://www.php.net/)
- Laravel 8.0 (https://lumen.laravel.com/)
- Docker (https://www.docker.com/)
- Nginx (https://www.nginx.com/)

## Instalação
Você pode rodar esse projeto usando o [Docker Compose](https://docs.docker.com/compose/install/).
```sh
$ docker-compose up  -d
```
A instalação das dependências, criação do .env, execução dos migrations e seed são feitas automaticamente. Você pode acompanhar pelo log se todos os passos foram executados corretamente digitando 

```sh
> $ docker logs desafio_laravel.php
```

Agora você deve ser capaz de visitar a página da aplicação http://localhost/ e começar a usar o sistema

## Execução dos teste

É possível executar os testes com o pphunit dentro do container, exemplo:

```sh
$ docker exec -it desafio_laravel.php vendor/bin/phpunit 
```

## Endpoints
### Users
- `GET users`, lista todos os usuários
- `GET users/{id}`, mostra detalhe de usuário específico
- `POST users`, cadastra usuário
- `PUT users/{id}`, atualiza usuário
- `DELETE users/{id}`, deleta usuário

### Accounts
- `GET users/{id}/accounts` lista contas do usuário
- `POST users/{id}/accounts` cadastra contas
- `DELETE users/{id}/accounts/{id}` exclui conta

### Transactions
- `GET transactions` lista todas as movimentações
- `GET transactions?account=id` lista as movimentações da conta
- `POST transactions` fazer uma transação (deposito ou saque)

### Para mais detalhe visite a documentação completa [API Documentation](https://github.com/cmparrela/caixa-eletronico-laravel-api/wiki/API-Documentation)