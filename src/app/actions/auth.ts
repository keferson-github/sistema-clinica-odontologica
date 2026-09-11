"use server";

import { redirect } from "next/navigation";
import { ZodError } from "zod";
import { authenticate } from "@/lib/auth/authenticate";
import { createSession, destroySession } from "@/lib/auth/session";

export type LoginState = { error?: string };

export async function login(_: LoginState, formData: FormData): Promise<LoginState> {
  try {
    const user = await authenticate({
      email: formData.get("email"),
      password: formData.get("password"),
    });

    if (!user) return { error: "E-mail ou senha incorretos." };

    await createSession({ userId: user.id, name: user.name, role: user.role });
  } catch (error) {
    if (error instanceof ZodError) {
      return { error: error.issues[0]?.message ?? "Dados invalidos." };
    }
    console.error("Falha ao autenticar", error);
    return { error: "Nao foi possivel entrar. Tente novamente." };
  }

  redirect("/dashboard");
}

export async function logout() {
  await destroySession();
  redirect("/login");
}
