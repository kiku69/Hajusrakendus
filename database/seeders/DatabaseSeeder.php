<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Movie;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Kui andmebaasis pole filme, lisame ~20 näidis-kirjet,
        // et `Filmid` leht oleks kohe sisuga.
        if (Movie::count() === 0) {
    $movies = [
        [
            'title' => 'Shadow Circuit',
            'image' => 'https://picsum.photos/seed/movie-shadow-circuit/600/400',
            'description' => 'Häkker avastab süsteemi, mis ennustab inimeste otsuseid enne, kui nad ise neist teadlikud on.',
            'director' => 'Karl Tamm',
            'release_year' => 2022,
            'genre' => 'Sci-Fi',
            'rating' => 8.5,
            'user_id' => $user->id,
        ],
        [
            'title' => 'Frozen Echo',
            'image' => 'https://picsum.photos/seed/movie-frozen-echo/600/400',
            'description' => 'Mahajäetud polaarjaamas hakkavad ilmuma helid, mis ei tohiks seal eksisteerida.',
            'director' => 'Anette Pärn',
            'release_year' => 2018,
            'genre' => 'Horror',
            'rating' => 7.9,
            'user_id' => $user->id,
        ],
        [
            'title' => 'Golden Horizon',
            'image' => 'https://picsum.photos/seed/movie-golden-horizon/600/400',
            'description' => 'Kaks rändurit otsivad legendaarset linna, mis ilmub ainult päikeseloojangu ajal.',
            'director' => 'Martin Õun',
            'release_year' => 2021,
            'genre' => 'Adventure',
            'rating' => 8.2,
            'user_id' => $user->id,
        ],
        [
            'title' => 'Broken Signals',
            'image' => 'https://picsum.photos/seed/movie-broken-signals/600/400',
            'description' => 'Raadiolained toovad sõnumeid tulevikust, kuid iga sõnum muudab olevikku ohtlikult.',
            'director' => 'Laura Kivi',
            'release_year' => 2020,
            'genre' => 'Thriller',
            'rating' => 8.0,
            'user_id' => $user->id,
        ],
        [
            'title' => 'Velvet Night',
            'image' => 'https://picsum.photos/seed/movie-velvet-night/600/400',
            'description' => 'Öine linn peidab saladust, kus iga kohtumine võib muuta inimese elu igaveseks.',
            'director' => 'Rene Vaher',
            'release_year' => 2017,
            'genre' => 'Drama',
            'rating' => 7.6,
            'user_id' => $user->id,
        ],
    ];

    foreach ($movies as $movie) {
        Movie::create($movie);
    }
}

        // Kui andmebaasis pole blogipostitusi, lisame mõned näited,
        // et `Blogi` leht poleks tühi.
        if (Post::count() === 0) {
    $posts = [
        [
            'title' => 'Kuidas alustada programmeerimisega?',
            'description' => 'Alusta väikestest sammudest: õpi üks keel selgeks ja tee lihtsaid projekte. Praktika on olulisem kui teooria.',
            'user_id' => $user->id,
        ],
        [
            'title' => 'Miks vead on kasulikud?',
            'description' => 'Iga error õpetab midagi uut. Kui midagi ei tööta, saad paremini aru, kuidas süsteem tegelikult toimib.',
            'user_id' => $user->id,
        ],
        [
            'title' => 'Hea disaini põhitõed',
            'description' => 'Lihtsus ja loetavus on kõige tähtsamad. Kasutaja peab aru saama, mida teha, ilma mõtlemata.',
            'user_id' => $user->id,
        ],
        [
            'title' => 'Kuidas jääda motiveerituks?',
            'description' => 'Sea endale väikesed eesmärgid ja tähista nende saavutamist. See hoiab motivatsiooni üleval.',
            'user_id' => $user->id,
        ],
        [
            'title' => 'Frontend vs Backend',
            'description' => 'Frontend tegeleb sellega, mida kasutaja näeb. Backend hoolitseb loogika ja andmete eest.',
            'user_id' => $user->id,
        ],
    ];

    foreach ($posts as $post) {
        Post::create($post);
    }
}

       $products = [
            ['name' => 'Juhtmevaba laadimisalus', 'description' => 'Kiirlaadimise toega juhtmevaba laadija, sobib kõigile Qi-seadmetele.', 'price' => 24.99, 'image' => 'https://picsum.photos/seed/wirelesscharger/400/400'],
            
            ['name' => 'LED laualamp', 'description' => 'Reguleeritava heleduse ja värvitemperatuuriga LED laualamp, ideaalne õppimiseks.', 'price' => 19.99, 'image' => 'https://picsum.photos/seed/ledlamp/400/400'],
            
            ['name' => 'Mängurihiir', 'description' => 'Täpsusanduriga RGB mängurihiir, mitme programmeeritava nupuga.', 'price' => 34.99, 'image' => 'https://picsum.photos/seed/gamingmouse/400/400'],
            
            ['name' => 'USB hub 5-porti', 'description' => 'Kompaktne USB jaotur, millega saad ühendada mitu seadet korraga.', 'price' => 17.49, 'image' => 'https://picsum.photos/seed/usbhub/400/400'],
            
            ['name' => 'Sülearvuti alus', 'description' => 'Ergonoomiline alus, mis parandab jahutust ja tõstab ekraani mugavamale kõrgusele.', 'price' => 27.99, 'image' => 'https://picsum.photos/seed/laptopstand/400/400'],
            
            ['name' => 'Juhtmevaba hiir', 'description' => 'Vaikne ja energiasäästlik juhtmevaba hiir igapäevaseks kasutamiseks.', 'price' => 15.99, 'image' => 'https://picsum.photos/seed/wirelessmouse/400/400'],
            
            ['name' => 'Mini projektor', 'description' => 'Kaasaskantav projektor HD resolutsiooniga, sobib filmide vaatamiseks kodus või reisil.', 'price' => 129.99, 'image' => 'https://picsum.photos/seed/projector/400/400'],
            
            ['name' => 'Välised kõvakettad 1TB', 'description' => 'Kompaktne ja kiire väline kõvaketas andmete varundamiseks.', 'price' => 59.99, 'image' => 'https://picsum.photos/seed/harddrive/400/400'],
            
            ['name' => 'Nutikell', 'description' => 'Tervise jälgimise ja teavitustega nutikell, pikk aku kestvus.', 'price' => 89.99, 'image' => 'https://picsum.photos/seed/smartwatch/400/400'],
            
            ['name' => 'Bluetooth klaviatuur', 'description' => 'Õhuke ja kerge Bluetooth klaviatuur, sobib nii telefonile kui arvutile.', 'price' => 44.99, 'image' => 'https://picsum.photos/seed/bluetoothkeyboard/400/400'],
        ];
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
