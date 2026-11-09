<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demo accounts with a dummy password, never a real one
        $ana = User::create(['name' => 'Ana Reyes', 'email' => 'ana@example.com', 'password' => 'password123']);
        $ben = User::create(['name' => 'Ben Cruz',  'email' => 'ben@example.com', 'password' => 'password123']);

        // 12 books so page 2 exists (10 per page)
        $ana->books()->createMany(collect(range(1, 12))->map(fn ($i) => [
            'title'  => "Sample Book $i",
            'author' => "Author $i",
            'status' => ['to-read', 'reading', 'finished'][$i % 3],
            'rating' => $i % 3 === 2 ? ($i % 5) + 1 : null,
        ])->all());

        $ben->books()->createMany([
            ['title' => 'Noli Me Tangere',  'author' => 'Jose Rizal',    'status' => 'finished', 'rating' => 5],
            ['title' => 'El Filibusterismo', 'author' => 'Jose Rizal',    'status' => 'reading'],
            ['title' => 'Dekada 70',         'author' => 'Lualhati Bautista', 'status' => 'to-read'],
            ['title' => 'Clean Code',        'author' => 'Robert C. Martin',  'status' => 'finished', 'rating' => 4],
        ]);
    }
}