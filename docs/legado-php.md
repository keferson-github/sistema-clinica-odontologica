# Referência do legado PHP (removido do repositório)

Os arquivos PHP da raiz foram removidos em 11/09/2026. O projeto passou a ser 100% Next.js.
Este documento preserva as regras de negócio que ainda **não** foram portadas, para servir de
especificação na implementação em Server Actions.

O código original continua recuperável pelo histórico do Git:

```bash
git show 22b6a5a:autenticar.php
git show 22b6a5a:recuperar-senha.php
git show 22b6a5a:resetar-senha.php
git show 22b6a5a:alterar-senha.php
git show 22b6a5a:conexao.php
git show 22b6a5a:index.php
```

## Arquivos removidos e status da migração

| Arquivo | Papel no legado | Status |
| --- | --- | --- |
| `conexao.php` | PDO MySQL + carga da tabela `config` em variáveis globais | Substituído por `src/lib/db.ts` e pelo modelo `AppConfig` |
| `autenticar.php` | Login por e-mail/senha e sessão PHP | Substituído por `src/app/actions/auth.ts` e `src/lib/auth/` |
| `index.php` | Tela de login e modal de recuperação | Substituído por `src/app/login/page.tsx` |
| `recuperar-senha.php` | Geração de token e envio de e-mail | **Não portado** |
| `resetar-senha.php` | Validação do token e formulário de nova senha | **Não portado** |
| `alterar-senha.php` | Gravação da nova senha | **Não portado** |

## Fluxo de recuperação de senha (a portar)

Comportamento do legado, em três etapas:

1. **Solicitação** (`recuperar-senha.php`): recebe `email`, busca em `usuarios`. Se existir,
   gera `token = hash('sha256', time())`, grava em `usuarios.token` e envia e-mail com o link
   `resetar-senha.php?email=<email>&token=<token>`. Responde texto puro
   `Recuperado com Sucesso` ou `Esse email não está Cadastrado!`.
2. **Validação** (`resetar-senha.php`): valida o par `email` + `token` contra `usuarios`.
   Sem correspondência, redireciona para a raiz. Com correspondência, renderiza o formulário
   de nova senha (`senha` + `re_senha`).
3. **Gravação** (`alterar-senha.php`): compara `senha` e `re_senha`; se diferentes, retorna
   `As senhas são diferentes!!`. Caso contrário grava `password_hash(PASSWORD_DEFAULT)` (bcrypt)
   em `usuarios.senha_crip` e regrava o mesmo `token`.

A coluna `usuarios.token` existe no schema Prisma e é o ponto de ancoragem desse fluxo.

### Falhas do legado que não devem ser reproduzidas

Ao implementar a versão Next.js, corrija os seguintes pontos:

- **Token previsível**: `hash('sha256', time())` deriva do relógio em segundos e é adivinhável.
  Usar `crypto.randomBytes(32)`, armazenando apenas o hash do token.
- **Token sem expiração**: nunca era invalidado. Definir validade curta (ex.: 30 min) e uso único,
  limpando o campo após a troca de senha — o legado regravava o mesmo token.
- **Enumeração de contas**: a resposta distingue e-mail cadastrado de não cadastrado.
  Retornar sempre a mesma mensagem.
- **Token na query string**: fica em logs e no histórico do navegador. Preferir consumo imediato
  com troca por cookie de uso único.
- **Sem rate limiting**: nenhuma proteção contra disparo em massa de e-mails.
- **Confirmação de senha no servidor**: manter, mas via schema Zod junto com a política de senha.

## Login: diferenças já resolvidas

O legado tinha comportamentos que **não** devem voltar, já corrigidos na stack atual:

- "Lembrar-me" gravava `email_usu` e **a senha em texto puro** em `localStorage`
  (`autenticar.php`), e um formulário oculto em `index.php` reautenticava só pelo `id` do usuário
  — login sem senha para quem tivesse acesso ao navegador. A versão atual usa cookie
  HTTP-only assinado (`src/lib/auth/session.ts`).
- A checagem de `ativo != 'Sim'` definia a mensagem mas **não interrompia o fluxo**: a sessão era
  criada mesmo assim. Garantir que usuário inativo seja bloqueado.
- Credenciais do banco ficavam hardcoded em `conexao.php` (`root` sem senha). Agora vêm de
  `DATABASE_URL` validada em `src/lib/env.ts`.

## Configuração global (`config`)

`conexao.php` lia a tabela `config` a cada request e, quando vazia, inseria uma linha padrão.
Quando `config.ativo` não era `'Sim'`, o sistema inteiro era bloqueado com uma imagem.

Campos usados: `nome`, `email`, `telefone`, `endereco`, `instagram`, `cnpj`, `logo`, `logo_rel`,
`icone`, `ativo`, `multa_atraso`, `juros_atraso`, `marca_dagua`, `assinatura_recibo`,
`impressao_automatica`, `entrar_automatico`, `mostrar_preloader`, `ocultar_mobile`,
`api_whatsapp`, `token_whatsapp`, `instancia_whatsapp`, `alterar_acessos`, `dados_pagamento`,
`abertura_caixa`, `horas_confirmacao`.

Todos estão mapeados no modelo Prisma `AppConfig`. O seed já cria a linha inicial, então a
inserção implícita a cada request não precisa ser reproduzida.
