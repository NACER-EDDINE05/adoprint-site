# Déploiement Vercel — ADO PRINT (démo visuelle)

Ce dossier est prêt à être déployé sur **Vercel** pour présenter la boutique à un client.

> ⚠️ **Portée « démo visuelle »** — Vercel est une plateforme *serverless* au
> disque en lecture seule. La navigation, les produits, les prix et **toutes les
> images** fonctionnent, mais les **nouvelles données ne sont pas persistées**
> (commandes, panier, uploads, modifications admin). Pour une mise en production
> réelle avec écritures, utilisez Railway / Render / un VPS (voir plus bas).

---

## Ce qui est déjà préparé dans le repo

| Fichier | Rôle |
|---|---|
| `vercel.json` | Routage : requêtes dynamiques → PHP, assets statiques servis directement |
| `api/index.php` | Point d'entrée serverless ; copie la base SQLite dans `/tmp` (disque inscriptible) |
| `bootstrap/app.php` | `trustProxies(at: '*')` → URL HTTPS correctes derrière Vercel |
| `public/storage/` | Les **vraies images** (plus un lien symbolique) pour qu'elles soient déployées |
| `database/database.sqlite` | Base versionnée avec les 49 produits / 12 catégories |
| `public/build/` | CSS/JS compilés (Vite) |
| `.vercelignore` | Allège le bundle (exclut `vendor`, `node_modules`, caches, fichiers de dev) |

Variables d'environnement déjà définies dans `vercel.json` :
`APP_ENV=production`, `APP_DEBUG=false`, `SESSION_DRIVER=cookie`,
`CACHE_STORE=array`, `QUEUE_CONNECTION=sync`, `LOG_CHANNEL=stderr`,
`FILESYSTEM_DISK=local`, `DB_CONNECTION=sqlite`.

---

## Étape 1 — Pousser le code sur GitHub

Le dépôt Git local est déjà initialisé et le commit est fait. Depuis ce dossier :

```bash
# 1. Créer un repo VIDE sur https://github.com/new (pas de README/.gitignore)
# 2. Relier et pousser (remplacez VOTRE-USER / VOTRE-REPO) :
git remote add origin https://github.com/VOTRE-USER/VOTRE-REPO.git
git branch -M main
git push -u origin main
```

## Étape 2 — Importer dans Vercel

1. Allez sur https://vercel.com/new
2. **Import Git Repository** → choisissez votre repo GitHub.
3. Framework Preset : **Other** (le runtime PHP est défini par `vercel.json`).
4. Dans **Environment Variables**, ajoutez **obligatoirement** :

   | Clé | Valeur |
   |---|---|
   | `APP_KEY` | `base64:L6TeVZVyjLvLegKwXyUTk6tmAs0KtdnVY4kfopMLdWs=` |
   | `APP_URL` | `https://VOTRE-PROJET.vercel.app` |
   | `ASSET_URL` | `https://VOTRE-PROJET.vercel.app` |

   > `APP_URL` / `ASSET_URL` : mettez d'abord une valeur provisoire, déployez,
   > notez l'URL réelle fournie par Vercel, puis corrigez ces 2 variables et
   > redéployez. (`APP_KEY` doit être identique à celui du `.env` local pour
   | que les données chiffrées/sessions restent cohérentes.)

5. **Deploy**.

## Étape 3 — Vérifier

- Page d'accueil, produits, catégories, images → OK.
- Le menu **Nos Produits ▾** liste les catégories.
- Prix, grilles dégressives, pages À propos / Guides / Contact → OK.

---

## Commandes utiles (Vercel CLI, optionnel)

```bash
npm i -g vercel
vercel login
vercel --prod          # déploie ce dossier tel quel
vercel logs <url>      # voir les logs d'exécution PHP
vercel env ls          # lister les variables d'environnement
```

---

## Limites connues de la démo Vercel

- **Écritures non persistées** : une commande passée ou un upload admin
  n'est pas conservé (disque éphémère `/tmp` remis à zéro à chaque cold start).
- **Base en lecture** : les 49 produits existants s'affichent toujours.
- **Emails** : `MAIL_MAILER=log` (les messages partent dans les logs, pas dans
  une boîte mail). Configurez un provider SMTP si besoin.

## Pour une VRAIE mise en production (écritures + uploads + email)

Déployez la même base sur une plateforme à disque persistant + base gérée :
**Railway**, **Render**, **Laravel Forge** (VPS), ou **Hostinger**. Il suffit
alors de repasser `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION` sur
`database` et d'utiliser une base MySQL/Postgres persistante. Demandez-moi,
je prépare la configuration.
