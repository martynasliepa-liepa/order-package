<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vartotoju ir Prekiu modeliai
    |--------------------------------------------------------------------------
    | Jei pagrindinis projektas turi savo Users ar Products modelius,
    | irasykite pilnus ju klasiu kelius cia.
    */
    'user_model' =>\App\Models\User::class, // \App\Models\User::class, null, Pagrindinio projekto User modelis 
    'item_model' => \App\Models\Product::class, // Pvz., \App\Models\Product::class, null, Pagrindinio projekto Product modelis
];