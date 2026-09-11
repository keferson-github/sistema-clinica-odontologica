import { hash, verify as verifyArgon2 } from "argon2";
import { compare as verifyBcrypt } from "bcryptjs";
import { getDb } from "@/lib/db";
import { loginSchema } from "@/lib/auth/validation";

export async function authenticate(input: unknown) {
  const credentials = loginSchema.parse(input);
  const db = getDb();
  const user = await db.user.findFirst({ where: { email: credentials.email } });

  if (!user || user.active !== "Sim") return null;

  try {
    const usesBcrypt = user.passwordHash.startsWith("$2");
    const passwordMatches = usesBcrypt
      ? await verifyBcrypt(credentials.password, user.passwordHash)
      : await verifyArgon2(user.passwordHash, credentials.password);

    if (!passwordMatches) return null;

    // Atualiza hashes bcrypt importados do PHP para Argon2 após o primeiro login.
    if (usesBcrypt) {
      await db.user.update({
        where: { id: user.id },
        data: { passwordHash: await hash(credentials.password), legacyPassword: null },
      });
    }
  } catch {
    return null;
  }

  return { id: user.id, name: user.name, role: toSessionRole(user.role) };
}

function toSessionRole(role: string) {
  if (role === "Administrador") return "ADMIN" as const;
  if (role === "Dentista") return "DENTIST" as const;
  if (role === "Auxiliar Dentista") return "ASSISTANT" as const;
  return "RECEPTIONIST" as const;
}
