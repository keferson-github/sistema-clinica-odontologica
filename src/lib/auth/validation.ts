import { z } from "zod";

export const loginSchema = z.object({
  email: z.string().trim().toLowerCase().email("Informe um e-mail valido."),
  password: z.string().min(1, "Informe sua senha.").max(200),
});

export type LoginInput = z.infer<typeof loginSchema>;
