# Deployment Guide - Buttercloud Bakery

This guide covers deploying your Laravel application to **Railway** (recommended for full-stack Laravel apps).

---

## 🚂 Railway Deployment (Recommended)

Railway is perfect for Laravel applications as it handles both backend and database seamlessly.

### Prerequisites
- GitHub account
- [Railway account](https://railway.app/) (sign up with GitHub)
- Your code pushed to GitHub repository

### Step-by-Step Deployment

#### 1. Push Your Code to GitHub

```powershell
# Make sure all files are committed
git add .
git commit -m "Add deployment configurations"
git push origin main
```

#### 2. Deploy to Railway

1. **Go to [Railway.app](https://railway.app/)**
2. **Click "Start a New Project"**
3. **Select "Deploy from GitHub repo"**
4. **Choose your repository:** `orbasidoericka/Cookie-ni-Tepay`
5. **Railway will auto-detect Laravel** and use the nixpacks.toml configuration

#### 3. Configure Environment Variables

In your Railway project dashboard:

1. Click on your service
2. Go to **"Variables"** tab
3. Click **"Raw Editor"**
4. Add these variables:

```env
APP_NAME="Buttercloud Bakery"
APP_ENV=production
APP_KEY=base64:GENERATE_THIS_KEY
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

DB_CONNECTION=sqlite

SESSION_DRIVER=cookie
SESSION_LIFETIME=120

CACHE_STORE=file
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=error
```

#### 4. Generate APP_KEY

Railway will automatically run migrations, but you need to generate an APP_KEY:

**Option A: Generate locally and copy**
```powershell
php artisan key:generate --show
```
Copy the output and paste it as `APP_KEY` in Railway variables.

**Option B: Generate in Railway**
1. Go to your Railway project
3. Find the generated key after first deployment
4. Or use Railway's CLI to run: `railway run php artisan key:generate`
#### 5. Update APP_URL

After deployment, Railway will provide a URL like:
```
https://your-app.up.railway.app
```

#### 6. Seed the Database (Optional)

If you want to populate the products after you have provisioned a MySQL plugin:

1. In Railway dashboard, click on your service
2. Go to **"Settings"** → **"Deploy Triggers"** (or run manually via CLI)
3. Or use Railway CLI to run seeders:

```powershell
railway run php artisan db:seed --class=ProductSeeder --force
```

### Helper scripts (local)

Two helper scripts are included to help import `database/legends.sql` after you create your MySQL plugin and set the Railway variables locally (or in your shell environment):

- `scripts/import-db.sh` : Linux/macOS (or WSL on Windows) with a local `mysql` client installed.
- `scripts/import-db.ps1` : Windows PowerShell script to import via the `mysql` client.

Usage example (PowerShell):

```powershell
$env:DB_HOST="<host>"; $env:DB_PORT="<port>"; $env:DB_USERNAME="<user>"; $env:DB_PASSWORD="<pass>"; $env:DB_DATABASE="<db>"
.\scripts\import-db.ps1 -SqlFile database/legends.sql
```

Usage example (bash):

```bash
export DB_HOST=<host> DB_PORT=<port> DB_USERNAME=<user> DB_PASSWORD=<pass> DB_DATABASE=<db>
./scripts/import-db.sh database/legends.sql
```
The following files have been created for deployment:

### Railway Configuration Files
- **`Procfile`** - Defines how Railway should start your app
- **`nixpacks.toml`** - Build configuration for PHP and dependencies
- **`railway.json`** - Railway-specific settings
- **`.env.example`** - Template for environment variables

### Vercel Configuration Files (Alternative)
- **`vercel.json`** - Vercel deployment configuration
- **`api/index.php`** - Serverless entry point

---

## 🔧 Important Notes

### Database
- The app uses **SQLite** which is perfect for Railway deployment
- Database file is stored persistently in Railway's volume
- No need for separate database service

### MySQL (Optional, Recommended if you have existing data)

If you'd like to use MySQL on Railway instead of SQLite (for example, to import your existing `database/legends.sql` dump), follow these steps:

1. Add a **MySQL plugin** to your Railway project
	- In your Railway project dashboard, click **Plugins** → **Add Plugin** → **MySQL**
	- Railway will provision a MySQL database and provide connection details.

2. Configure environment variables in Railway
	- Go to **Variables** on the service page and add the following:

```
DB_CONNECTION=mysql
DB_HOST=<from-railway-plugin>
DB_PORT=<from-railway-plugin>
DB_DATABASE=<from-railway-plugin>
DB_USERNAME=<from-railway-plugin>
DB_PASSWORD=<from-railway-plugin>
APP_KEY=base64:<generated or set manually>
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<your-railway-domain>.up.railway.app
```

3. Generate or copy `APP_KEY` into Railway
	- Locally run: `php artisan key:generate --show` and copy the output into Railway's `APP_KEY` variable.

4. Import the `database/legends.sql` dump

There are several ways to import your existing `database/legends.sql` into Railway's MySQL:

**Option A — Railway Dashboard UI (recommended)**
- Open the MySQL plugin in Railway and use the **Import** option to upload `database/legends.sql`.

**Option B — Use MySQL client locally**
- Install MySQL client on your machine and run (replace placeholders with your Railway plugin credentials):

```powershell
mysql -h <DB_HOST> -P <DB_PORT> -u <DB_USERNAME> -p <DB_DATABASE> < database/legends.sql
```

**Option C — Use Railway CLI**
- Install Railway CLI (`npm i -g @railway/cli` or via brew).
- Run `railway connect` and use the provided command to connect, then run import as in Option B.

5. Run migrations and seeders (if necessary)

```powershell
railway run php artisan migrate --force
railway run php artisan db:seed --class=ProductSeeder --force
```

6. Verify the site
- Visit the `APP_URL` and confirm your MySQL data is present.

Notes:
- If your `DB_CONNECTION` is set to `mysql` in Railways' variables, the app will use the MySQL plugin.
- Adjust `SESSION_DRIVER`, `CACHE_DRIVER`, and other storage variables if you plan to use serverless persistence or a different cache backend.

### Storage
- Railway provides persistent storage for SQLite database
- Sessions are using cookie driver (stateless)
- File cache is used for simplicity

### Sessions
- Changed from `database` to `cookie` driver for stateless deployments
- Works better with serverless/container environments

### Auto-Deploy
- Railway automatically deploys when you push to GitHub
- Enable auto-deploy in Railway project settings
- Every git push triggers a new deployment

---

## 🐛 Troubleshooting

### Application Key Error
If you see "No application encryption key has been specified":
```powershell
# Generate key locally
php artisan key:generate --show

# Add to Railway environment variables
APP_KEY=base64:YourGeneratedKeyHere
```

### 500 Error
1. Check Railway logs: Click on your service → "Logs"
2. Set `APP_DEBUG=true` temporarily to see detailed errors
3. Ensure all environment variables are set
4. Check if migrations ran successfully

### Database Not Created
Railway runs migrations automatically via the Procfile. Check logs for migration errors.

### Static Assets Not Loading
Make sure your `APP_URL` matches your Railway domain exactly.

---

## 🔄 Updating Your Deployment

To deploy updates:

```powershell
# Make changes to your code
git add .
git commit -m "Your update message"
git push origin main

# Railway automatically deploys the new version
```

---

## 🎉 Success!

Your Buttercloud Bakery shop is now live on Railway! 

**Test the following features:**
- Browse products at `/`
- Add items to cart
- View cart
- Complete checkout with contact number validation
- Check stock tracking (out of stock / low stock warnings)
- View order confirmation

---

## 💡 Tips

1. **Custom Domain**: Railway allows you to add custom domains in project settings
2. **Monitoring**: Use Railway's built-in monitoring to track performance
3. **Logs**: Always check logs if something goes wrong
4. **Environment Variables**: Never commit `.env` file - use Railway's variables
5. **Database Backups**: Download your SQLite database periodically from Railway

---

## 🚀 Alternative: Vercel Deployment

**Note:** Vercel is primarily for static/serverless sites. Railway is recommended for Laravel.

If you still want to try Vercel:
1. Install Vercel CLI: `npm i -g vercel`
2. Run: `vercel`
3. Follow prompts
4. **Limitations:** Database persistence is difficult, sessions won't work well

**Recommendation:** Use Railway instead for the best Laravel experience!

---

## 📞 Support

If you encounter issues:
1. Check Railway documentation: https://docs.railway.app/
2. Review Laravel deployment guide: https://laravel.com/docs/deployment
3. Check application logs in Railway dashboard
4. Verify all environment variables are set correctly

---

**Happy Baking! 🥐✨**
