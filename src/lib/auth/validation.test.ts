import assert from "node:assert/strict";
import { describe, it } from "node:test";
import { loginSchema } from "./validation.ts";

describe("loginSchema", () => {
  it("normaliza o e-mail", () => {
    assert.equal(
      loginSchema.parse({ email: " ADMIN@CLINICA.COM ", password: "senha" }).email,
      "admin@clinica.com",
    );
  });

  it("rejeita credenciais incompletas", () => {
    assert.equal(loginSchema.safeParse({ email: "invalido", password: "" }).success, false);
  });
});
