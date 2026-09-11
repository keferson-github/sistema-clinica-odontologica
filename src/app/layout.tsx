import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: { default: "Odonto Care", template: "%s | Odonto Care" },
  description: "Gestao clinica odontologica segura e integrada.",
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="pt-BR">
      <body>{children}</body>
    </html>
  );
}
