# Manga Reader



php artisan queue:work --tries=3 --queue="sync.index"
php artisan sync:releases 1