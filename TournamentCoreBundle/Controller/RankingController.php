<?php
/*
    FantasyFootball Symfony2 bundles - Symfony2 bundles collection to handle fantasy football tournament
    Copyright (C) 2017  Bertrand Madet

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

class RankingController extends Controller
{
  public function getCoachTeamRankingAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $ranking = $data->getCoachTeamRanking($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachTeamRankingByTouchdownAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $ranking = $data->getCoachTeamRankingByTouchdown($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachTeamRankingByCasualtiesAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $ranking = $data->getCoachTeamRankingByCasualties($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachTeamRankingByComebackAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $ranking = $data->getCoachTeamRankingByComeback($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachTeamRankingByCompletionsAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $ranking = $data->getCoachTeamRankingByCompletions($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachTeamRankingByFoulsAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $ranking = $data->getCoachTeamRankingByFouls($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachTeamRankingByDefenseAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $ranking = $data->getCoachTeamRankingByDefense($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachRankingAction($edition) {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $ranking = $data->getMainCoachRanking($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    return $response;
  }

  public function getCoachRankingByTouchdownAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $tdRanking = $data->getCoachRankingByTouchdown($editionObj);
    $response = new JsonResponse($tdRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachRankingByCasualtiesAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $casRanking = $data->getCoachRankingByCasualties($editionObj);
    $response = new JsonResponse($casRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachRankingByComebackAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $comebackRanking = $data->getCoachRankingByComeback($editionObj);
    $response = new JsonResponse($comebackRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachRankingByCompletionsAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $completionsRanking = $data->getCoachRankingByCompletions($editionObj);
    $response = new JsonResponse($completionsRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachRankingByFoulsAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $completionsRanking = $data->getCoachRankingByFouls($editionObj);
    $response = new JsonResponse($completionsRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

  public function getCoachRankingByDefenseAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $ranking = $data->getCoachRankingByDefense($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }

}

?>
