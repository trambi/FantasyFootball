<?php

namespace FantasyFootball\TournamentAdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/delete/{idMatch}');
    }

    public function testAdd()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add');
    }

    public function testResume()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/resume');
    }

    public function testModify()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modify');
    }

    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/view');
    }

}
