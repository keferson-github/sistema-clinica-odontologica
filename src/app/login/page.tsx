import type { Metadata } from "next";
import { redirect } from "next/navigation";
import { Activity, CalendarCheck, ShieldCheck } from "lucide-react";
import { LoginForm } from "@/components/auth/login-form";
import { readSession } from "@/lib/auth/session";

export const metadata: Metadata = { title: "Entrar" };

export default async function LoginPage() {
  if (await readSession()) redirect("/dashboard");

  return (
    <main className="auth-page">
      <section className="brand-panel">
        <div className="brand-mark"><Activity aria-hidden size={24} /> Odonto Care</div>
        <div className="brand-copy">
          <p className="eyebrow">Gestao inteligente para sua clinica</p>
          <h1>Mais tempo para cuidar de cada sorriso.</h1>
          <p>Agenda, pacientes, prontuarios e financeiro em uma experiencia clara e segura.</p>
        </div>
        <div className="trust-row">
          <span><ShieldCheck aria-hidden size={18} /> Dados protegidos</span>
          <span><CalendarCheck aria-hidden size={18} /> Rotina organizada</span>
        </div>
      </section>

      <section className="form-panel">
        <div className="login-card">
          <div className="mobile-brand"><Activity aria-hidden size={22} /> Odonto Care</div>
          <p className="eyebrow">Bem-vindo de volta</p>
          <h2>Acesse sua conta</h2>
          <p className="muted">Use as credenciais cadastradas pela sua clinica.</p>
          <LoginForm />
        </div>
      </section>
    </main>
  );
}
