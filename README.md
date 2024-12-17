# Laravel
## Command

- Membuat Project Laravel Sail Baru

    ```bash
    curl -s "https://laravel.build/example-app" | bash
    ```

    - Memilih Sail Service (Contoh: mysql dan redis)

        ```bash
        curl -s "https://laravel.build/example-app?with=mysql,redis" | bash
        ```

- Running Laravel Sail dari Repository

    ```bash
    ./vendor/bin/sail up
    # Tambahkan opsi -d jika ingin jalankan di background
    ./vendor/bin/sail up -d
    ```

- Migrasi Awal Laravel

    ```bash
    ./vendor/bin/sail artisan migrate
    ```

- Membuat Model, Migration, dan Factory

    ```bash
    ./vendor/bin/sail artisan make:model ModelName -m -f
    ```
    - Contoh (Job Model)
        - Inisialisasi Model dengan Migration dan Factory

            ```bash
            ./vendor/bin/sail artisan make:model Job -m -f
            ```

- Membuat Controller

    ```bash
    ./vendor/bin/sail artisan make:controller ControllerName
    ```
    - Contoh (Job Controller)
        - Inisialisasi Controller dengan Resource (CRUD)

          ```bash
          ./vendor/bin/sail artisan make:controller JobController --resource
          ```
- Menjalankan Seeder

    ```bash
    ./vendor/bin/sail artisan migrate:refresh --seed
    ```

- Install Laravel Debugbar (https://github.com/barryvdh/laravel-debugbar)

    ```bash
    ./vendor/bin/sail composer require barryvdh/laravel-debugbar --dev
    ```

- Install Sail npm package

    ```bash
    ./vendor/bin/sail npm install
    ```

- Running Sail Vite

    ```bash
    ./vendor/bin/sail npm run dev
    ```
  - Jika tidak ingin menjalankan npm dibackground, gunakan perintah berikut (harus dijalankan ulang jika ada perubahan pada file css/js)

    ```bash
    ./vendor/bin/sail npm run build
    ```

- Membuat Layout Component

    ```bash
    ./vendor/bin/sail artisan make:component ComponentName
    ```
    - Contoh (Layout Component)
        - Aktifkan opsi --view untuk membuat file view

            ```bash
            ./vendor/bin/sail artisan make:component Layout --view
            ```
    - Reference https://laravel.com/docs/11.x/blade#defining-the-layout-component
    - Copy code berikut ke file `resources/view/layout.blade.php`

        ```php
        <!DOCTYPE html>
        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Laravel Job Board</title>
            @vite(['resource/css/app.css'])
        </head>
        <body>
            {{ $slot }}
        </body>
        </html>
        ```
    - Hapus file `resources/views/welcome.blade.php` dan hapus code berikut di file `routes/web.php`

        ```php
        Route::get('/', function () {
            return view('welcome');
        });
        ```
    - Ubah code pada file `routes/web.php` menjadi seperti berikut

        ```php
        <?php
        
        use App\Http\Controllers\JobController;
        use Illuminate\Support\Facades\Route;
        
        Route::resource('jobs', JobController::class)->only(['index']);
        ```
    - Cek apakah route sudah berjalan dengan baik

        ```bash
        ./vendor/bin/sail artisan route:list
        ```
    - Jika sudah, buat file `resources/views/job/index.blade.php` dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:view job.index
        ```
    - Ubah code pada method `index` di `JobController` menjadi seperti berikut

        ```php
        public function index() {
            return view('job.index', ['jobs' => Job::all()]);
        }
        ```
    - Ubah code pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <x-layout>
            @foreach ($jobs as $job)
                <div>{{ $job->title }}</div>
            @endforeach
        </x-layout>
        ```


