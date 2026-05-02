# laravel 13.0
laravel new zapnet-billing

# filament 5.5
composer require filament/filament:"~5.5"
php artisan filament:install --panels
php artisan make:filament-user
php artisan vendor:publish --tag=filament-config

# filament-shield 4.2
composer require bezhansalleh/filament-shield
php artisan vendor:publish --tag="filament-shield-config"
'auth_provider_model' => 'App\\Models\\User',
use HasRoles;
php artisan shield:setup
php artisan shield:install
php artisan shield:generate --all
php artisan shield:super-admin

# filament-leaflet
composer require eduardoribeirodev/filament-leaflet
php artisan vendor:publish --tag=filament-leaflet

# run
php artisan serve --host=192.168.90.14 --port=8001
npm run dev

# new project

git clone https://github.com/will-shine/zapnet-billing.git
composer install
copy .env
php artisan livewire:publish --assets
php artisan storage:link
php artisan migrate
php artisan shield:generate --all
php artisan shield:super-admin
