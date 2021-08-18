<?php

namespace App\Tests\Controller\API;

use App\Entity\AnnonceAutomobile;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AnnonceControllerTest extends WebTestCase
{
    use FixturesTrait;

    public static $first_annonce = [];

    public function getFixtures()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\UserFixtures',
            'App\DataFixtures\CategorieFixtures',
            'App\DataFixtures\AnnonceFixtures'
        ));
    }

    public function getHeaders() : array
    {
        return [
            'HTTP_X-AUTH-TOKEN' => 'test1@test.test',
            'HTTP_CONTENT_TYPE' => 'application/json',
        ];
    }

    public function testGetAnnonceListe(): void
    {
        $client = static::createClient();
        $this->getFixtures();
        $client->request('GET', 'http://localhost:8000/api/annonce',[],[],$this->getHeaders());
        $content = json_decode($client->getResponse()->getContent(),true);
        self::$first_annonce = $content[0];
        $this->assertResponseIsSuccessful();
        $this->isJson();
        $this->assertCount(1000,$content);
    }

    public function testGetAnnonceById(): void
    {
        $client = static::createClient();
        $client->request('GET', 'http://localhost:8000/api/annonce/'.self::$first_annonce['id']??null,[],[],$this->getHeaders());
        $content = json_decode($client->getResponse()->getContent(),true);
        $this->assertResponseIsSuccessful();
        $this->isJson();
        $this->assertEqualsCanonicalizing($content,self::$first_annonce);
    }

    public function testUpdateAnnonce(): void
    {
        $client = static::createClient();
        $annonce = self::$first_annonce;
        $annonce['contenu']="nouveau contenu";
        unset($annonce['id']); unset($annonce['user']);unset($annonce['cat']);
        $client->request('PUT', 'http://localhost:8000/api/annonce/'.self::$first_annonce['id']??null,[],[],$this->getHeaders(),json_encode($annonce));
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $client->request('GET', 'http://localhost:8000/api/annonce/'.self::$first_annonce['id']??null,[],[],$this->getHeaders());
        $content = json_decode($client->getResponse()->getContent(),true);
        $this->assertNotEqualsCanonicalizing($content,self::$first_annonce);
        $this->assertEquals("nouveau contenu",$content['contenu']);
    }

    public function testAddAnnonce(): void
    {
        $client = static::createClient();
        $annonce = self::$first_annonce;
        $annonce['contenu']="nouvelle annonce";
        unset($annonce['id']); unset($annonce['user']);unset($annonce['cat']);
        $client->request('POST', 'http://localhost:8000/api/annonce',[],[],$this->getHeaders(),json_encode($annonce));
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $content = json_decode($client->getResponse()->getContent(),true);
        $new_id = $content['id']??null;
        $client->request('GET', 'http://localhost:8000/api/annonce/'.$new_id,[],[],$this->getHeaders());
        $content = json_decode($client->getResponse()->getContent(),true);
        $this->assertResponseIsSuccessful();
        $this->isJson();
        $this->assertEquals("nouvelle annonce",$content['contenu']);
    }

    public function testDeleteAnnonce(): void
    {
        $client = static::createClient();
        $client->request('DELETE', 'http://localhost:8000/api/annonce/'.self::$first_annonce['id']??null,[],[],$this->getHeaders());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $client->request('GET', 'http://localhost:8000/api/annonce/'.self::$first_annonce['id']??null,[],[],$this->getHeaders());
        $this->isJson();
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAnnonceAutomobileSearch(): void
    {
        $client = static::createClient();
        $client->request('GET', 'http://localhost:8000/api/annonce/search/rs4?limit=5',[],[],$this->getHeaders());
        $this->assertResponseIsSuccessful();
        $this->isJson();
        $content = json_decode($client->getResponse()->getContent(),true);
        $first_reponse = $content[0];
        $this->assertCount(5,$content);
        $this->assertArrayHasKey('match',$first_reponse);
        $this->assertEquals(AnnonceAutomobile::AUDI,$first_reponse['marque']);
    }
}
