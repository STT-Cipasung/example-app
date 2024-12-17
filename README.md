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
    # Tambahkan opsi -d jika ingin jalankan di background
    ./vendor/bin/sail up
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
