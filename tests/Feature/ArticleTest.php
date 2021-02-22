<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ArticleTest extends TestCase
{   
    use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_articles()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_articles_controller_index_call()
    {   
        Cache::shouldReceive('get')
                    ->once()
                    ->with('spy_index_call');

        $response = $this->get('/api/articles');
    }

    public function test_articles_controller_show_call()
    {   
        Cache::shouldReceive('get')
                    ->once()
                    ->with('spy_show_call');

        $response = $this->get('/api/articles/12');
    }

    public function test_articles_controller_store_call()
    {   
        Cache::shouldReceive('get')
                    ->once()
                    ->with('spy_store_call');

        $response = $this->post('/api/articles', []);
    }

}
