<?php

namespace App\Application\Service;

use App\Entity\Ingredients\Ingredients;
use App\Entity\Recipes\Recipes;

class RecipeService
{
    /**
     * @var array $recipes
     */
    private $recipes;

    /**
     * @var array $ingredients
     */
    private $ingredients;

    /**
     * RecipeService constructor
     */
    public function __construct()
    {
        $this->recipes = Recipes::getRecipes();
        $this->ingredients = Ingredients::getIngredients();
    }

    /**
     * @param date $today
     * 
     * @return array recipe lists
     */
    public function recipeList($today)
    {
        return $this->checkIngredients($this->recipes, $this->ingredients, $today);
    }

    /**
     * @param array $recipes
     * @param array $ingredients
     * @param date $today
     * 
     * @return array sorted recipes
     */
    private function checkIngredients($recipes, $ingredients, $today)
    {
        $recipeScore = [];
        foreach ($recipes["recipes"] as $key => $recipe)
        {
            $exists = 0;
            $score = array(
                "index" => $key,
                "score" => 0,
            );

            foreach ($recipe["ingredients"] as $list)
            {
                foreach ($ingredients["ingredients"] as $item)
                {
                    if ($item["title"] == $list && !$this->expiryDate($item["use-by"], $today)) {
                        if ($this->expiryDate($item["best-before"], $today)) {
                            $score["score"] += $this->checkScore($item["best-before"], $today);
                        }
                        $exists += 1;
                        break;
                    }
                }
            }

            if ($exists == count($recipe["ingredients"])) {
                array_push($recipeScore, $score);
            }
        }
        return $this->sortRecipe($recipeScore, $recipes);
    }

    /**
     * @param array $recipeScore
     * @param array $recipes
     * 
     * @return array sorted recipes
     */
    private function sortRecipe($recipeScore, $recipes)
    {
        usort($recipeScore, function($a, $b) {
            return $a["score"] <=> $b["score"];
        });

        $recipeList["recipes"] = [];
        foreach ($recipeScore as $list)
        {
            array_push($recipeList["recipes"], $recipes["recipes"][$list["index"]]);
        }
        return $recipeList;
    }

    /**
     * @param date $expiry
     * @param date $today
     * 
     * @return bool
     */
    public function expiryDate($expiry, $today)
    {
        if (strtotime($expiry) >= strtotime($today)) {
            return false;
        }
        return true;
    }

    /**
     * @param date $date
     * @param date $today
     * 
     * @return int excess days
     */
    public function checkScore($date, $today)
    {
        return (strtotime($today) - strtotime($date)) / (60 * 60 * 24);
    }
}
