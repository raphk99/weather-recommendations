<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RecommendationControllerTest extends WebTestCase
{
    public function testGetRecommendations1() {
        $client = static::createClient();
        $client->request('POST', '/api/recommendations', [], [], ['CONTENT_TYPE' => 'application/json'], '{"weather": {"city": "Marseilles","date": "tomorrow"}}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
    public function testGetRecommendations2() {
        $client = static::createClient();
        $client->request('POST', '/api/recommendations', [], [], ['CONTENT_TYPE' => 'application/json'], '{"weather": {"city": "Parisgo","date": "today"}}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
    public function testGetRecommendations3() {
        $client = static::createClient();
        $client->request('POST', '/api/recommendations', [], [], ['CONTENT_TYPE' => 'application/json'], '{"weather": {"city": "Paris"}}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
    public function testGetRecommendations4() {
        $client = static::createClient();
        $client->request('POST', '/api/recommendations', [], [], ['CONTENT_TYPE' => 'application/json'], '{"weather": {"city": "Parisgo"}}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}
?>