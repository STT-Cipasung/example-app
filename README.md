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

## Job Page: Job Card & Link Button Components
- Halaman Job: Job Card & Link Button Components
    - Tambahkan style `items-center` pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <div class="mb-4 flex items-center justify-between text-sm text-slate-500">
        ```
    - Ubah `routes/web.php` menjadi seperti berikut

        ```php
        Route::resource('jobs', JobController::class)->only(['index', 'show']);
        ```
    - Buat file `resources/views/job/show.blade.php` dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:view job.show
        ```
    - Ubah method `show` pada file JobController menjadi seperti berikut

        ```php
        public function show(Job $job) {
            return view('job.show', compact('job'));
        }
        ```
    - Tambahkan code berikut sebagai placeholder pada file `resources/views/job/show.blade.php`

        ```php
        <x-layout>
            <x-card>
                Job Details
            </x-card>
        </x-layout>
        ```
    - Ubah code di `<p class="text-sm text-slate-500">` pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <p class="text-sm text-slate-500 mb-4">{!! nl2br(e($job->description)) !!}</p>

        <div>
            <a href="{{ route('jobs.show', $job) }}">
                See
            </a>
        </div>
        ```
    - Tambahkan style pada `<a href="{{ route('jobs.show', $job) }}">` pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <a href="{{ route('jobs.show', $job) }}" 
            class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-center text-sm font-semibold text-black shadow-sm hover:bg-slate-100">
            See
        </a>
        ```
    - Extract `<a>` menjadi sebuah component dengan nama `LinkButton` dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:component LinkButton --view
        ```
    - Pindahkan code `<a>` sebelumnya ke dalam component `LinkButton` pada file `resources/views/components/link-button.blade.php` dan ubah menjadi seperti berikut

        ```php
        <a href="{{ $href }}"
            class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-center text-sm font-semibold text-black shadow-sm hover:bg-slate-100">
            {{ $slot }}
        </a>
      ```
    - Ubah code sebelumnya pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <x-link-button href="{{ route('jobs.show', $job) }}">
            See
        </x-link-button>
        ```
    - Buat component baru dengan nama `JobCard` dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:component JobCard --view
        ```
    - Pindahkan code sebelumnya pada file `resources/views/job/index.blade.php` ke dalam component `JobCard` pada file `resources/views/components/job-card.blade.php` dan ubah menjadi seperti berikut

        ```php
        <x-card class="mb-4">
            <div class="mb-4 flex justify-between">
                <h2 class="text-lg font-medium">
                    {{ $job->title }}
                </h2>
                <div class="text-slate-500">
                    ${{ number_format($job->salary) }}
                </div>
            </div>
            
            <div class="mb-4 flex items-center justify-between text-sm text-slate-500">
                <div class="flex space-x-4">
                    <div>Company Name</div>
                    <div>{{ $job->location }}</div>
                </div>
                <div class="flex space-x-1 text-xs">
                    <x-tag>{{ Str::ucfirst($job->experience) }}</x-tag>
                    <x-tag>{{ $job->category }}</x-tag>
                </div>
            </div>

            <p class="text-sm text-slate-500 mb-4">{!! nl2br(e($job->description)) !!}</p>
            
            {{ $slot }}
        </x-card>
        ```
    - Ubah code sebelumnya pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <x-job-card class="mb-4" :job="$job">
            <div>
                <x-link-button :href="route('jobs.show', $job)">
                    Show
                </x-link-button>
            </div>
        </x-job-card>
        ```
    - Ubah code pada file `resources/views/job/show.blade.php` menjadi seperti berikut

        ```php
        <x-layout>
            <x-job-card :job="$job" />
        </x-layout>
        ```

## Breadcrumbs Navigation
- Breadcrumbs Navigation
    - Tambahkan code pada file `resources/views/job/show.blade.php` menjadi seperti berikut

        ```php
        <nav>
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <li>→</li>
                <li>
                    <a href="{{ route('jobs.index') }}">Jobs</a>
                </li>
                <li>→</li>
                <li>{{ $job->title }}</li>
            </ul>
        </nav>
        ```
    - Tambahkan style pada file `resources/views/job/show.blade.php` menjadi seperti berikut

        ```php
        <nav class="mb-4">
            <ul class="flex space-x-4 text-slate-500">
        ```
    - Buat component baru dengan nama `Breadcrumbs` dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:component Breadcrumbs
        ```
    - Pindahkan code sebelumnya pada file `resources/views/job/show.blade.php` ke dalam component `Breadcrumbs` pada file `resources/views/components/breadcrumbs.blade.php` dan ubah menjadi seperti berikut

        ```php
        <nav class="mb-4">
            <ul class="flex space-x-4 text-slate-500">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>→</li>
                <li>
                    <a href="{{ route('jobs.index') }}">Jobs</a>
                </li>
                <li>→</li>
                <li>{{ $job->title }}</li>
            </ul>
        </nav>
        ```
    - Ubah code sebelumnya pada file `resources/views/job/show.blade.php` menjadi seperti berikut

        ```php
        <x-layout>
            <x-breadcrumbs :job="$job" />
            <x-job-card :job="$job" />
        </x-layout>
        ```
    - Ubah code pada file `resources/views/components/breadcrumbs.blade.php` agar menjadi lebih universal menjadi seperti berikut

        ```php
        <nav {{ $attributes }}>
            <ul class="flex space-x-4 text-slate-500">
            <li>
                <a href="/">Home</a>
            </li>

            @foreach($links as $label => $link)
                <li>→</li>
                <li>
                    <a href="{{ $link }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
            </ul>
        </nav>
        ```
    - Ubah code pada file `resources/views/job/show.blade.php` menjadi seperti berikut

        ```php
        <x-layout>
            <x-breadcrumbs class="mb-4" :links="['Jobs' => route('jobs.index'), $job->title => '#']"/>
            <x-job-card :job="$job"/>
        </x-layout>
        ```
    - Ubah code pada Breadcumbs class pada file `app/View/Components/Breadcrumbs.php` menjadi seperti berikut

        ```php
        public function __construct(public array $links)
        ```
    - Tambahkan breadcrumbs pada halaman `resources/views/job/index.blade.php` dengan code berikut

        ```php
        <x-layout>
            <x-breadcrumbs class="mb-4" :links="['Jobs' => '#']"/>
            ...
        </x-layout>
        ```

## Filtering Jobs: Tailwind Form Plugin & Text Inputs
- Filtering Jobs: Tailwind Form Plugin & Text Inputs
    - Reference https://tailwindcss.com/docs/plugins#forms
    - Install Tailwind Form Plugin

        ```bash
        ./vendor/bin/sail npm install -D @tailwindcss/forms
        ```
    - Edit `tailwind.config.js` dan tambahkan plugin `require('@tailwindcss/forms')` pada file tersebut

        ```js
        module.exports = {
            ...
            plugins: [
                require('@tailwindcss/forms'),
            ],
        }
        ```
    - Ubah code pada file `resources/views/job/index.blade.php` dengan menambahkan code berikut diantara `breadcrumbs` dan `@foreach`

        ```php
        <x-card class="mb-4 text-sm">
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>1</div>
                <div>2</div>
                <div>3</div>
                <div>4</div>
            </div>
        </x-card>
        ```
    - Buat component baru dengan nama `TextInput` dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:component TextInput
        ```
    - Ubah `TextInput` class pada file `app/View/Components/TextInput.php` menjadi seperti berikut

        ```php
        public function __construct(
            public ?string $value = null,
            public ?string $name = null,
            public ?string $placeholder = null,
        )
        {}
        ```
    - Ubah `TextInput` view pada file `resources/views/components/text-input.blade.php` menjadi seperti berikut

        ```php
        <input type="text" placeholder="{{ $placeholder }}" name="{{ $name }}" value="{{ $value }}" id="{{ $name }}"
       class="w-full rounded-md border-0 py-1.5 px-2.5 text-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:ring-2"/>
        ```
    - Ubah `<div>1</div>` pada file `resources/view/job/index.blade.php` menjadi sebagai berikut

        ```php
        <div>
            <div class="mb-1 font-semibold">Search</div>
            <x-text-input name="search" value="" placeholder="Search for any text" />
        </div>
        ```
    - Ubah `<div>2</div>` pada file `resources/views/job/index.blade.php` menjadi sebagai berikut

        ```php
        <div>
            <div class="mb-1 font-semibold">Salary</div>
                <div class="flex space-x-2">
                    <x-text-input name="min_salary" value="" placeholder="From" />
                    <x-text-input name="max_salary" value="" placeholder="To" />
                </div>
            </div>
        </div>
        ```

## Filtering Jobs: Form & Searching for Text in Job Posts
- Filtering Jobs: Form & Searching for Text in Job Posts
    - Refactor code pada file `resources/views/components/job-card.blade.php`, hapus bagian ini

        ```php
        <p class="text-sm text-slate-500 mb-4">{!! nl2br(e($job->description)) !!}</p>
        ```
    - Pindahkan code tersebut ke dalam file `resources/views/job/show.blade.php` dan ubah menjadi seperti berikut

        ```php
        ...
        <x-job-card :job="$job">
            <p class="text-sm text-slate-500 mb-4">{!! nl2br(e($job->description)) !!}</p>
        </x-job-card>
        ...
        ```
    - Refactor code pada file `resources/views/job/index.blade.php` dengan menambahkan code berikut diantara `breadcrumbs` dan `@foreach`

        ```php
        ...
        <x-card class="mb-4 text-sm">
            <form action="{{ route('jobs.index') }}" method="GET">
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <div class="mb-1 font-semibold">Search</div>
                        <x-text-input name="search" value="" placeholder="Search for any text"/>
                ...
                <button class="w-full">Filter</button>
            </form>
        </x-card>
        ...
        ```
    - Refactor code pada `JobController` untuk mendukung fungsi filter

        ```php
        ...
        public function index() {
            $jobs = Job::query();
            $jobs->when(request('search'), function ($query) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%');
            });

            return view('job.index', ['jobs' => $jobs->get()]);
        }
        ...
        ```

## Filtering Jobs: Min & Max Salary
- Filtering Jobs: Min & Max Salary
    - Refactor code pada file `resources/views/components/job-card.blade.php`, hapus bagian ini

        ```php
        <x-text-input name="search" value="{{ request('search') }}" placeholder="Search for any text"/>
        <x-text-input name="min_salary" value="{{ request('min_salary') }}" placeholder="From"/>
        <x-text-input name="max_salary" value="{{ request('max_salary') }}" placeholder="To"/>
        ```
    - Refactor code pada `JobController` untuk mendukung fungsi filter

        ```php
        ...
        public function index() {
            $jobs = Job::query();
            $jobs->when(request('search'), function ($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%' . request('search') . '%')
                        ->orWhere('description', 'like', '%' . request('search') . '%');
                });
            })->when(request('min_salary'), function ($query) {
                $query->where('salary', '>=', request('min_salary'));
            })->when(request('max_salary'), function ($query) {
                $query->where('salary', '<=', request('max_salary'));
            });

            return view('job.index', ['jobs' => $jobs->get()]);
        }
        ...
        ```

## Filtering Jobs: Radio Button Filters
- Pilih Salah Satu dari Beberapa
    - Ubah `<div>3</div>` pada file `resources/views/job/index.blade.php` menjadi sebagai berikut

        ```php
        <div>
            <div class="mb-1 font-semibold">Experience</div>

            <label for="experience" class="mb-1 flex items-center">
                <input type="radio" name="experience" value="" />
                <span class="ml-2">All</span>
            </label>
        </div>
        ```
    - Tambahkan sisa radio button pada file `resources/views/job/index.blade.php` dengan content sebagai berikut

        ```php
        <label for="experience" class="mb-1 flex items-center">
            <input type="radio" name="experience" value="junior" />
            <span class="ml-2">Junior</span>
        </label>
        <label for="experience" class="mb-1 flex items-center">
            <input type="radio" name="experience" value="middle" />
            <span class="ml-2">Middle</span>
        </label>
        <label for="experience" class="mb-1 flex items-center">
            <input type="radio" name="experience" value="senior" />
            <span class="ml-2">Senior</span>
        </label>
        ```
    - Refactor code pada `JobController` untuk mendukung fungsi filter

        ```php
        $jobs->when(request('search'), function ($query) {
            $query->where(function ($query) {
                $query->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            });
        })->when(request('min_salary'), function ($query) {
            $query->where('salary', '>=', request('min_salary'));
        })->when(request('max_salary'), function ($query) {
            $query->where('salary', '<=', request('max_salary'));
        })->when(request('experience'), function ($query) {
            $query->where('experience', request('experience'));
        });
        ```
    - Handle checkbox pada `resources/views/job/index.blade.php` dengan menggunakan referensi https://laravel.com/docs/11.x/blade#authentication-directives, dan rubah code menjadi seperti berikut

        ```php
        <label for="experience" class="mb-1 flex items-center">
            <input type="radio" name="experience" value=""
                @checked(!request('experience'))/>
            <span class="ml-2">All</span>
        </label>

        <label for="experience" class="mb-1 flex items-center">
            <input type="radio" name="experience" value="entry"
                @checked('entry' === request('experience'))/>
            <span class="ml-2">Entry</span>
        </label>

        <label for="experience" class="mb-1 flex items-center">
            <input type="radio" name="experience" value="intermediate"
                @checked('intermediate' === request('experience'))/>
            <span class="ml-2">Intermediate</span>
        </label>

        <label for="experience" class="mb-1 flex items-center">
            <input type="radio" name="experience" value="senior"
                @checked('senior' === request('experience'))/>
            <span class="ml-2">Senior</span>
        </label>
        ```
    - Buat component baru dengan nama `RadioGroup` dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:component RadioGroup
        ```
    - Refactor code pada `RadioGroup` class pada file `app/View/Components/RadioGroup.php` menjadi seperti berikut

        ```php
        public function __construct(
            public string $name,
            public array $options
        )
        {}
        ```
    - Copy code input pada file `resources/views/job/index.blade.php` ke dalam file `resources/views/components/radio-group.blade.php` dan ubah menjadi seperti berikut

        ```php
        <label for="experience" class="mb-1 flex items-center">
            <input type="radio" name="experience" value=""
                @checked(!request('experience'))/>
            <span class="ml-2">All</span>
        </label>

        <label for="experience" class="mb-1 flex items-center">
            <input type="radio" name="experience" value="entry"
                @checked('entry' === request('experience'))/>
            <span class="ml-2">Entry</span>
        </label>
        ```
    - Refactor code pada file `resources/views/components/radio-group.blade.php` menjadi seperti berikut

        ```php
        <label for="{{ $name }}" class="mb-1 flex items-center">
            <input type="radio" name="{{ $name }}" value=""
                @checked(!request($name))/>
            <span class="ml-2">All</span>
        </label>

        @foreach($options as $option)
            <label for="{{ $name }}" class="mb-1 flex items-center">
                <input type="radio" name="{{ $name }}" value="{{ $option }}"
                    @checked($option === request($name))/>
                <span class="ml-2">{{ $option }}</span>
            </label>
        @endforeach
        ```
    - Ubah code pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <x-radio-group name="experience" :options="\App\Models\Job::$experience" />
        ```
    - Refactor code `<div>4</div>` pada file `resources/views/job/index.blade.php` menjadi sebagai berikut

        ```php
        <div>
            <div class="mb-1 font-semibold">Category</div>
            
            <x-radio-group name="category" :options="\App\Models\Job::$category" />
        </div>
        ```
    - Refactor code pada `JobController` untuk mendukung fungsi filter

        ```php
        ...
        ->when(request('category'), function ($query) {
            $query->where('category', request('category'));
        })
        ...
        ```

## Filtering Jobs: Configuring Labels and Arrays in PHP
- Disini kita akan membuat array yang berisi label dan value untuk radio button
    - Refactor code `RadioGroup` class pada file `app/View/Components/RadioGroup.php` tambahkan fucntion seperti berikut

        ```php
        public function optionsWithLabels(): array
        {
            return  array_is_list($this->options)
                ? array_combine($this->options, $this->options)
                : $this->options;
        }
        ```
    - Refactor code pada file `resources/views/components/radio-group.blade.php` menjadi seperti berikut

        ```php
        @foreach($optionsWithLabels as $label => $option)
            <label for="{{ $name }}" class="mb-1 flex items-center">
                <input type="radio" name="{{ $name }}" value="{{ $option }}"
                    @checked($option === request($name))/>
                <span class="ml-2">{{ $label }}</span>
            </label>
        @endforeach
        ```
    - Refactor code `<x-radio-group name="experience" :options="\App\Models\Job::$experience" />` pada file `resources/views/job/index.blade.php` menjadi seperti berikut

        ```php
        <x-radio-group name="experience"
            :options="array_combine(array_map('ucfirst', \App\Models\Job::$experience), \App\Models\Job::$experience)"/>
        ```

## Filtering Jobs: Clearing the Input
- Clearing the Input
    - Heroicons (https://heroicons.com/)
    - Refactor code pada file `resources/views/components/text-input.blade.php` dengan menambahkan code berikut

        ```php
        <input type="text" placeholder="{{ $placeholder }}" name="{{ $name }}" value="{{ $value }}" id="{{ $name }}"
            class="w-full rounded-md border-0 py-1.5 px-2.5 text-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:ring-2"/>
        ```
    - Refactor code pada file `resources/views/job/index.blade.php` dengan menambahkan code berikut

        ```php
        <div class="relative">
            <button class="absolute top-0 right-0 flex h-full items-center pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="h-4 w-4 text-slate-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
            <input type="text" placeholder="{{ $placeholder }}" name="{{ $name }}" value="{{ $value }}" id="{{ $name }}"
                class="w-full rounded-md border-0 py-1.5 px-2.5 pr-8 text-sm ring-1 ring-slate-300 placeholder:text-slate-400 focus:ring-2"/>
        </div>
        ```
    - Tambahkan logic untuk button pada file `resources/views/components/text-input.blade.php` dengan menambahkan code berikut pada inline `<button class="absolute top-0 right-0 flex h-full items-center pr-2">`

        ```php
        <button type="button" class="absolute top-0 right-0 flex h-full items-center pr-2"
            onclick="document.getElementById('{{ $name }}').value= ''">
        ```
    - Refactor class `TextInput` pada file `app/View/Components/TextInput.php` dengan menambahkan attribute baru

        ```php
        public function __construct(
            public ?string $value = null,
            public ?string $name = null,
            public ?string $placeholder = null,
            public ?string $formId = null,
        )
        {}
        ```
    - Refactor code pada file `resources/views/components/text-input.blade.php` menjadi seperti berikut

        ```php
        @if($formId)
            <button type="button" class="absolute top-0 right-0 flex h-full items-center pr-2"
                    onclick="document.getElementById('{{ $name }}').value= ''">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor"
                    class="h-4 w-4 text-slate-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        @endif
        ```
    - Refactor code pada file `resources/views/job/index.blade.php` dengan `id` pada `<form action="{{ route('jobs.index') }}" method="GET">`

        ```php
        <form id="filtering-form" action="{{ route('jobs.index') }}" method="GET">
        ```
    - Refactor code pada file `resources/views/job/index.blade.php` dengan menambahkan `form-id` pada `<x-text-input name="search" value="{{ request('search') }}" placeholder="Search for any text"/>`

        ```php
        <x-text-input name="search" value="{{ request('search') }}" placeholder="Search for any text" form-id="filtering-form"/>
        ```
    - Refactor code pada file `resources/views/job/index.blade.php` pada `<x-text-input>` lainnya dengan menambahkan `form-id` pada masing-masing

        ```php
        <x-text-input name="min_salary" value="{{ request('min_salary') }}" placeholder="From" form-id="filtering-form"/>
        <x-text-input name="max_salary" value="{{ request('max_salary') }}" placeholder="To" form-id="filtering-form"/>
        ```
    - Refactor code pada file `resources/views/components/text-input.blade.php` dengan menambahkan logic submit form pada button

        ```php
        <button type="button" class="absolute top-0 right-0 flex h-full items-center pr-2"
                onclick="document.getElementById('{{ $name }}').value= ''; document.getElementById('{{ $formId }}').submit()">
        ```

## Refactor: Gradient Background, STyling Buttons, Adding Alpine.js
- Refactor: Gradient Background, STyling Buttons, Adding Alpine.js
    - Refactor code pada file `resources/views/components/layout.blade.php` dengan menambahkan style gradient background

        ```php
        <body class="mx-auto mt-10 max-w-2xl bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90% text-slate-700">
        ```
    - Refactor code pada file `resources/views/components/job-card.blade.php` dengan mengubah `<x-tag>{{ Str::ucfirst($job->experience) }}</x-tag>` menjadi sebagai berikut

        ```php
        <x-tag>
            <a href="{{ route('jobs.index', ['experience' => $job->experience]) }}">
                {{ Str::ucfirst($job->experience) }}
            </a>
        </x-tag>
        ```
    - Refactor code pada file `resources/views/components/job-card.blade.php` dengan mengubah `<x-tag>{{ $job->category }}</x-tag>` menjadi sebagai berikut

        ```php
        <x-tag>
            <a href="{{ route('jobs.index', ['category' => $job->category]) }}">
                {{ $job->category }}
            </a>
        </x-tag>
        ```
    - Buat sebuah component baru dengan nama `Button` dengan command berikut

        ```bash
        ./vendor/bin/sail artisan make:component Button --view
        ```
    - Refactor code pada component baru yang telah dibuat pada file `resources/views/components/button.blade.php` dengan code berikut

        ```php
        <button {{ $attributes->class(['rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-center text-sm font-semibold text-black shadow-sm hover:bg-slate-100']) }}>
            {{ $slot }}
        </button>
        ```
    - Refactor code pada file `resources/views/job/index.blade.php` dengan menambahkan button pada form

        ```php
        <x-button class="w-full">Filter</x-button>
        ```
    - Install Alpine.js https://alpinejs.dev/

        ```bash
        ./vendor/bin/sail npm install alpinejs
        ```
    - Refactor code pada file `resources/views/components/layout.blade.php` dengan menambahkan script Alpine.js

        ```php
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        ```
    - Refactor code pada file `resources/js/bootstrap.js` dengan menambahkan script Alpine.js

        ```js
        import axios from 'axios';
        import Alpine from 'alpinejs'

        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        window.Alpine = Alpine
        Alpine.start()
        ```
