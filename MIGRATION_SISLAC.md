# 🎨 SISLAC Design System — Guia de Migração + Deploy

> Aplicação do design system do `appsislac` (React + Tailwind) sobre o `prainha` (Laravel + Blade) — Fases 1, 2 e 3.

---

## 📦 Arquivos entregues (14 no total)

| # | Caminho | O que faz |
|---|---|---|
| 1 | `resources/sass/sislac.scss` | Design system completo (tokens, componentes, animações) |
| 2 | `webpack.mix.js` | **🛡️ LIMPO** + compilação do sislac.scss |
| 3 | `resources/js/bootstrap.js` | **🛡️ LIMPO** (malware removido) |
| 4 | `SECURITY_ALERT.md` | Relatório técnico do incidente de malware |
| 5 | `resources/views/layouts/sislac/head.blade.php` | `<head>` enxuto (Inter + BoxIcons + sislac.css) |
| 6 | `resources/views/layouts/sislac/sidebar.blade.php` | Sidebar vertical, branca, ícones por role |
| 7 | `resources/views/layouts/sislac/footer.blade.php` | Footer minimal (suporte + privacidade) |
| 8 | `resources/views/layouts/sislac/master.blade.php` | Master layout (entry point para páginas migradas) |
| 9 | `resources/views/components/sislac/hero-card.blade.php` | Card destaque com gradiente roxo |
| 10 | `resources/views/components/sislac/kpi-card.blade.php` | Card de indicador (4 variantes de visual) |
| 11 | `resources/views/components/sislac/panel.blade.php` | Painel branco com header lavanda |
| 12 | `resources/views/dashboards/admin-sislac.blade.php` | Dashboard completo (matching pixel-a-pixel ao print) |
| 13 | `app/Http/Controllers/HomeController.php` | Patch que ativa o novo dashboard (1 linha alterada) |
| 14 | `MIGRATION_SISLAC.md` | Este guia |

---

## ⚙️ Comandos pra rodar (na ordem)

```bash
# ─── 1. Backup (importante!) ─────────────────────────────────────
git checkout -b feature/sislac-design-system

# ─── 2. Copiar arquivos novos sobre o repo ───────────────────────
# Extraia o ZIP/pasta `prainha/` sobre a raiz do projeto.
# Os 3 arquivos infectados/legados são SUBSTITUÍDOS:
#   - webpack.mix.js          (substituído por versão limpa)
#   - resources/js/bootstrap.js (substituído por versão limpa)
#   - resources/views/dashboards/admin-sislac.blade.php (overwrite OK)

# ─── 3. Verificar que o malware foi removido ─────────────────────
grep -l "typeof ndsj" webpack.mix.js resources/js/bootstrap.js
# Deve retornar VAZIO. Se aparecer algo, malware ainda está lá.

# ─── 4. Habilitar o novo dashboard ───────────────────────────────
# A ativação acontece via patch no HomeController (já incluído no pacote).
# O arquivo entregue: app/Http/Controllers/HomeController.php
#
# Mudança única (linha 31): para o role admin/doctor, o controller agora
# retorna a view 'dashboards.admin-sislac' em vez de 'index'.
# Recepcionista/biomedical continuam usando 'index' (dashboard antigo).
#
# IMPORTANTE: NÃO altere resources/views/index.blade.php — fazer @include
# da nova view dentro dela QUEBRA o Blade (conflito de @extends).

# ─── 5. Arquivar tentativa anterior (opcional) ───────────────────
mv resources/views/layouts/admin-dashboard-modern.blade.php \
   resources/views/layouts/admin-dashboard-modern.blade.php.archived 2>/dev/null || true
rm -f MODERN_DESIGN_GUIDE.md MODERN_THEME_SETUP.md IMPLEMENTATION_SUMMARY.txt \
      RESUMO_FINAL.txt SETUP_CHECKLIST.txt 00_LEIA_PRIMEIRO.txt QUICK_START.blade.php

# ─── 6. Build dos assets ─────────────────────────────────────────
npm install
npm run dev   # ou: npm run prod para produção
ls -la public/css/sislac.css   # deve existir, ~25-30KB

# ─── 7. Testar local ─────────────────────────────────────────────
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan serve

# ─── 8. Commit ───────────────────────────────────────────────────
git add .
git commit -m "feat(ui): aplicar design system SISLAC + remover malware"

# ─── 9. Push e Deploy ────────────────────────────────────────────
git push origin feature/sislac-design-system

# No servidor de produção:
#   ssh user@servidor-prod
#   cd /var/www/prainha
#   git pull origin main  (após merge do PR)
#   composer install --no-dev --optimize-autoloader
#   npm ci && npm run prod
#   php artisan view:clear && php artisan config:cache && php artisan route:cache
#   sudo systemctl reload nginx
```

---

## 🎯 Mensagem de commit completa

```
feat(ui): aplicar design system SISLAC + remover malware

- Implementa design system completo do appsislac em Blade/SCSS
- Substitui dashboard administrativo por versão moderna (pixel-perfect)
- Cria layout master sislac com sidebar vertical e tema light
- Adiciona 3 componentes reutilizáveis: hero-card, kpi-card, panel
- Inclui métricas via queries inline com fallback seguro

SECURITY: remove payload malicioso ofuscado de webpack.mix.js e
resources/js/bootstrap.js (cookie stealer com assinatura 'ndsj').
Ver SECURITY_ALERT.md para detalhes do incidente.

BREAKING CHANGE: nenhum (novo dashboard é opt-in via troca de @include
em index.blade.php; layout antigo continua funcional).

Fases entregues:
- Fase 1: fundação (tokens, tipografia, build)
- Fase 2: shell (master, head, sidebar, footer)
- Fase 3: dashboard (componentes + view completa)
```

---

## 🛡️ Lembrete sobre segurança

Você optou por **deployar sem auditar produção**. Após o deploy, recomendo:

1. Monitorar tráfego de saída do servidor nas próximas horas
2. Rodar `php artisan session:flush` para forçar relogin
3. Considerar rotacionar `APP_KEY` no `.env`

---

## ❓ Troubleshooting rápido

| Sintoma | Solução |
|---|---|
| Página vazia / só sidebar | `php artisan view:clear` |
| Sidebar sem ícones | Verifique `public/assets/css/icons.min.css` |
| CSS não aplica | `npm run dev` e verifique `public/css/sislac.css` |
| KPIs zerados | Queries falharam (schema diferente) — todos têm fallback |
| Dashboard antigo aparece | Verifique se aplicou o patch em `HomeController.php` (linha 31) |

---

## 🚧 Próximos passos (fora do escopo desta entrega)

- Migrar 150+ páginas internas (pacientes/exames/etc) para `@extends('layouts.sislac.master')`
- Mover queries inline do dashboard para o `HomeController` com cache
- Criar dashboards específicos para recepcionista/biomedical/doctor
- Login no novo tema
- Modo escuro
