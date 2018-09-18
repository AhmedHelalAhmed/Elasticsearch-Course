<?php
use App\Duck;
use Illuminate\Database\Seeder;

class DucksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        // create 100 simple Ducks
        foreach(range(1,100) as $index)
        {
            $gender = $faker -> randomElement(['male', 'female']);
            $age = $faker -> numberBetween(1, 25);
            Duck::create([
                'name'       => $faker -> name($gender),
                'age'        => $age,
                'gender'     => $gender,
                'color'      => $faker -> safeColorName,
                'funkyDuck'  => $faker -> boolean,
                'home town'  => "{$faker->city}, {$faker->state}",
                'about'      => $faker -> realText(),
                'registered' => $faker -> dateTimeBetween("-{$age} years", 'now') -> format('Y-m-d'),
            ]);
        }
    }
}
