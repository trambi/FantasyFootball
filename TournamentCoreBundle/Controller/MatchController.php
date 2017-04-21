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

class MatchController extends Controller
{
	public function getPlayedMatchListAction($edition,$round)
	{
		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$matchs = $data->getPlayedMatchsByEditionAndRound($edition,$round);
		$response = new JsonResponse($matchs);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
 	}
	public function getToPlayMatchListAction($edition,$round)
	{
		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$matchs = $data->getToPlayMatchsByEditionAndRound($edition,$round);
		$response = new JsonResponse($matchs);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
	}
	public function getMatchListAction($edition,$round)
	{
		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$matchs = $data->getMatchsByEditionAndRound($edition,$round);
		$response = new JsonResponse($matchs);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
	}
	public function getMatchListByCoachAction($coachId)
	{
		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$matchs = $data->getMatchsByCoach($coachId);
		$response = new JsonResponse($matchs);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
	}
	public function getMatchListByCoachTeamAction($coachTeamId)
	{
		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$matchs = $data->getMatchsByCoachTeam($coachTeamId);
		$response = new JsonResponse($matchs);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
	}
}

?>