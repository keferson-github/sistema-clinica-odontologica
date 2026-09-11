import { config } from "dotenv";
import { defineConfig, env } from "prisma/config";

// Next.js lê .env.local automaticamente; o Prisma CLI precisa fazê-lo explicitamente.
// Em produção, as variáveis injetadas pelo Easypanel têm precedência.
config({ path: process.env.DOTENV_CONFIG_PATH ?? ".env.local", quiet: true });
config({ quiet: true });

export default defineConfig({
  schema: "prisma/schema.prisma",
  migrations: {
    path: "prisma/migrations",
    seed: "tsx prisma/seed.ts",
  },
  datasource: {
    url: env("DATABASE_URL"),
  },
});
