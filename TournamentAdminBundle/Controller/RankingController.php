<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

class RankingController extends Controller
{
    public function coachTeamAction($edition)
    {
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);
        $editionObj = $data->getEditionById($edition);
        $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy);
        $ranking = $data->getCoachTeamRanking($edition, $strategy);
        //print_r($ranking);
        return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach_team.html.twig', 
                array('edition' => $edition,
                    'ranking' => $ranking));
        
    }
  
    public function coachAction($edition) {
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);
        $editionObj = $data->getEditionById($edition);
        $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy);
        $ranking = $data->getMainCoachRanking($editionObj, $strategy);
        return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach.html.twig', 
                array('edition' => $edition,
                    'ranking' => $ranking));
    }

    public function coachByTouchdownAction($edition)
    {
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);
        $editionObj = $data->getEditionById($edition);
        $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy);
        $tdRanking = $data->getCoachRankingByTouchdown($editionObj, $strategy);
        return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach_touchdown.html.twig', 
                array('edition' => $edition,
                    'ranking' => $tdRanking));
        
        return $response;
    }

    public function coachByCasualtiesAction($edition)
    {
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);
        $editionObj = $data->getEditionById($edition);
        $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy);
        $casRanking = $data->getCoachRankingByCasualties($editionObj, $strategy);
        return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach_casualties.html.twig', 
                array('edition' => $edition,
                    'ranking' => $casRanking));
    }

    public function coachByComebackAction($edition)
    {
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);
        $editionObj = $data->getEditionById($edition);
        $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy);
        $comebackRanking = $data->getCoachRankingByComeback($editionObj, $strategy);
        return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach_comeback.html.twig', 
                array('edition' => $edition,
                    'ranking' => $comebackRanking));
    }
    
    public function allAction($edition)
    {
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataProvider($conf);
        $editionObj = $data->getEditionById($edition);
        $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy);
        $rankings = $data->getAllRanking($editionObj, $strategy);
        return $this->render('FantasyFootballTournamentAdminBundle:Ranking:all.html.twig',
            array('edition' => $edition,
                'ranking' => $rankings['ranking'],
                'casRanking' => $rankings['casRanking'],
                'tdRanking' => $rankings['tdRanking'],
                'comebackRanking' => $rankings['comebackRanking'],
                'coachTeamRanking' => $rankings['coachTeamRanking']
                ));
    }

}

?>