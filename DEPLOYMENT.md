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
2. Open the deployment logs
3. Find the generated key after first deployment
4. Or use Railway's CLI to run: `railway run php artisan key:generate`

#### 5. Update APP_URL

After deployment, Railway will provide a URL like:
```
https://your-app.up.railway.app
```

Update the `APP_URL` environment variable with this URL.

#### 6. Seed the Database (Optional)

If you want to populate the products:

1. In Railway dashboard, click on your service
2. Go to **"Settings"** → **"Deploy Triggers"**
3. Or use Railway CLI:
```powershell
railway run php artisan db:seed --class=ProductSeeder
```

#### 7. Access Your Site

Your site will be live at: `https://your-app.up.railway.app`

---

## 📁 File Structure for Deployment

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
