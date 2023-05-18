<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\DataSources\UserDataSource;
use App\Domain\User;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

use function Symfony\Component\String\s;

class PostSellCoinControllerTest extends TestCase
{
    private CoinDataSource $coinDataSource;
    protected function setUp(): void
    {
        parent::setUp();
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->app->bind(CoinDataSource::class, function () {
            return $this->coinDataSource;
        });
    }

    /**
     * @test
     */
    public function ifBadRequestThrowsError()
    {
        $response = $this->post('api/coin/sell', ["coin_id" => null,
                                                    "wallet_id" => null,
                                                    "amount_usd" => null]);

        $response->assertBadRequest();
        $response->assertExactJson(['description' => 'bad request error']);
    }

    /**
     * @test
     */
    public function ifCoinIdNotFoundThrowsError()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value")
            ->andReturn(null);

        $response = $this->post('api/coin/sell', ["coin_id" => "coin_id_value",
            "wallet_id" => "wallet_id_value",
            "amount_usd" => 1]);

        $response->assertNotFound();
        $response->assertExactJson(['description' => 'A coin with the specified ID was not found']);
    }


    /**
     * @test
     */
    /*public function ifCoinWasSoldSuccessfully()
    {
        $this->coinDataSource
            ->expects("findById")
            ->with("coin_id_value")
            ->andReturn(null);
    }*/
}