<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoachTeamController extends Controller
{
    public function AddAction()
    {
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Add.html.twig', array(
                // ...
            ));    }

    public function ModifyAction()
    {
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Modify.html.twig', array(
                // ...
            ));    }

    public function DeleteAction()
    {
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Delete.html.twig', array(
                // ...
            ));    }

    public function LoadAction()
    {
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Load.html.twig', array(
                // ...
            ));    }

}
