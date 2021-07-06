<?php

namespace Tests\Feature\Articles;

use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_articles()
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Título',
                    'slug' => 'nuevo-titulo',
                    'content' => 'Nuevo contenido del articulo'
                ]
            ]
        ]);

        $response->assertCreated();

        $article = Article::first();

        $this->withHeaders([
            'Location',
            route('api.v1.articles.show', $article)
        ]);
        
        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);
    }
}
