# Sistema Clínica Odontológica

Nova fundação full-stack em Next.js e TypeScript para substituir gradualmente a aplicação PHP legada.

## Stack

- Next.js (App Router e Server Actions) + React + TypeScript estrito
- Tailwind CSS 4 com design responsivo próprio
- PostgreSQL 17 ou superior
- Prisma ORM com migrations versionadas
- Zod para validação de entradas e ambiente
- Argon2 para hash de senhas e `jose` para sessões assinadas em cookie HTTP-only
- Node Test Runner, ESLint e verificação estática do TypeScript
- Docker Compose para o banco local e Dockerfile multi-stage para a aplicação

Os arquivos PHP na raiz são mantidos temporariamente como referência da migração. Eles não são executados pelo Next.js.

## Pré-requisitos

- Node.js 24
- npm 11 ou compatível
- Docker com Docker Compose, ou uma instância PostgreSQL acessível

## Desenvolvimento local com pgAdmin

O arquivo `.env.local` já contém credenciais locais exclusivas e é ignorado pelo Git. Para criar o banco no PostgreSQL instalado na máquina:

1. No pgAdmin, conecte-se ao servidor local como administrador e abra o Query Tool no banco `postgres`.
2. Abra `.local/bootstrap-development.sql`. Execute primeiro o bloco `DO` e depois, separadamente, a instrução `CREATE DATABASE`.
3. Prepare todas as tabelas, dados migrados e o administrador da aplicação:

   ```bash
   npm install
   npm run db:setup
   ```

4. Inicie a aplicação:

   ```bash
   npm run dev
   ```

A aplicação estará em `http://localhost:3000`, e o health check em `http://localhost:3000/api/health`.

Como alternativa ao PostgreSQL instalado, `docker compose up -d postgres` usa as mesmas credenciais do `.env.local`.

## Produção no Easypanel

O arquivo `.env.prod`, também ignorado pelo Git, contém segredos de produção diferentes dos locais. Antes do deploy:

1. Crie um serviço PostgreSQL chamado `postgres` e configure nele `POSTGRES_DB`, `POSTGRES_USER` e `POSTGRES_PASSWORD` do `.env.prod`.
2. Importe no serviço da aplicação `NODE_ENV`, `NEXT_TELEMETRY_DISABLED`, `DATABASE_URL`, `SESSION_SECRET` e as variáveis `SEED_ADMIN_*`.
3. Troque `SEED_ADMIN_EMAIL` pelo e-mail administrativo definitivo.
4. Aplique `npm run db:deploy` e, apenas na primeira implantação, `npm run db:seed`.

Se o serviço PostgreSQL receber outro nome, substitua o host `postgres` dentro de `DATABASE_URL`. Não copie `.env.local` para produção e não versione nenhum dos arquivos com segredos.

## Comandos

| Comando | Finalidade |
| --- | --- |
| `npm run dev` | Servidor local com hot reload |
| `npm run build` | Build otimizado de produção |
| `npm run check` | ESLint, TypeScript e testes |
| `npm run db:migrate` | Cria/aplica migration em desenvolvimento |
| `npm run db:deploy` | Aplica migrations existentes em produção |
| `npm run db:seed` | Cria configuração e administrador iniciais |
| `npm run db:setup` | Gera o cliente, aplica migrations e executa o seed local |
| `npm run db:status` | Mostra o estado das migrations |
| `npm run db:deploy:prod` | Aplica `.env.prod` manualmente fora do Easypanel |
| `npm run db:studio` | Interface de inspeção do banco |

## Estado da migração

O dump MariaDB `u472864586_odonto.sql`, gerado em 10/09/2026, foi convertido integralmente para a migration inicial PostgreSQL. A migration contém as 33 tabelas, os 23 blocos de dados e os valores seguintes de `AUTO_INCREMENT` presentes na origem. O schema Prisma representa todas as tabelas do legado.

O arquivo de origem não declara chaves estrangeiras; por fidelidade, a migration também não inventa restrições relacionais. Os módulos de pacientes, agenda, prontuário, odontograma, financeiro e relatórios ainda precisam ser implementados na aplicação sobre o modelo importado.

Consulte [MIGRATION.md](MIGRATION.md) para decisões, lacunas e próximas etapas.
