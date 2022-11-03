<?php

namespace App\Http\Controllers;

use App\Services\BreedService;

class Breed extends Controller
{
    public function listAllBreed(BreedService $breedService): bool|string
    {
        return json_encode($breedService->getBreed(false));
    }

    public function breedById($id, BreedService $breedService): bool|string|null
    {
        $breed = $breedService->getBreedById($id, false);
        if ($breed !== null) {
            return json_encode($breed);
        }

        return null;
    }

    public function randomBreed(BreedService $breedService) {
        return json_encode($breedService->getBreedByRand(false));
    }

    public function breedByIdWithImage($id, BreedService $breedService) {
        return response($breedService->getBreedImageById($id), 200)
            ->header('Content-Type', 'image/jpeg');
    }
}
