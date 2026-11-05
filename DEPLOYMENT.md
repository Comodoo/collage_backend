# Deployment Guide — ZMC College LMS Backend

## Production server (recommended)

Do **not** use `php artisan serve` in production. Use **PHP-FPM + Nginx** or **Laravel Octane**.

### Option A: PHP-FPM + Nginx
1. Install PHP 8.2+ with FPM, Nginx, MySQL.
2. Point Nginx document root to `public/`.
3. Configure PHP-FPM pool (e.g., `www.conf`) with `pm = dynamic`, `pm.max_children = 50`.
4. Set `.env`:
   ```
   APP_ENV=production
   APP_DEBUG=false
   QUEUE_CONNECTION=database
   CACHE_DRIVER=redis (or file)
   ```
5. Run:
   ```
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan migrate --force
   php artisan db:seed --force
   ```
6. Set up a queue worker (systemd/supervisor):
   ```
   php artisan queue:work --sleep=3 --tries=3
   ```
7. Set up cron for scheduler:
   ```
   * * * * * cd /path/to/collage_backend && php artisan schedule:run >> /dev/null 2>&1
   ```

### Option B: Laravel Octane (Swoole/RoadRunner)
1. `composer require laravel/octane`
2. `php artisan octane:install`
3. `php artisan octane:start --server=swoole --host=0.0.0.0 --port=8000`

## Backups
- Daily database dump: `php artisan backup:database` (scheduled at 02:00).
- Backups stored in `storage/app/backups/`. Copy off-site.

## Scaling notes
- The biggest concurrency ceiling is the single-threaded dev server; moving to FPM/Octane resolves it.
- DB indexes are already added for hot paths.
- Permission cache TTL is 60s; role changes propagate after expiry or explicit cache forget.