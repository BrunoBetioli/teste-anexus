# teste-anexus
 Processo Seletivo Anexus

Depois de baixar e instalar localmente, é preciso entrar rodar o comando "composer update" dentro da raíz do projeto. Após isso, basta criar uma base de dados no banco de dados e configurar um arquivo .env (Há um arquivo .env.example de exemplo).

Ainda na raiz do projeto, mais alguns comandos precisam ser executados.
"php artisan migrate" para criar a tabela de usuários dentro da base desejada
"php artisan db:seed" para popular a tabela de usuários com alguns usuários criados automaticamente.
"php artisan serve" para disponibilizar o projeto pela url http://localhost:8000

Lista de rotas em formato API REST:

GET /api/users - Retorna uma lista com todos os usuários
GET /api/tree - Retorna uma lista com todos os usuários em "users" e a pontuação que podem tem à direita e à esquerda em "tree_users_points"
GET /api/indications - Retorna uma lista com todos os usuários, incluindo uma contagem das indicações
GET /api/allowed_indicate - Retorna uma lista com todos os usuários que podem indicar novos usuários (todos os usuários que não tem o limite de duas indicações)
POST /api/user - Cria um usuário a partir dos dados informados. Campos necessários: name, email, user_id e points
GET /api/user/{id} - Retorna o usuário cujo ID foi informado, bem como quem o indicou (indicated_by) e quem ele indicou (indicated)
PUT /api/user/{id} - Atualiza o usuário cujo ID foi informado. Campos necessários: name, email, user_id e points
