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
                    'title' => 'Nebula Drift',
                    'image' => 'https://picsum.photos/seed/movie-nebula-drift/600/400',
                    'description' => 'Kosmoselaev navigeerib ohtliku udupiirkonna kaudu, kus gravitatsioon käitub ettearvamatult.',
                    'director' => 'Marta Kask',
                    'release_year' => 2019,
                    'genre' => 'Sci-Fi',
                    'rating' => 8.4,
                    'user_id' => $user->id,
                ],
                [
                    'title' => 'Clockwork Heart',
                    'image' => 'https://picsum.photos/seed/movie-clockwork-heart/600/400',
                    'description' => 'Romaanne lugu inimesest, kes avastab masinast ehitatud südame salajase loo.',
                    'director' => 'Rasmus Värav',
                    'release_year' => 2021,
                    'genre' => 'Romance',
                    'rating' => 7.7,
                    'user_id' => $user->id,
                ],
                [
                    'title' => 'Midnight Courier',
                    'image' => 'https://picsum.photos/seed/movie-midnight-courier/600/400',
                    'description' => 'Salajane kuller toimetab pakke, mis avavad iga sihtkoha kohta uue müüdi.',
                    'director' => 'Liis Rand',
                    'release_year' => 2020,
                    'genre' => 'Thriller',
                    'rating' => 8.1,
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
                    'title' => 'Kas Sci-Fi peab olema tehniline?',
                    'description' => 'Ei pea. Sci‑Fi võib olla ka emotsioon: kuidas inimesed reageerivad uuele maailmale, mida nad kohe ei mõista.',
                    'user_id' => $user->id,
                ],
                [
                    'title' => 'Koomia näitab, kas teed õigeid asju',
                    'description' => 'Naer tuleb tihti siis, kui tegelased teevad midagi “liiga tõsiselt”—ja sina saad lõpuks aru, et see oli just õige lähenemine.',
                    'user_id' => $user->id,
                ],
                [
                    'title' => 'Hirm ja valgus',
                    'description' => '“Glass Fortress” näitab, kuidas hirm võib peituda rahulikus raamis. Mõnikord on parim efekt see, mis ei plahvata kohe.',
                    'user_id' => $user->id,
                ],
                [
                    'title' => 'Mis on hea kirjeldus?',
                    'description' => 'Kirjeldus peaks tekitama pildi: kelle lugu see on, mis olukorras nad on ja miks see on oluline. Üks lõik on tihti piisav.',
                    'user_id' => $user->id,
                ],
                [
                    'title' => 'Minu “Tide of Tomorrow” lemmikhetk',
                    'description' => 'Kõige rohkem meeldib mulle, kuidas lugu ei kiirusta. Mõned asjad saavad päriselt kokku alles siis, kui ootad.',
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
