import type { Metadata } from "next";
import { CalendarDays, CircleDollarSign, ClipboardList, Users } from "lucide-react";
import { logout } from "@/app/actions/auth";
import { requireSession } from "@/lib/auth/session";

export const metadata: Metadata = { title: "Dashboard" };

const cards = [
  { label: "Pacientes", value: "--", icon: Users },
  { label: "Consultas hoje", value: "--", icon: CalendarDays },
  { label: "Procedimentos", value: "--", icon: ClipboardList },
  { label: "Receita do mes", value: "--", icon: CircleDollarSign },
];

export default async function DashboardPage() {
  const session = await requireSession();

  return (
    <main className="dashboard-page">
      <header className="dashboard-header">
        <div className="brand-mark dark"><ActivityLogo /> Odonto Care</div>
        <div className="user-actions">
          <span>{session.name}</span>
          <form action={logout}><button className="secondary-button">Sair</button></form>
        </div>
      </header>
      <section className="dashboard-content">
        <p className="eyebrow">Visao geral</p>
        <h1>Ola, {session.name.split(" ")[0]}</h1>
        <p className="muted">A estrutura da nova plataforma esta pronta para receber os dados do sistema.</p>
        <div className="metric-grid">
          {cards.map(({ label, value, icon: Icon }) => (
            <article className="metric-card" key={label}>
              <span className="metric-icon"><Icon aria-hidden size={21} /></span>
              <div><p>{label}</p><strong>{value}</strong></div>
            </article>
          ))}
        </div>
        <div className="empty-state">
          <ClipboardList aria-hidden size={30} />
          <h2>Pronto para a migracao dos modulos</h2>
          <p>Os indicadores serao ativados quando o arquivo SQL original for modelado no PostgreSQL.</p>
        </div>
      </section>
    </main>
  );
}

function ActivityLogo() {
  return <span aria-hidden className="activity-logo">+</span>;
}
