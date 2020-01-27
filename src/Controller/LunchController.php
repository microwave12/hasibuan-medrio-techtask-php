<?php

namespace App\Controller;

use App\Application\Service\RecipeService;
use Symfony\Component\Routing\Annotation\Route;

class LunchController extends ApiController
{
    /**
     * @var RecipeService $recipeService
     */
    private $recipeService;

    /**
     * LunchController constructor
     */
    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    /**
     * API lunch recipe for today, using hard coded date instead of date("Y-m-d")
     * 
     * @Route("/lunch")
     * 
     * @return string JSON recipe lists
     */
    public function getLunch()
    {
        $today = "2019-03-07";

        return $this->jsonResponse($this->recipeService->recipeList($today));
    }
}
