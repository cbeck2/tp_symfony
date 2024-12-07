<?php

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryTest extends ApiTestCase
{
    public function testCreateCategories(){
        $response = static::createClient()->request('POST', '/api/categories', [
            'json' => [
                'name' => 'Voiture'
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@type' => 'Category',
            'name' => 'Voiture'
        ]);
        $this->assertMatchesRegularExpression('~^/api/categories/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Category::class);
    }

    public function testGetAllCategories()
    {
        $response = static::createClient()->request('GET', '/api/categories');
       
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testUpdateCategories(){
        $response = static::createClient()->request('PUT', '/api/categories/1', ['json' =>[
            'name' => 'moto',
        ]]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Category',
            '@id' => '/api/categories/1',
            '@type' => 'Category',
        ]);
    }    

    /*public function testDeleteCategories(){
        $response = static::createClient()->request('DELETE', 'api/categories/1');
        $this->assertResponseStatusCodeSame(204); 
    }*/
}

class PictureTest extends ApiTestCase
{
    public function testCreatePicture(){

        $file = new UploadedFile(
            'tests/TEST.png',
            'TEST.png',
            'image/png',
        );

        $response = static::createClient()->request(
            'POST',
            '/api/pictures',
            [
                'headers' => ['Content-Type' => 'multipart/form-data'],
                'extra' => [
                            'files' => [
                                        'file' => $file,
                                        ],
                            ],
            ]

        );
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Picture',
            //'@id' => '/api/pictures/1',
            '@type' => 'https://schema.org/MediaObject',
            //'contentUrl' => 'tests/'
        ]);

        $this->assertMatchesRegularExpression('~^/api/pictures/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Picture::class);
    }

    public function testGetAllPicture(){

        $response = static::createClient()->request('GET', '/api/pictures');
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetByIdPicture()
    {
        $response = static::createClient()->request('GET', '/api/pictures/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

}


class AdvertTest extends ApiTestCase
{
    public function testCreateAdvert(){
        $response = static::createClient()->request('POST', '/api/adverts', ['json' => [
            'title' => 'Wankil',
            'content' => 'Trop bien la chaine Youteub',
            'author' => ' Jean-Michel',
            'email' => 'jean@gmail.com',
            'category' => '/api/categories/1',
            'state' => 'draft',
            'createdAt' => '2022-12-11T09:17:00.047Z',
            'publishedAt' => '2022-12-10T09:17:59.047Z',
            'pictures' => [
                
            ],
            'price' => 0
         ]]);
        
         $this->assertResponseStatusCodeSame(201);
         $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Advert',
            //'@id' => 'api/adverts/1',
            '@type' => 'Advert',
            'title' => 'Wankil',
            'content' => 'Trop bien la chaine Youteub',
            'author' => ' Jean-Michel',
            'email' => 'jean@gmail.com',
            'category' => '/api/categories/1',
            'state' => 'draft',
            'createdAt' => '2022-12-11T09:17:00+00:00',
            'publishedAt' => '2022-12-10T09:17:59+00:00',
            'pictures' => [

            ],
            'price' => 0
        ]);
        $this->assertMatchesRegularExpression('~^/api/adverts/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Advert::class);
    }

    public function testGetAllAdvert() : void{
        $response = static::createClient()->request('GET', '/api/adverts');
       
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetByIdAdvert()
    {
        $response = static::createClient()->request('GET', '/api/adverts/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
    
}
