<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class LunchController extends ApiController
{
    /**
    * @Route("/lunch")
    */
    public function getLunch()
    {
        $recipes = $this->jsonParse("data/recipes.json");
        $ingredients = $this->jsonParse("data/ingredients.json");
        $today = "2019-03-13";

        return $this->jsonResponse($this->recipeList($recipes, $ingredients, $today));
    }

    private function recipeList($recipes, $ingredients, $today)
    {
        $recipeList = [];
        $sortedRecipes = $this->checkIngredients($recipes, $ingredients, $today);

        foreach ($sortedRecipes as $value) {
            array_push($recipeList, $recipes["recipes"][$value["index"]]);
        }
        return $recipeList;
    }

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
                            $score["score"] += (strtotime($today) - strtotime($item["best-before"])) / (60 * 60 *24);
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
        return $this->sortRecipe($recipeScore);
    }

    private function expiryDate($expiry, $today)
    {
        if (strtotime($expiry) >= strtotime($today)) {
            return false;
        }
        return true;
    }

    private function sortRecipe($recipeScore)
    {
        usort($recipeScore, function($a, $b) {
            return $a["score"] <=> $b["score"];
        });
        return $recipeScore;
    }

    private function jsonParse($f)
    {
        $f = file_get_contents($f);
        return json_decode($f, true);
    }
}
