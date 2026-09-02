# Portal do Cliente (protótipo de portfólio)

> ⚠️ **Este é um protótipo de portfólio, com dados 100% fictícios.** O projeto é
> **inspirado** em um "Portal do Cliente" que desenvolvi profissionalmente
> como Analista de Sistemas (Laravel + PHP), mas foi **reescrito do zero**
> para este repositório: nenhum código, credencial, endpoint, nome de
> empresa/cliente ou CNPJ reais aparecem em nenhum lugar. Todos os registros
> (clientes, boletos, notas fiscais, pedidos) são gerados via
> [Faker](https://fakerphp.github.io/) e a integração com o ERP externo é
> **mockada** — não há chamada de rede real nem credencial de nenhum tipo.

## O que é

Um painel administrativo onde usuários vinculados a uma empresa ("cliente")
fazem login e acompanham, para a própria empresa:

- **Dashboard** com cards de resumo (boletos em aberto, valor em aberto,
  boletos vencidos, total de pedidos) e dois gráficos (Chart.js): valor de
  boletos por mês nos últimos 6 meses, e distribuição de boletos por status.
- **Boletos**: listagem paginada com filtro por status (aberto/pago/vencido)
  e por intervalo de vencimento, com geração de PDF (fictício) por boleto.
- **Notas fiscais**: listagem com busca e filtro por data de emissão, detalhe
  e PDF (fictício) por nota.
- **Pedidos**: agrupamento de notas fiscais por pedido (quantidade de notas,
  quantidade de boletos vinculados por proximidade de data, valor total e
  data), com página de detalhe.
- **Sincronização assíncrona com um ERP externo** (mockada): Jobs de fila
  (`ShouldQueue`) que buscariam dados de um ERP via API REST, com tratamento
  de erro de comunicação, disparados por um scheduler (Laravel Task
  Scheduling) — o mesmo padrão de arquitetura usado no sistema real, só que
  aqui o "ERP" é uma classe que gera dados fictícios em memória
  (`App\Services\ErpApiMock`), sem nenhuma chamada HTTP de verdade.

## Tecnologias

- [Laravel 13](https://laravel.com/) (PHP 8.3+)
- [Laravel Breeze](https://laravel.com/docs/starter-kits) (stack Blade) para autenticação
- [Tailwind CSS](https://tailwindcss.com/) + [Vite](https://vitejs.dev/)
- [Chart.js](https://www.chartjs.org/) (via CDN) para os gráficos do dashboard
- [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) para geração de PDF
- SQLite como banco de dados (zero configuração externa para rodar o projeto)
- [Faker](https://fakerphp.github.io/) (locale `pt_BR`) para todos os dados de demonstração
- Laravel Queues + Laravel Task Scheduling para a sincronização assíncrona (mockada)

## Como rodar localmente

Pré-requisitos: PHP 8.3+, Composer, Node.js + npm.

```bash
# 1. Instalar dependências PHP
composer install

# 2. Instalar dependências JS e compilar os assets (Tailwind/Vite)
npm install
npm run build

# 3. Configurar o ambiente
cp .env.example .env
php artisan key:generate

# 4. Criar o banco SQLite (o projeto já vem configurado para usar SQLite
#    por padrão — DB_CONNECTION=sqlite — não é preciso instalar MySQL/Postgres)
touch database/database.sqlite

# 5. Rodar as migrations e popular o banco com dados fictícios (Faker)
php artisan migrate --seed

# 6. Subir o servidor de desenvolvimento
php artisan serve
```

Acesse **http://localhost:8000** e faça login com um dos usuários de teste
criados pelo seeder (todos com senha `password`):

| E-mail                | Senha      |
|------------------------|-----------|
| `cliente1@example.com` | `password` |
| `cliente2@example.com` | `password` |
| `cliente3@example.com` | `password` |

(o seeder cria de 5 a 8 clientes fictícios, cada um com seu próprio usuário —
os e-mails seguem sempre o padrão `cliente{N}@example.com`).

Cada usuário só enxerga os dados do seu próprio cliente (`cliente_id`) — é
assim que o isolamento por empresa funciona tanto no protótipo quanto no
sistema real que o inspirou.

## Sincronização com o ERP externo (mockada)

Para demonstrar o fluxo de sincronização assíncrona sem precisar configurar
um queue worker, existe um comando artisan que dispara os Jobs de forma
síncrona:

```bash
php artisan sync:demo

# Para simular uma falha de comunicação com o ERP (ex.: 30% de chance):
php artisan sync:demo --falhar=30
```

Em produção, os mesmos Jobs (`App\Jobs\SyncBoletosJob` e
`App\Jobs\SyncNotasFiscaisJob`) rodam de forma assíncrona via fila
(`ShouldQueue`), agendados a cada hora pelo scheduler em
`routes/console.php` (equivalente moderno do clássico
`$schedule->job(...)->hourly()` do `app/Console/Kernel.php`, que foi
substituído a partir do Laravel 11). Para isso funcionar de verdade seria
necessário:

```bash
# Um worker de fila rodando (ex.: supervisor, em produção)
php artisan queue:work

# E um único cron apontando para o scheduler do Laravel, a cada minuto
* * * * * php artisan schedule:run >> /dev/null 2>&1
```

Os dados retornados pelo "ERP" (`App\Services\ErpApiMock`) são inteiramente
fictícios, gerados com Faker — a classe existe apenas para simular o
formato de resposta que um client HTTP real (Guzzle, por exemplo)
receberia de um ERP de verdade, incluindo o tratamento de erro de
comunicação (timeout/endpoint indisponível), sem nenhuma chamada de rede.

## Estrutura relevante

```
app/
  Console/Commands/SyncDemoCommand.php   # php artisan sync:demo
  Http/Controllers/
    DashboardController.php
    BoletoController.php
    NotaFiscalController.php
    PedidoController.php
  Jobs/
    SyncBoletosJob.php
    SyncNotasFiscaisJob.php
  Models/
    Cliente.php
    Boleto.php          # status calculado via accessor (pago/aberto/vencido)
    NotaFiscal.php
    User.php             # belongsTo Cliente
  Services/
    ErpApiMock.php        # simula a API REST do ERP externo
database/
  migrations/             # clientes, boletos, notas_fiscais, cliente_id em users
  seeders/                # ClienteSeeder, BoletoSeeder, NotaFiscalSeeder (Faker pt_BR)
resources/views/
  layouts/                # layout com sidebar (Dashboard, Boletos, Notas Fiscais, Pedidos, Sair)
  dashboard.blade.php     # cards + Chart.js
  boletos/, notas-fiscais/, pedidos/
  pdf/                    # templates dos PDFs fictícios (dompdf)
routes/
  web.php                 # rotas protegidas por auth
  console.php             # scheduler (Schedule::job(...)->hourly())
```

## Deploy (Railway)

Já vem com `nixpacks.toml` e `railway.json` prontos para publicar uma demo
pública deste protótipo no [Railway](https://railway.app/):

1. Suba este repositório pro GitHub (`git remote add origin ...` + `git push`).
2. No Railway: **New Project → Deploy from GitHub repo** e selecione o
   repositório.
3. Em **Variables**, adicione (aba "Raw Editor" facilita colar tudo de uma vez):

   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=            # gere com `php artisan key:generate --show` localmente e cole o valor (base64:...)
   APP_URL=https://SEU-DOMINIO.up.railway.app
   DB_CONNECTION=sqlite
   ```

4. Em **Settings → Networking**, clique em **Generate Domain** pra ganhar uma
   URL pública — e cole essa mesma URL na variável `APP_URL` acima.
5. Deploy. O Railway usa o `nixpacks.toml` deste repo pra instalar as
   dependências (Composer + npm) e o `railway.json` pra definir o comando de
   start, que recria o banco SQLite e roda `migrate --seed` a cada
   deploy/restart — perfeito pra uma demo com dados sempre fictícios.

**Observações importantes:**

- Como o banco é SQLite num disco efêmero, os dados voltam ao estado inicial
  (do seeder) a cada novo deploy ou reinício do container. Para este projeto
  (portfólio, dados fake) isso é proposital e até desejável. Se um dia você
  quiser persistência de verdade, é só adicionar um serviço PostgreSQL no
  Railway e trocar `DB_CONNECTION` para `pgsql` (as migrations já são
  compatíveis, sem `raw SQL` específico do SQLite).
- O `php artisan serve` embutido no comando de start é suficiente pro volume
  de tráfego de uma demo de portfólio, mas não é o ideal para produção real
  (o certo seria PHP-FPM + Nginx/Caddy). Como o objetivo aqui é só ter um
  link pra mostrar o projeto, a simplicidade valeu a pena.
- Eu não testei este deploy num Railway real (não tenho acesso à sua conta) —
  os arquivos seguem a documentação oficial atual, mas se o build falhar, me
  manda o log de erro que eu ajusto.

## Sobre este repositório

Este código foi escrito do zero para servir de peça de portfólio, **inspirado
funcionalmente** em um sistema real que desenvolvi profissionalmente, mas sem
reaproveitar nenhuma linha do código original nem qualquer dado de cliente
verdadeiro. O objetivo é demonstrar, de forma honesta e sem exposição de
informação sensível, o tipo de solução (autenticação multi-cliente,
dashboards com gráficos, geração de PDF, sincronização assíncrona com
sistemas externos via filas e scheduler) que já implementei em produção.
