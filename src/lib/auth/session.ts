import { SignJWT, jwtVerify } from "jose";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { z } from "zod";
import { getServerEnv } from "@/lib/env";

const SESSION_COOKIE = "odonto_session";
const SESSION_DURATION_SECONDS = 60 * 60 * 8;

const sessionSchema = z.object({
  userId: z.number().int().positive(),
  name: z.string(),
  role: z.enum(["ADMIN", "DENTIST", "RECEPTIONIST", "ASSISTANT"]),
});

export type Session = z.infer<typeof sessionSchema>;

function sessionKey() {
  return new TextEncoder().encode(getServerEnv().SESSION_SECRET);
}

export async function createSession(session: Session) {
  const token = await new SignJWT(session)
    .setProtectedHeader({ alg: "HS256" })
    .setIssuedAt()
    .setExpirationTime(`${SESSION_DURATION_SECONDS}s`)
    .sign(sessionKey());

  const cookieStore = await cookies();
  cookieStore.set(SESSION_COOKIE, token, {
    httpOnly: true,
    secure: getServerEnv().NODE_ENV === "production",
    sameSite: "lax",
    path: "/",
    maxAge: SESSION_DURATION_SECONDS,
  });
}

export async function readSession(): Promise<Session | null> {
  const token = (await cookies()).get(SESSION_COOKIE)?.value;
  if (!token) return null;

  try {
    const { payload } = await jwtVerify(token, sessionKey(), { algorithms: ["HS256"] });
    return sessionSchema.parse(payload);
  } catch {
    return null;
  }
}

export async function requireSession() {
  const session = await readSession();
  if (!session) redirect("/login");
  return session;
}

export async function destroySession() {
  (await cookies()).delete(SESSION_COOKIE);
}
