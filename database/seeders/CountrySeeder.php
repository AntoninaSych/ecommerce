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
            "AL" => "Alabama",
            "AK" => "Alaska",
            "AS" => "American Samoa",
            "AZ" => "Arizona",
            "AR" => "Arkansas",
            "CA" => "California",
            "CO" => "Colorado",
            "CT" => "Connecticut",
            "DE" => "Delaware",
            "DC" => "District Of Columbia",
            "FM" => "Federated States Of Micronesia",
            "FL" => "Florida",
            "GA" => "Georgia",
            "GU" => "Guam",
            "HI" => "Hawaii",
            "ID" => "Idaho",
            "IL" => "Illinois",
            "IN" => "Indiana",
            "IA" => "Iowa",
            "KS" => "Kansas",
            "KY" => "Kentucky",
            "LA" => "Louisiana",
            "ME" => "Maine",
            "MH" => "Marshall Islands",
            "MD" => "Maryland",
            "MA" => "Massachusetts",
            "MI" => "Michigan",
            "MN" => "Minnesota",
            "MS" => "Mississippi",
            "MO" => "Missouri",
            "MT" => "Montana",
            "NE" => "Nebraska",
            "NV" => "Nevada",
            "NH" => "New Hampshire",
            "NJ" => "New Jersey",
            "NM" => "New Mexico",
            "NY" => "New York",
            "NC" => "North Carolina",
            "ND" => "North Dakota",
            "MP" => "Northern Mariana Islands",
            "OH" => "Ohio",
            "OK" => "Oklahoma",
            "OR" => "Oregon",
            "PW" => "Palau",
            "PA" => "Pennsylvania",
            "PR" => "Puerto Rico",
            "RI" => "Rhode Island",
            "SC" => "South Carolina",
            "SD" => "South Dakota",
            "TN" => "Tennessee",
            "TX" => "Texas",
            "UT" => "Utah",
            "VT" => "Vermont",
            "VI" => "Virgin Islands",
            "VA" => "Virginia",
            "WA" => "Washington",
            "WV" => "West Virginia",
            "WI" => "Wisconsin",
            "WY" => "Wyoming"
        ];
        //created several countries  for test
        $countries = [
//            ['code' => 'ua', 'name' => 'Ukraine', 'states' => null],
//            ['code' => 'uk', 'name' => 'UK', 'states' => null],
//            ['code' => 'ind', 'name' => 'India', 'states' => null],
            ['code' => 'usa', 'name' => 'United States of America', 'states' => json_encode($usaStates)],
//            ['code' => 'ger', 'name' => 'Germany', 'states' => null],
        ];
        Country::insert($countries);
    }
}
