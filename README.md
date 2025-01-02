<h1 align="center">Monitor Eval System with Laravel</h1>

> Base url of this service is: http://localhost:8000

## How to Install

#### 1. Clone repository with the following command:

```bash
git clone https://github.com/arisafriyanto/monitor-eval-system.git
```

#### 2. Move to the repository directory with the command:

```bash
cd monitor-eval-system
```

#### 3. Run the following command to install the depedency:

```bash
composer install
npm install
npm run build
```

#### 4. Copy the `.env.example` file, rename it to `.env` and edit the `.env` file in the main directory, making sure the configuration values are appropriate:

```bash
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE={YOUR_DB_NAME}
DB_USERNAME={YOUR_DB_USERNAME}
DB_PASSWORD={YOUR_DB_PASSWORD}
```

#### 5. Generate the laravel key, with this command:

```bash
php artisan key:generate
```

#### 6. Run the migration and seeder with the following commands:

```bash
php artisan migrate
php artisan db:seed
php artisan laravolt:indonesia:seed
```

#### 7. Run the following command, to create a symbolic link:

```bash
php artisan storage:link
```

#### 8. Run the laravel project with the command:

```bash
php artisan serve
```

   <br>
  
## Documentation
### Login Credentials

| Role  | Email                 | Password  |
|-------|-----------------------|-----------|
| Admin | admin@example.com     | password  |
| User  | regional@example.com  | password  |

## Contact

Please contact [arisafriyanto1933@gmail.com](mailto:arisafriyanto1933@gmail.com).

#### Thank you !!
