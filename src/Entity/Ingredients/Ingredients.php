<?php

namespace App\Entity\Ingredients;

class Ingredients
{
    public static function getIngredients()
    {
        $f = file_get_contents(__DIR__."/data.json");
        return json_decode($f, true);
    }
}
