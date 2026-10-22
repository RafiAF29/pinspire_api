<?php

namespace Database\Seeders;

use App\Models\Pin;
use App\Models\User;
use Illuminate\Database\Seeder;

class PinSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        // Data pin contoh — gambar dari picsum.photos (placeholder gratis).
        $pins = [
            ['title' => 'Anak anjing lucu',        'aspect' => 0.75, 'image_url' => 'https://picsum.photos/seed/pin1/400/533'],
            ['title' => 'Resep kue coklat',         'aspect' => 0.80, 'image_url' => 'https://picsum.photos/seed/pin2/400/500'],
            ['title' => 'Dekorasi kamar minimalis', 'aspect' => 1.00, 'image_url' => 'https://picsum.photos/seed/pin3/400/400'],
            ['title' => 'Sunset di pantai',         'aspect' => 0.67, 'image_url' => 'https://picsum.photos/seed/pin4/400/600'],
            ['title' => null,                       'aspect' => 1.20, 'image_url' => 'https://picsum.photos/seed/pin5/480/400'],
            ['title' => 'Outfit hari ini',          'aspect' => 0.60, 'image_url' => 'https://picsum.photos/seed/pin6/360/600'],
            ['title' => 'Wallpaper estetik',        'aspect' => 0.56, 'image_url' => 'https://picsum.photos/seed/pin7/360/640'],
            ['title' => 'Kucing tidur',             'aspect' => 1.00, 'image_url' => 'https://picsum.photos/seed/pin8/400/400'],
            ['title' => 'Taman bunga',              'aspect' => 0.75, 'image_url' => 'https://picsum.photos/seed/pin9/400/533'],
            ['title' => 'Anime fan art',            'aspect' => 0.80, 'image_url' => 'https://picsum.photos/seed/pin10/400/500', 'ai_modified' => true],
            ['title' => 'Kopi pagi',                'aspect' => 1.00, 'image_url' => 'https://picsum.photos/seed/pin11/400/400'],
            ['title' => 'Desain logo kreatif',      'aspect' => 1.00, 'image_url' => 'https://picsum.photos/seed/pin12/400/400', 'ai_modified' => true],
            ['title' => null,                       'aspect' => 0.67, 'image_url' => 'https://picsum.photos/seed/pin13/400/600'],
            ['title' => 'Rambut pendek wanita',     'aspect' => 0.75, 'image_url' => 'https://picsum.photos/seed/pin14/400/533'],
            ['title' => 'Nail art pink',            'aspect' => 1.00, 'image_url' => 'https://picsum.photos/seed/pin15/400/400'],
            ['title' => 'Sketsa pensil',            'aspect' => 0.80, 'image_url' => 'https://picsum.photos/seed/pin16/400/500'],
        ];

        foreach ($pins as $pinData) {
            Pin::create(array_merge($pinData, [
                'user_id' => $user->id,
            ]));
        }
    }
}
