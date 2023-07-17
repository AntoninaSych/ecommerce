<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('countries')->truncate();

        $usaStates = [
            "AL" => 'Alabama',
            "AK" => 'Alaska',
            "AZ" => 'Arizona',
            "AR" => 'Arkansas',
            "CA" => 'California',
        ];
        //created several countries  for test
        $countries = [
            ['code' => 'ua', 'name' => 'Ukraine', 'states' => null],
            ['code' => 'uk', 'name' => 'UK', 'states' => null],
            ['code' => 'ind', 'name' => 'India', 'states' => null],
            ['code' => 'usa', 'name' => 'United States of America', 'states' => json_encode($usaStates)],
            ['code' => 'ger', 'name' => 'Germany', 'states' => null],
        ];
        Country::insert($countries);
    }
}
