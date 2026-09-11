import { config } from "dotenv";
import { hash } from "argon2";
import { PrismaPg } from "@prisma/adapter-pg";
import { PrismaClient } from "../src/generated/prisma/client";
import { z } from "zod";

config({ path: process.env.DOTENV_CONFIG_PATH ?? ".env.local", quiet: true });
config({ quiet: true });

const seedEnv = z.object({
  DATABASE_URL: z.string().url(),
  SEED_ADMIN_EMAIL: z.string().email(),
  SEED_ADMIN_PASSWORD: z.string().min(12),
  SEED_ADMIN_NAME: z.string().min(1).optional(),
}).parse(process.env);

if (seedEnv.SEED_ADMIN_PASSWORD === "troque-esta-senha") {
  throw new Error("Defina uma SEED_ADMIN_PASSWORD unica com pelo menos 12 caracteres.");
}

const prisma = new PrismaClient({ adapter: new PrismaPg({ connectionString: seedEnv.DATABASE_URL }) });

async function main() {
  await prisma.appConfig.upsert({
    where: { id: 1 },
    update: {},
    create: {
      id: 1,
      clinicName: "Sistema Clinica Odontologica",
      email: seedEnv.SEED_ADMIN_EMAIL,
    },
  });

  const email = seedEnv.SEED_ADMIN_EMAIL.toLowerCase();
  const passwordHash = await hash(seedEnv.SEED_ADMIN_PASSWORD);
  const existingAdmin = await prisma.user.findFirst({ where: { email } });

  if (existingAdmin) {
    await prisma.user.update({
      where: { id: existingAdmin.id },
      data: {
        name: seedEnv.SEED_ADMIN_NAME ?? existingAdmin.name,
        passwordHash,
        legacyPassword: null,
        role: "Administrador",
        active: "Sim",
      },
    });
  } else {
    await prisma.user.create({
      data: {
        name: seedEnv.SEED_ADMIN_NAME ?? "Administrador",
        email,
        passwordHash,
        role: "Administrador",
        active: "Sim",
        data: new Date(),
        id_ref: 0,
      },
    });
  }
}

main()
  .finally(() => prisma.$disconnect());
