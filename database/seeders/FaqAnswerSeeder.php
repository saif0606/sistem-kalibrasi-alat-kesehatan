<?php

namespace Database\Seeders;

use App\Models\FaqAnswer;
use Illuminate\Database\Seeder;

class FaqAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/jawaban_faq.csv');
        $handle = fopen($path, 'r');

        $header = fgetcsv($handle); // ['intent', 'jawaban']

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            FaqAnswer::updateOrCreate(
                ['intent' => $data['intent']],
                ['jawaban' => $data['jawaban']]
            );
        }

        fclose($handle);
    }
}