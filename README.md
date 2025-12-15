1. Pré-requisitos

- Docker Desktop for Windows: Para gerenciar os contêineres. Importante: Durante a instalação, mantenha a opção "Use WSL 2" marcada. Link: https://docs.docker.com/desktop/setup/install/windows-install/

2. Passo a Passo da Instalação

- Clone o repositório: git clone https://github.com/ANDGG-ProjetoExtensao/javierce-laravel.git
- Entre na pasta do projeto no terminal(do vscode msm)
- Suba os contêineres Docker: docker compose up -d --build
- Instale as dependências do Laravel (Composer): docker compose exec app composer install
- Crie e configure o arquivo .env do Laravel: docker compose exec app cp .env.example .env (coloca o msm que ta no .env da raiz e mantem o resto)
- Gere a chave da aplicação: docker compose exec app php artisan key:generate
- Rode as "Migrations": docker compose exec app php artisan migrate

PRONTO! 🎉

Se tudo deu certo, o ambiente está no ar.

    Acesse a aplicação em: http://localhost:8001

    Acesse o banco de dados via phpMyAdmin em: http://localhost:8002

        Servidor: db

        Usuário: JavierCabeloEstetica

        Senha: senha

*Obs: eu deixei o .env da raiz mas dps tenho q por no gitignore*
