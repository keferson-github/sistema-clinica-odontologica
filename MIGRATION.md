# Plano de migração do legado

## Entregue

- Aplicação Next.js full-stack organizada em `src/app`, `src/components` e `src/lib`.
- Login baseado em usuário PostgreSQL, senha Argon2 e sessão assinada de oito horas.
- Dashboard protegido e endpoint de saúde do banco.
- Prisma configurado para PostgreSQL, migration completa do dump legado e seed seguro/idempotente.
- As 33 tabelas, os 23 blocos de dados e todas as sequências do dump `u472864586_odonto.sql` foram convertidos para PostgreSQL.
- Schema Prisma completo para todas as tabelas importadas.
- Compatibilidade com senhas bcrypt do PHP, com atualização automática para Argon2 após login válido.
- Ambiente local via Docker Compose e imagem de produção standalone.
- ESLint, TypeScript estrito e teste unitário inicial com o runner nativo do Node.js.
- Pipeline de CI para geração do cliente, lint, tipos, testes e build.
- Interface responsiva sem dependência dos assets ausentes do legado.

## Correspondência da autenticação

| Legado PHP/MySQL | Nova plataforma |
| --- | --- |
| `usuarios` | modelo Prisma `User`, mantendo a tabela `usuarios` |
| `config` | modelo Prisma `AppConfig`, mantendo a tabela `config` |
| `senha_crip` | bcrypt legado, atualizado para Argon2 após login |
| `nivel` textual | papel normalizado na sessão da aplicação |
| sessão PHP/localStorage | cookie HTTP-only assinado |
| `conexao.php` | cliente Prisma centralizado |
| inserts executados em requests | migration e seed explícitos |

## Dados ainda necessários

1. Código e assets do painel que não estão neste repositório, se estiverem disponíveis.
2. Regras operacionais: perfis e permissões, estados de consulta, caixa, comissões, convênios e odontograma.
3. Identidade visual definitiva: logotipo, cores, tipografia e nome comercial.
4. Escolha da infraestrutura de produção: hospedagem da aplicação, PostgreSQL, armazenamento de anexos, e-mail e WhatsApp.
5. Requisitos de LGPD: retenção, consentimento, auditoria, backups e política de acesso aos prontuários.

## Conversão do SQL

O dump foi tratado como fonte de verdade estrutural e de dados. Tipos inteiros, decimais, datas, horários, textos e `AUTO_INCREMENT` foram reescritos para equivalentes PostgreSQL. Como o dump só declara chaves primárias e não possui chaves estrangeiras, nenhuma relação foi adicionada por inferência.

Não aplique a migration inicial sobre um banco de produção existente. Ela cria todas as tabelas e inclui os registros presentes no dump de origem.

O dump contém dados pessoais, hashes de senha e configuração operacional. Mantenha este repositório privado, restrinja o acesso e substitua credenciais/tokens antes de qualquer publicação ou uso em produção.

## Segurança de dependências

Em 10/09/2026, `npm audit --omit=dev` reporta quatro alertas altos transitivos no CLI/configuração do Prisma (`deepmerge-ts` e `mysql2`). O aplicativo usa PostgreSQL e esses pacotes não participam do caminho de autenticação em runtime da imagem standalone. A correção automática disponível exige downgrade incompatível do Prisma e não foi aplicada; deve-se atualizar assim que uma versão corrigida e compatível estiver disponível.
