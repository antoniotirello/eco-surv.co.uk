<?php

namespace Tests\Feature;

use App\Services\BreedService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BreedServiceTest extends TestCase
{
    public function testCache() {
        Cache::store('redis')->flush();

        Cache::store('redis')->put('breedList', 'xxx', now()->addMinutes(1));

        $this->assertTrue(Cache::store('redis')->has('breedList'));

        $this->assertEquals('xxx', Cache::store('redis')->get('breedList'));
    }

    /**
     * Test failing. I need to study more how the Laravel cache works
     */
    public function testGetCachedBreeds()
    {
        Http::fake([
            'https://dog.ceo/api/breeds/list/all' => Http::response(
                file_get_contents('tests/Fixtures/FiveBreedList.json')
            )
        ]);

        Cache::store('redis')->flush();

        //var_dump(Cache::store('redis')->has('xxx'));

        $service = new BreedService();
        $service->getBreed();
        Http::assertSentCount(1);

        //var_dump(Cache::store('redis')->has('breedList'));
        //var_dump('Stored: '. Cache::store('redis')->get('breedList'));

        /**
         * The getBreed method should call the remote server the first time, store it in redis and load data from
         * the cache the second time but the second assert show that it was called twice
         */

        $breedList = $service->getBreed();
        Http::assertSentCount(1);

        $this->assertIsArray($breedList);

        $this->assertCount(5, $breedList);

        $africanFound = false;
        $bulldogFound = false;

        foreach ($breedList as $breed) {
            $this->assertTrue(property_exists($breed, 'name'));
            $this->assertTrue(property_exists($breed, 'sub'));

            $this->assertIsArray($breed->sub);

            if ($breed->name === 'african') {
                $africanFound = true;

                $this->assertCount(0, $breed->sub);
            }

            if ($breed->name === 'bulldog') {
                $bulldogFound = true;

                $this->assertContains('boston', $breed->sub);
                $this->assertContains('french', $breed->sub);
                $this->assertContains('english', $breed->sub);

                $this->assertCount(3, $breed->sub);
            }
        }

        $this->assertTrue($africanFound);
        $this->assertTrue($bulldogFound);

    }
}
