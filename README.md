# Laravel
## Setup New Laravel Sail Project or Existing Repository

- Membuat Project Laravel Sail Baru

    ```bash
    curl -s "https://laravel.build/example-app" | bash
    ```

    - Memilih Sail Service (Contoh: mysql dan redis)

        ```bash
        curl -s "https://laravel.build/example-app?with=mysql,redis" | bash
        ```


### Setup Existing Repository
- Jika ingin menggunakan repository Laravel Sail

    ```bash
    git clone <repository-url>
    ```

- Docker run dengan command berikut

    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/opt \
        -w /opt \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs
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

## Job Model, Factory, and Migration

- Membuat Model, Migration, dan Factory

    ```bash
    ./vendor/bin/sail artisan make:model ModelName -m -f
    ```
    - Contoh (Job Model)
        - Inisialisasi Model dengan Migration dan Factory

            ```bash
            ./vendor/bin/sail artisan make:model Job -m -f
            ```

## Job Controller and Seeder
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

## Laravel Debugbar
- Install Laravel Debugbar (https://github.com/barryvdh/laravel-debugbar)

    ```bash
    ./vendor/bin/sail composer require barryvdh/laravel-debugbar --dev
    ```

## Vite and Tailwind CSS
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

## Blade Component and Layout
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
            @vite(['resources/css/app.css'])
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

## Jobs Page and Card Component
- Halaman Job dan Card Component
    - Tambahkan style pada layout component. Tambahkan style pada `<body>` di file `resources/views/components/layout.blade.php` sehinnga menjadi seperti berikut

        ```php
        ...
        <body class="mx-auto mt-10 max-w-2xl bg-slate-200 text-slate-700">
        ...
        ```
    - Buat sebuh component card dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:component Card --view
        ```
    - Tambahkan code berikut pada file `resources/views/components/card.blade.php`. Referensi https://laravel.com/docs/11.x/blade#default-merged-attributes

        ```php
        <div {{ $attributes->class(['rounded-md border border-slate-300 bg-white p-4 shadow-sm']) }}>
            {{ $slot }}
        </div>
        ```
    - Ubah code pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <x-layout>
            @foreach ($jobs as $job)
                <x-card class="mb-4">
                    {{ $job->title }}
                </x-card>
            @endforeach
        </x-layout>
        ```

## Job Page: Tag Component and Job Info
- Halaman Job: Tag Component dan Job Info
    - Ubah code pada file `resources/components/card.blade.php` dari `<div>` menjadi `<article>`. Agar lebih semantic

        ```php
        <article {{ $attributes->class(['rounded-md border border-slate-300 bg-white p-4 shadow-sm']) }}>
            {{ $slot }}
        </article>
        ```
    - Ubah code pada file `resources/views/job/index.blade.php` sehinnga menjadi seperti berikut

        ```php
        <x-layout>
            @foreach ($jobs as $job)
                <x-card class="mb-4">
                    <div class="mb-4 flex justify-between">
                        <h2 class="text-lg font-medium">
                            {{ $job->title }}
                        </h2>
                        <div class="text-slate-500">
                            ${{ number_format($job->salary) }}
                        </div>
                    </div>
                    
                    <p>{{ nl2br($job->description) }}</p>
                </x-card>
            @endforeach
        </x-layout>
        ```
    - Sesuaikan format description pada file sebelumnya dan ubah menjadi seperti berikut

        ```php
        <p class="text-sm text-slate-500">{!! nl2br(e($job->description)) !!}</p>
        ```
    - Tambahkan code berikut untuk menampilkan `Company Name`, `Location`, `Experiences`, dan `Category` pada file `resources/views/job/index.blade.php`

        ```php
        ...
        <div class="mb-4 flex justify-between text-sm text-slate-500">
            <div class="flex space-x-4">
                <div>Company Name</div>
                <div>{{ $job->location }}</div>
            </div>
            <div class="flex space-x-1 text-xs">
                <div>Experience</div>
                <div>{{ $job->category }}</div>
            </div>
        </div>
        
        <p class="text-sm text-slate-500">{!! nl2br(e($job->description)) !!}</p>
        ...
        ```
    - Ubah `<div>Experience</div>` dan `<div>{{ $job->category }}</div>` menjadi seperti berikut

        ```php
        <div class="rounder-md border px-2 py-1">{{ Str::ucfirst($job->experience) }}</div>
        <div class="rounder-md border px-2 py-1">{{ $job->category }}</div>
        ```
    - Buat sebuah component baru dengan nama `Tag` dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:component Tag --view
        ```
    - Pindahkan code `<div>` sebelumnya ke dalam component `Tag` pada file `resources/views/components/tag.blade.php`

        ```php
        <div {{ $attributes->class(['rounded-md border px-2 py-1']) }}>
            {{ $slot }}
        </div>
        ```
    - Ubah code sebelumnya pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <x-tag>{{ Str::ucfirst($job->experience) }}</x-tag>
        <x-tag>{{ $job->category }}</x-tag>
        ```
    - Tambahkan code pada file `routes/web.php` untuk handle halaman yang tidak ditemukan

        ```php
        Route::get('', function (){
            return to_route('jobs.index');
        });
        ```

