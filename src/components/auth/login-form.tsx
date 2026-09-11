"use client";

import { useActionState } from "react";
import { useFormStatus } from "react-dom";
import { LockKeyhole, Mail } from "lucide-react";
import { login, type LoginState } from "@/app/actions/auth";

const initialState: LoginState = {};

function SubmitButton() {
  const { pending } = useFormStatus();
  return (
    <button className="primary-button" disabled={pending} type="submit">
      {pending ? "Entrando..." : "Entrar no sistema"}
    </button>
  );
}

export function LoginForm() {
  const [state, action] = useActionState(login, initialState);

  return (
    <form action={action} className="login-form">
      <label>
        E-mail
        <span className="input-shell">
          <Mail aria-hidden size={18} />
          <input autoComplete="email" name="email" placeholder="voce@clinica.com.br" required type="email" />
        </span>
      </label>

      <label>
        Senha
        <span className="input-shell">
          <LockKeyhole aria-hidden size={18} />
          <input autoComplete="current-password" name="password" placeholder="Sua senha" required type="password" />
        </span>
      </label>

      {state.error ? <p className="form-error" role="alert">{state.error}</p> : null}
      <SubmitButton />
      <button className="forgot-link" disabled title="Disponivel na proxima etapa" type="button">
        Esqueci minha senha
      </button>
    </form>
  );
}
