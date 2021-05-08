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