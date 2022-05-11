### SETUP

```bash
composer install
```

- isi .env

```bash
php artisan key:generate
```

```bash
php artisan storage:link
```

### SETUP USERDATABASE

Bikin file `database.sqlite` di dalam database.

- Bikin database/table
```bash
php artisan migrate
```

- Jalankan ini untuk membuat user default superadmin dan admin
```bash
php artisan db:seed
```

### SETUP ROLES/PERMISSION

- Di terminal jalankan ini
```bash
php artisan tinker
```

- Setelah itu jalankan ini satu per satu.
```php
$superadmin = User::where("username", "superadmin")->first();
$superadmin = User::where("username", "admin")->first();

$admin->syncRoles(["superadmin"]);
$admin->syncRoles(["admin"]);
```