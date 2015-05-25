<?php
namespace FantasyFootball\TournamentAdminBundle\Util;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\DatabaseConfiguration;

class DataUpdater extends DataProvider
{
	public function __construct($DbConf){
		parent::__construct($DbConf);
	}
	
	const insertCoachQuery ='INSERT INTO tournament_coach (team_name,name,id_race,email,naf_number,edition,ready,id_coach_team) VALUES(?,?,?,?,?,?,?,?)';
	const updateCoachQuery ='UPDATE tournament_coach SET team_name=?,name=?,id_race=?,email=?,naf_number=?,edition=?,ready=? WHERE id=?';
	const deleteCoachQuery ='DELETE tournament_coach WHERE id=?';
	
	public function insertCoach($team){
		$teams = array($team);
		$this->insertCoachs($teams);
	}
	
	public function insertCoachs($coachs){
			if ( NULL != $this->mySQL ){
				if ($stmt = $this->mySQL->prepare(self::insertCoachQuery)){
					foreach ($coachs as $coach){
						$teamName = $coach->getTeamName();
						$coachName = $coach->getName();
						$race = $coach->getRace();
						$email = $coach->getEmailAddress();
						$naf = $coach->getNafNumber() ;
						$edition = $coach->getEdition();
						$ready = $coach->getReady();
						$coachTeam = $coach->getCoachTeam();

						$stmt->bind_param('ssdsdddd', $teamName,$coachName,$race,$email,$naf,$edition,$ready,$coachTeam);
						$stmt->execute();
					}
					print_r($stmt);
					$stmt->close();
				}
			}
		}
	
      public function modifyCoach($coach){
		  $coachs = array($coach);
		  $this->modifyCoachs($coachs);
		}
    
		public function modifyCoachs($coachs){
			if ( NULL != $this->mySQL ){
				if ($stmt = $this->mySQL->prepare(self::updateCoachQuery)){
					foreach ($coachs as $coach){
						$teamName = $coach->getTeamName();
						$coachName = $coach->getName();
						$race = $coach->getRace();
						$email = $coach->getEmailAddress();
						$naf = $coach->getNafNumber() ;
						$edition = $coach->getEdition();
						$ready = $coach->getReady();
						$id = (property_exists($coach,'id')?intval($coach->id):0);
						$stmt->bind_param('ssisiiii', $teamName,$coachName,$race,$email,$naf,$edition,$ready,$id);
						$result = $stmt->execute();
					}
					$stmt->close();
				}
			}
		}
		public function deleteCoach($coach){
		  $coachs = array($coach);
		  $this->deleteCoachs($coachs);
		}
    
		public function deleteCoachs($coachs){
			if ( NULL != $this->mySQL ){
				if ($stmt = $this->mySQL->prepare(self::updateCoachQuery)){
					foreach ($coachs as $coach){
						$id = (property_exists($coach,'id')?intval($coach->id):0);
						$stmt->bind_param('i', $id);
						$result = $stmt->execute();
					}
					$stmt->close();
				}
			}
		}
}
?>