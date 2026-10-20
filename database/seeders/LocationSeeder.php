<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\City;
use App\Models\Locality;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => 'España',
                'code' => 'ES',
                'cities' => [
                    [
                        'name' => 'Madrid',
                        'localities' => [
                            'Centro',
                            'Salamanca',
                            'Chamberí',
                            'Chamartín',
                            'Retiro',
                        ],
                    ],
                    [
                        'name' => 'Barcelona',
                        'localities' => [
                            'Eixample',
                            'Gràcia',
                            'Sants-Montjuïc',
                            'Sant Martí',
                        ],
                    ],
                    [
                        'name' => 'Valencia',
                        'localities' => [
                            'Ciutat Vella',
                            'Ruzafa',
                            'Benimaclet',
                        ],
                    ],
                    [
                        'name' => 'Sevilla',
                        'localities' => [
                            'Triana',
                            'Nervión',
                            'Los Remedios',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Portugal',
                'code' => 'PT',
                'cities' => [
                    [
                        'name' => 'Lisboa',
                        'localities' => [
                            'Alfama',
                            'Belém',
                            'Baixa',
                        ],
                    ],
                    [
                        'name' => 'Oporto',
                        'localities' => [
                            'Centro Histórico',
                            'Boavista',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Francia',
                'code' => 'FR',
                'cities' => [
                    [
                        'name' => 'París',
                        'localities' => [
                            'Montmartre',
                            'Le Marais',
                            'Montparnasse',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($countries as $countryData) {
            $cities = $countryData['cities'];
            unset($countryData['cities']);

            $country = Country::firstOrCreate(
                ['code' => $countryData['code']],
                ['name' => $countryData['name']]
            );

            foreach ($cities as $cityData) {
                $localities = $cityData['localities'];
                unset($cityData['localities']);

                $city = City::firstOrCreate(
                    [
                        'country_id' => $country->id,
                        'name' => $cityData['name'],
                    ]
                );

                foreach ($localities as $localityName) {
                    Locality::firstOrCreate([
                        'city_id' => $city->id,
                        'name' => $localityName,
                    ]);
                }
            }
        }
    }
}
