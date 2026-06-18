1.Susikurkite tuscia aplankala ir atsidarykite ji su visual code tada paleiskite komanda terminale
kuri sukurs nauja laravel projekta:

composer create-project laravel/laravel .

2.Sukurus projekta reikia prideti sia eilute i composer.json faila jusu projekte:
Pridekite sia eilute failo virsuje po description eilutes ( nuoroda i git)

    "repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/martynasliepa-liepa/order-package.git"
    }
],

3. Tada terminale paleisti sia komanda (packo atsisiuntimui):

composer require praktika/orders:dev-main

turi atsirasti aplankale vendors praktika folderis su packeto failais.

4. Dabar priregistruosime paketa i eikite i ->	bootstrap/providers.php 
ir ten pridekite sia eilute viduje return:

Praktika\Orders\OrdersServiceProvider::class,

5.Config failo sukurimui leiskite sia komanda terminale:

php artisan vendor:publish --tag=orders-config

sukuriamas failas kuriame galima nurodyti varotojo ar produktu modelius jo keisti nebutina sistema veikia ir be ju.

6.Sukurkite duomenu baze ir isitikinkite kad duomenys env faile yra teisingi ( naudojau xamp su mysql butinai ijunkite apache serveri ir mysql db)
cia pavizdys naudoju mysql db ir duomenu bazes pavadinimu testas2

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=testas2
DB_USERNAME=root
DB_PASSWORD=

7. Paleiskite migracija lenteliu sukurimui terminale

php artisan migrate

8. Pries uzpildant lenteles duomenis pridekite sia eilute i
faila i run funkcijos vidu->	database/seeders/DatabaseSeeder.php

$this->call(\Praktika\Orders\Database\Seeders\OrderStatusSeeder::class);

9. uzpidome duomenu baze butinais duomenimis kaip ( statusu reiksmes) su komanda terminale:

php artisan db:seed

10.Su komanda startuoajme serveri

php artisan serve

ir nasykleje nueikite i -> http://127.0.0.1:8000/admin/orders
