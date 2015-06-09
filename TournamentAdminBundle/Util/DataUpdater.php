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
	const deleteCoachQuery ='DELETE FROM tournament_coach WHERE id=?';
        const changeReadinessCoachQuery='UPDATE tournament_coach SET ready=? WHERE id=?';
        const insertMatchQuery ='INSERT INTO tournament_match (id_coach_1,id_coach_2,round,edition,table_number) VALUES(?,?,?,?,?)';
	const resumeMatchQuery ='UPDATE tournament_match SET td_1=?,td_2=?,sortie_1=?,sortie_2=?,points_1=?,points_2=?,status=\'resume\'	WHERE id_match=?';
	const deleteMatchQuery ='DELETE FROM tournament_match';
	const updateRankingQuery ='UPDATE INTO tournament_coach SET points=?,opponents_points=?,net_td=?,casualties=? WHERE id=?';
	const deletepreCoachQuery ='DELETE FROM tournament_precoach';
	const updateTeamQuery ='UPDATE INTO tournament_coach SET team_name=?,name=?,id_race=?,email=?,fan_factor=?,naf_number=?,edition=?,ready=? WHERE id=?';
        const insertCoachTeamQuery ='INSERT INTO tournament_coach_team (name) VALUES(?)';
        
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
    
    public function setReadyCoach($coach){
        $coachs = array($coach);
	$this->_changeReadinessCoachs($coachs,1);
    }
    
    public function setReadyCoachs($coachs){
	$this->_changeReadinessCoachs($coachs,1);
    }
    
    public function setUnreadyCoach($coach){
        $coachs = array($coach);
	$this->_changeReadinessCoachs($coachs,0);
    }
    
    public function setUnreadyCoachs($coachs){
	$this->_changeReadinessCoachs($coachs,0);
    }
    
    protected function _changeReadinessCoachs($coachs,$readiness){
	if ( NULL != $this->mySQL ){
            $stmt = $this->mySQL->prepare(self::changeReadinessCoachQuery);
            if ( $stmt ){
                foreach ($coachs as $coach){
                    $id = (property_exists($coach,'id')?intval($coach->id):0);
                    $stmt->bind_param('ii', $readiness,$id);
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
                $stmt = $this->mySQL->prepare(self::deleteCoachQuery);
        	if ( $stmt ){
                    foreach ($coachs as $coach){
                        $id = (property_exists($coach,'id')?intval($coach->id):0);
			$stmt->bind_param('i', $id);
			$result = $stmt->execute();
                    }
                    $stmt->close();
		}
        }
    }
    
    public function insertCoachTeams($coachTeams){
	if ( NULL != $this->mySQL ){
            if ($stmt = $this->mySQL->prepare(self::insertCoachTeamQuery)){
		foreach ($coachTeams as $coachTeam){
                    $name = $coachTeam->getName();
                    $stmt->bind_param('ssdsdddd', $name);
                    $stmt->execute();
		}
		$stmt->close();
            }
	}
    }
}
?>