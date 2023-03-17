<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenderSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders =  [
            "Ação",
            "Aventura",
            "Chick-lit",
            "Clássico",
            "Comédia",
            "Comédia romântica",
            "Distopia",
            "Drama",
            "Espionagem",
            "Fantasia",
            "Ficção científica",
            "Gótico",
            "Guerra",
            "Histórico",
            "Horror",
            "Infantil",
            "Jovem adulto",
            "Literatura estrangeira",
            "Literatura nacional",
            "Mistério",
            "Não-ficção",
            "Poesia",
            "Policial",
            "Romance",
            "Romance histórico",
            "Suspense",
            "Terror",
            "Thriller"
        ];

        foreach ($genders as $gender) {
            $new_gender = new Gender();
            $new_gender->fill(['key' => $gender])->save();
        }
    }
}
