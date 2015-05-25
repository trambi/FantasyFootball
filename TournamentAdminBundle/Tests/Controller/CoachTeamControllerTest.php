<?php

namespace FantasyFootball\TournamentAdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoachTeamControllerTest extends WebTestCase
{
    public function testAdd()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Add');
    }

    public function testModify()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Modify');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Delete');
    }

    public function testLoad()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Load');
    }

}
