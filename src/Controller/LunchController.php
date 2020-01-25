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
        $recipeLists = [];

        foreach ($recipes["recipes"] as $recipe) {
            if ($this->checkIngredients($recipe["ingredients"], $ingredients["ingredients"], $today)) {
                array_push($recipeLists, $recipe);
            }
        }
        return $this->jsonResponse($recipeLists);
    }

    private function checkIngredients($recipe, $ingredients, $dueDate)
    {
        $exists = 0;
        foreach ($recipe as $list)
        {
            foreach ($ingredients as $item)
            {
                if ($item["title"] == $list && !$this->useByExpired($item["use-by"], $dueDate)) {
                    $exists += 1;
                    break;
                }
            }
        }
        return $exists == count($recipe);
    }

    private function useByExpired($useByDate, $today)
    {
        if (strtotime($useByDate) >= strtotime($today)) {
            return false;
        }
        return true;
    }

    private function jsonParse($f)
    {
        $f = file_get_contents($f);
        return json_decode($f, true);
    }
}
