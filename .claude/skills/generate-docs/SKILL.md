---
name: generate-docs
description: Generate the project README.md from the repo's actual state, or update the existing one in place when it has drifted. Use whenever documentation, a README, or project docs are requested.
---

# Generate Docs

Produce `README.md` at the repo root: **create** it if it is missing, **update** it if it
is already there. Everything in it must be a fact read out of this repo — a README that
describes a project that no longer exists is worse than no README.

## 1. Decide create vs. update

```bash
ls README.md README_Old.md
```

- **`README.md` missing** → create it (§4). `README_Old.md` is a superseded draft, not a
  source of truth: it still calls the app `llb-pdf`, claims Docker is the only supported
  path, and lists two route files when there are now six. Mine it for *structure*, verify
  every sentence before reusing it, and leave the file itself alone.
- **`README.md` present** → update it (§5).

Read `CLAUDE.md` either way. Where the README and `CLAUDE.md` disagree about a convention,
`CLAUDE.md` wins — and the README should not restate it at length, just point at it.

## 2. Gather facts before writing a word

Read files; don't recall. This repo drifts faster than its docs.

| Claim in the README | Read this |
|---|---|
| Stack, PHP/Laravel version, scripts | `composer.json`, `package.json` |
| Setup steps | `composer.json` `scripts.setup` / `scripts.dev`, `.env.example`, `.env.docker.example` |
| Route files, prefixes, name prefixes | the `using:` closure in `bootstrap/app.php`, then `ls routes/` |
| Docker services and ports | `docker-compose.local.yml`, `Dockerfile` |
| Test setup (connection, suites) | `phpunit.xml`, `ls tests/Feature` |
| Layering / conventions | `docs/code-structure/*.md`, `CLAUDE.md` |
| Seeded login | `database/seeders/` |
| Deployment | `DEPLOY.md` — link it, don't duplicate it |

Rules while gathering:

- `routes/` currently holds `admin-v1.php`, `public-v1.php`, `student-v1.php`,
  `teacher-v1.php`, `web.php`, `web-admin.php`. Build the route table from what
  `bootstrap/app.php` actually mounts, not from this list and not from `CLAUDE.md`.
- Prefer reading files over running commands. If you genuinely need
  `php artisan route:list`, ask first whether the environment is local or Docker —
  the two command sets are not interchangeable.
- Do not document a command you have not seen defined somewhere. No aspirational
  `npm run lint` that does not exist.
- If a fact cannot be established, leave it out. Never write a placeholder like
  `TODO` or `<your database>` into the README.

## 3. What the README covers

Aim for something a new developer can follow start to finish, in this order. Drop a
section rather than pad it.

1. **Title + one paragraph** — what the app is and the shape of it (one Laravel app
   serving Inertia pages *and* its own versioned JSON API).
2. **Getting started** — clone → env → install → key → migrate → run, as copy-pasteable
   blocks. Cover both the local (Laragon/native, `composer run dev`) and Docker paths,
   clearly separated, and say which `.env.*.example` belongs to which. Include the
   ordering traps that actually bite (copy `.env` before `docker compose up`; don't
   `npm install` on the host for the Docker path).
3. **Commands** — test, single test, formatter, `npx tsc --noEmit`, build.
4. **Architecture** — pages are render-only; the Route → Controller → FormRequest →
   Service → Model → Resource flow; the route-mount table; where controllers live.
   Link `docs/code-structure/*.md` instead of restating them.
5. **Database** — engine(s), Eloquent-only rule, transactions, FK constraints.
6. **Deployment** — one line pointing at `DEPLOY.md`.

Conventions for the prose: short sections, real commands in fenced blocks, tables for
anything that is a lookup (ports, route mounts). Say *why* where a step is
non-obvious — that is the part a reader cannot reconstruct from the code.

## 4. Creating a new README.md

Write the whole file in one `Write`. Then §6.

## 5. Updating an existing README.md

Surgical, not a rewrite. Read the file in full first, then:

- **Keep** everything still true — wording, ordering, voice, headings, badges, and any
  hand-written section that has no equivalent in §3. Someone chose those.
- **Correct** statements the repo contradicts: stale ports, renamed route files, commands
  that no longer exist, versions that moved.
- **Add** a §3 section only when it is missing *and* you have the facts for it.
- **Remove** only what is provably wrong — not what is merely brief.

Use `Edit` per change, not `Write` over the file. If the diff would touch nearly every
line, say so and confirm a full rewrite before doing it.

When nothing has drifted, change nothing and report that — a no-op is a valid result.

## 6. Verify before reporting

- Every relative link resolves (`docs/code-structure/backend-structure.md`, `DEPLOY.md`).
- Every command appears in `composer.json`, `package.json`, or is plain `artisan`/`docker
  compose`.
- Ports match `docker-compose.local.yml`.
- Fenced blocks are closed and tables render.

Then tell the user what you created or changed, and name anything you deliberately left
out for lack of a reliable source.

## 7. Boundaries

- Markdown only — the README is prose, so `CLAUDE.md`'s no-comments rule does not apply
  to it, but it does apply to any code you touch along the way.
- Do not commit or push. That is `commit-changes`, and only when asked.
- Do not edit `README_Old.md`, `CLAUDE.md`, `DEPLOY.md`, or `docs/**` unless asked —
  this skill owns `README.md`.
