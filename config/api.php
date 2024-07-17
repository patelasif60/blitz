<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party API
    |--------------------------------------------------------------------------
    |
    | This file is for storing the api of third party web services such
    | as countrystatecity and more. This file provides the de facto
    | location for this type of information, allowing product to have
    | a conventional file to locate the various web services api.
    |
    */

    'countrystatecity'                  => [
        'country'       =>  'https://api.countrystatecity.in/v1/countries',
        'state'         =>  'https://api.countrystatecity.in/v1/countries/{iso2}/states',
        'state_details' =>  'https://api.countrystatecity.in/v1/countries/{iso2}/states/{iso3}',
        'city'          =>  'https://api.countrystatecity.in/v1/countries/{iso2}/states/{iso3}/cities'
    ],
];
