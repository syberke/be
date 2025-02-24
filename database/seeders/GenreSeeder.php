<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            ['id' => Str::uuid(), 'name' => 'Action'],
            ['id' => Str::uuid(), 'name' => 'Adventure'],
            ['id' => Str::uuid(), 'name' => 'Comedy'],
            ['id' => Str::uuid(), 'name' => 'Demons'],
            ['id' => Str::uuid(), 'name' => 'Drama'],
            ['id' => Str::uuid(), 'name' => 'Fantasy'],
            ['id' => Str::uuid(), 'name' => 'Horror'],
            ['id' => Str::uuid(), 'name' => 'Isekai'],
            ['id' => Str::uuid(), 'name' => 'Magic'],
            ['id' => Str::uuid(), 'name' => 'Martial Arts'],
            ['id' => Str::uuid(), 'name' => 'Mecha'],
            ['id' => Str::uuid(), 'name' => 'Military'],
            ['id' => Str::uuid(), 'name' => 'Mystery'],
            ['id' => Str::uuid(), 'name' => 'Psychological'],
            ['id' => Str::uuid(), 'name' => 'Romance'],
            ['id' => Str::uuid(), 'name' => 'Sci-Fi'],
            ['id' => Str::uuid(), 'name' => 'Slice of Life'],
            ['id' => Str::uuid(), 'name' => 'Sports'],
            ['id' => Str::uuid(), 'name' => 'Supernatural'],
            ['id' => Str::uuid(), 'name' => 'Thriller'],
        ]);
    }
}
