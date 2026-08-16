# AGENTS.md

## Project Overview
Laravel backend service. Follow `1.x` flow. Make all changes in `1.x` branch only.

## Branch and PR Rules
- Base branch for PR: `1.x`
- **CRITICAL**: When creating a PR using GitHub CLI (`gh pr create`), you MUST explicitly set the base branch to 1.x using `--base 1.x`.
- Never commit directly to `1.x`
- Use short-lived branches from `1.x`: `feature/*`, `fix/*`, `chore/*`, `hotfix/*`

## Env and Secrets
- Never commit secrets or `.env`
- Use `.env.example` as template
- Any new env var must be documented in README

## DB and Migration Policy
- Put schema changes in migrations
- Keep migrations backward-safe for rolling deploys
- Include migration note in PR

## Definition of Done
- Code updated on `1.x`
- Tests pass locally (`php artisan test`)
- Static analysis passes when applicable
- Build passes for touched frontend assets (`npm run build`)
- Delete new agent branch after task done

## Fast Path Commands
```bash
git checkout develop
git pull --ff-only
composer install
php artisan test
npm install
npm run build
```