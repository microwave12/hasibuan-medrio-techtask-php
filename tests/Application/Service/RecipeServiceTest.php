<?php

namespace App\Tests\Application\Service;

use App\Application\Service\RecipeService;
use PHPUnit\Framework\TestCase;

class RecipeServiceTest extends TestCase
{
    public function testRecipeList()
    {
        $recipeService = new RecipeService();

        $checklist = array(
            array(
                "date" => "2019-03-06",
                "last_recipe" => "Hotdog",
                "recipe_count" => 3,
            ),
            array(
                "date" => "2019-03-07",
                "last_recipe" => "Salad",
                "recipe_count" => 3,
            ),
            array(
                "date" => "2019-03-08",
                "last_recipe" => "Hotdog",
                "recipe_count" => 2,
            ),
            array(
                "date" => "2019-03-09",
                "last_recipe" => "Ham and Cheese Toastie",
                "recipe_count" => 2,
            ),
            array(
                "date" => "2019-03-27",
                "last_recipe" => "Hotdog",
                "recipe_count" => 1,
            ),
            array(
                "date" => "2019-03-28",
                "last_recipe" => "",
                "recipe_count" => 0,
            ),
        );
        
        foreach ($checklist as $today)
        {
            $recipeList = $recipeService->recipeList($today["date"])["recipes"];

            if (!empty($recipeList)) {
                $this->assertEquals($today["last_recipe"],
                    $recipeList[count($recipeList) - 1]["title"],
                    "Last recipe on ".$today["date"]." should be ".$today["last_recipe"]."\n"
                );
            }

            $this->assertEquals($today["recipe_count"],
                count($recipeList),
                "Total recipe on ".$today["date"]." should be ".$today["recipe_count"]."\n"
            );
        }
    }

    public function testCheckScore()
    {
        $recipeService = new RecipeService();

        $today = "2019-03-13";
        $checklist = array(
            // Ham and Cheese Toastie ingredients best-before date & score in 2019-03-13
            array(
                "score" => 5,
                "best_before" => array(
                    "2019-03-25",
                    "2019-03-08",
                    "2019-03-25",
                    "2019-03-25",
                ),
            ),
            // Hotdog ingredients best-before date & score in 2019-03-13
            array(
                "score" => 0,
                "best_before" => array(
                    "2019-03-25",
                    "2019-03-25",
                    "2019-03-25",
                    "2019-03-25",
                ),
            ),
        );

        foreach ($checklist as $recipe)
        {
            $score = 0;
            foreach ($recipe["best_before"] as $bestBefore)
            {
                if ($recipeService->expiryDate($bestBefore, $today)) {
                    $score += $recipeService->checkScore($bestBefore, $today);
                }
            }

            $this->assertEquals($recipe["score"], $score);
        }
    }
}
