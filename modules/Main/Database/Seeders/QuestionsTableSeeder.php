<?php

namespace Modules\Main\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use Faker\Generator;
use Modules\Main\Repositories\QuestionRepository;

class QuestionsTableSeeder extends Seeder
{
    const SEEDER_PLANT_SIZE = 7;

    /**
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $faker = Faker::create('ru_RU');
        $questionRepo = app(QuestionRepository::class);

        foreach (range(1, self::SEEDER_PLANT_SIZE) as $i) {
            $questionRepo->store([
                'title' => e($faker->realText(80)),
                'content' => e($faker->realText(1024)),
                'order' => $i,
            ]);
        }
    }
}
