<?php

namespace App\Entity\Recipes;

class Recipes
{
    public static function getRecipes()
    {
        $f = file_get_contents(__DIR__."/data.json");
        return json_decode($f, true);
    }
}
