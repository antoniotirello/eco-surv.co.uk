<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Safe\Exceptions\JsonException;


class BreedService
{
    private string $breedCacheKey = 'breedList';

    /**
     * @param $returnAlsoSubBreed
     * @return array
     */
    public function getBreed($returnAlsoSubBreed = false) {
        $jsonData = $this->getCachedBreeds();

        //var_dump('jsonData length: ', strlen($jsonData));

        if ($jsonData === null) {
            $jsonData = $this->getRemoteBreed();
            //var_dump('jsonData length: ', strlen($jsonData));
            $this->storeBreedCache($jsonData, $returnAlsoSubBreed);
        }

        //var_dump(Cache::store('redis')->has($this->breedCacheKey));

        //var_dump(Cache::store('redis')->get($this->breedCacheKey));

        return $this->parseBreedJson($jsonData, $returnAlsoSubBreed);
    }

    public function getBreedById($breedId, $returnAlsoSubBreed = false) {
        $list = $this->getBreed($returnAlsoSubBreed);

        foreach ($list as $breed) {
            if (strtolower($breed->name) == trim(strtolower($breedId))) {
                return $breed;
            }
        }

        return null;
    }

    public function getBreedByRand($returnAlsoSubBreed = false) {
        $list = $this->getBreed($returnAlsoSubBreed);

        if (is_array($list)) {
            $total = count($list);
            $index = rand(0, $total-1);

            return $list[$index];
        }
    }

    public function getBreedImageById($breedId) {
        return $this->loadRemoteImage($breedId);
    }

    private function loadRemoteImage($breedId) {
        if (trim($breedId)!='') {
            $imgResponse = \Safe\json_decode(
                Http::get('https://dog.ceo/api/breed/' . $breedId . '/images/random')->body(),
                true
            );
            if (array_key_exists('message', $imgResponse)) {
                $imgPath = $imgResponse['message'];

                return Http::get($imgPath)->body();
            }
        } else {
            return null;
        }
    }

    private function parseBreedJson($jsonData, $returnAlsoSubBreed = false) {
        $output = [];

        try {
            $parsedData = \Safe\json_decode($jsonData, true);
            if (array_key_exists('message', $parsedData)) {
                foreach ($parsedData['message'] as $breedName => $breedSubs) {
                    $breed = new \stdClass();
                    $breed->name = $breedName;
                    if ($returnAlsoSubBreed) {
                        $breed->sub = [];

                        foreach ($breedSubs as $subBreedName) {
                            $breed->sub[] = $subBreedName;
                        }
                    }

                    $output[] = $breed;
                }
            }

        } catch (JsonException $e) {
        }

        return $output;
    }

    /**
     * @return string | null
     */
    private function getCachedBreeds(): ?string
    {
        try {
            //var_dump('Read: ', Cache::store('redis')->get($this->breedCacheKey));
            return Cache::store('redis')->get($this->breedCacheKey);
        } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * @param $breedJson
     * @param int | null $cacheDurationInMinutes
     * @return void
     */
    private function storeBreedCache($breedJson, ?int $cacheDurationInMinutes = 30): void
    {
        //var_dump('storing', $breedJson);
        Cache::store('redis')->put($this->breedCacheKey, $breedJson, now()->addMinutes($cacheDurationInMinutes));
    }

    /**
     * @return string
     */
    private function getRemoteBreed(): string
    {
        return Http::get('https://dog.ceo/api/breeds/list/all')->body();
    }
}
