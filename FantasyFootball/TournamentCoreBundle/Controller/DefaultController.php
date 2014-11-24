<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FantasyFootballTournamentCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
