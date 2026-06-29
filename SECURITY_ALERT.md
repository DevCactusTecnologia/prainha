# 🚨 ALERTA DE SEGURANÇA — Malware detectado no repositório `prainha`

**Data da detecção:** 29 de junho de 2026
**Detectado por:** auditoria realizada durante migração de design system
**Severidade:** 🔴 **CRÍTICA** — comprometimento de credenciais de usuários finais

---

## 📋 Sumário executivo

Durante a auditoria do repositório `DevCactusTecnologia/prainha` (commit `1146fc5` — único commit), foi identificado **código JavaScript malicioso ofuscado** injetado em dois arquivos do projeto. O payload é compatível com a categoria **cookie stealer / browser hijacker** e, se compilado e servido em produção, exfiltra cookies de sessão e referrer de **todos os usuários** que acessarem a aplicação.

## 🎯 Arquivos comprometidos

| Caminho | Byte de início do payload | Tamanho do payload |
|---|---|---|
| `webpack.mix.js` | 478 | ~8.640 bytes |
| `resources/js/bootstrap.js` | 897 | ~5.900 bytes |

## 🔬 Indicadores de comprometimento (IoC)

- **Assinatura única no código:** `if (typeof ndsj === "undefined")`
- **Padrão de obfuscação:** dupla camada (obfuscator.io-like + array shuffling com aritmética em base hex)
- **Construtor injetado:** `HttpClient` próprio (não relacionado ao axios)
- **Gerador:** função `rand()` produzindo string base-36 usada como `token`
- **APIs do browser consumidas:** `document.cookie`, `document.referrer`, `window.location.hostname`, `XMLHttpRequest`
- **Whitelist de hostnames:** o malware NÃO ativa em `localhost`, em domínios `.local`, ou em hostnames que combinem com determinadas strings ofuscadas — esse comportamento é típico de evasão de scanners e ambientes de desenvolvimento
- **Pista forense:** `public/js/app.js` contém o path `C:\Users\help\Downloads\Sislac\...` — sugere que o repositório foi inicializado a partir de um download (template, tema ou projeto pirateado) em uma máquina Windows com nome de usuário `help`. O malware muito provavelmente já estava no template original

## ⚙️ Comportamento esperado do payload (sem decifrar)

1. Lê `document.cookie`, `document.referrer` e `window.location.hostname`
2. Verifica se o hostname é "interessante" (não é dev/local)
3. Gera um token aleatório
4. Faz request GET para um endpoint C2 (Command & Control) embarcado de forma ofuscada nos arrays de strings
5. Se a resposta contiver um marcador específico, executa `eval`/`location` na resposta (auto-update do malware ou redirecionamento)

## 🛡️ Plano de remediação imediato

### Bloco 1 — Conter (HOJE)
- [ ] **Substituir** `webpack.mix.js` e `resources/js/bootstrap.js` pelas versões limpas fornecidas
- [ ] **NÃO executar** `npm run dev` nem `npm run prod` antes da substituição — qualquer build atual recompila o malware para `public/js/app.js`
- [ ] **Verificar produção**: inspecionar o `public/js/app.js` que está servido no ambiente produtivo. Procurar pela string `typeof ndsj`. Se aparecer, o ambiente está comprometido
- [ ] **Bloquear deploy** automático até a limpeza ser validada

### Bloco 2 — Investigar (24h)
- [ ] **Auditar histórico** de deploys: quando o `public/js/app.js` em produção foi gerado pela última vez? Se foi após o commit inicial deste repo, está infectado
- [ ] **Coletar logs de saída** do servidor de aplicação para identificar tráfego para o domínio C2
- [ ] **Identificar usuários afetados** — todos que acessaram a aplicação enquanto o JS infectado estava servido
- [ ] **Verificar outros repositórios** da organização que possam ter origem no mesmo template/download

### Bloco 3 — Remediar credenciais (24h)
- [ ] **Rotacionar `APP_KEY`** no `.env` de produção
- [ ] **Invalidar todas as sessões ativas** (`php artisan session:flush` ou equivalente)
- [ ] **Forçar reset de senhas** para usuários administrativos por precaução
- [ ] **Rotacionar tokens API** de integrações (Razorpay, etc.)
- [ ] **Notificar usuários** sobre necessidade de relogin

### Bloco 4 — Prevenir (72h)
- [ ] **Adicionar scanner** no CI/CD: `git grep -E "typeof (ndsj|ndhj)" || exit 0` no pre-commit
- [ ] **Configurar SRI** (Subresource Integrity) nos assets JS servidos
- [ ] **Implementar CSP** (Content Security Policy) restritiva no header HTTP — bloqueia exfiltração via XHR para domínios não autorizados
- [ ] **Revisar todos os assets de terceiros** em `public/assets/libs/` para padrões similares
- [ ] **Adotar gerenciamento de dependências** via npm/composer com lockfiles auditados

## 📝 Como verificar se sua produção está infectada

Acesse o servidor de produção e execute:

```bash
# Verificar o app.js compilado
grep -c "typeof ndsj" /var/www/prainha/public/js/app.js

# Se retornar > 0, está infectado.
# Se retornar 0, a produção está atualmente limpa — mas o próximo deploy reinfecta se os fontes não forem corrigidos.

# Verificar arquivos-fonte:
grep -c "typeof ndsj" /var/www/prainha/webpack.mix.js
grep -c "typeof ndsj" /var/www/prainha/resources/js/bootstrap.js
```

## 📞 Contatos para escalonamento

Se confirmado que produção foi servida com o malware:
- Notificar DPO/encarregado de dados (LGPD — incidente reportável)
- Notificar autoridade nacional (ANPD) dentro do prazo legal se houver vazamento confirmado
- Documentar timeline do incidente para o registro de violações da empresa

---

## 📎 Anexos (arquivos limpos entregues)

- `webpack.mix.js` — versão limpa (apenas mix.js + mix.sass legítimos + nova compilação SISLAC)
- `resources/js/bootstrap.js` — versão limpa (apenas axios + lodash + Echo comentado)

> Estes arquivos limpos foram produzidos preservando apenas os bytes legítimos identificados na auditoria. **Nenhuma parte do payload foi executada, decifrada ou propagada** durante a análise.
