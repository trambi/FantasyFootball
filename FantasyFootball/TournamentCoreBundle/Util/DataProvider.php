<?php
namespace FantasyFootball\TournamentCoreBundle\Util;
	class DataProvider
	{
		protected $mySQL;
		public function __construct(){
			if (FALSE == class_exists('mysqli') ){
				$this->link = mysql_connect('localhost', 'tournament', 'tournament');
				mysql_select_db('tournament',$this->link);
				//echo "print_r($this->link)";
				$this->mySQL = NULL;
			}else{
				$this->mySQL = new \mysqli('localhost', 'tournament', 'tournament', 'tournament');
			}
		} 
		
		public function __destruct(){
			if ( NULL != $this->mySQL ){
				$this->mySQL->close();
			}else{
				mysql_close($this->link);

			}
		}
		
		protected function query($query){
			if ( NULL != $this->mySQL ){
				return $this->mySQL->query($query);
			}else{
				//echo "<h6>query : $query</h6>";
				//echo "print_r($this->link)";
				return mysql_query($query,$this->link);
			}
		}
		
		protected function resultFetchObject($result){
			if ( NULL != $this->mySQL ){
				return $result->fetch_object();
			}else{
				return mysql_fetch_object($result);
			}
		}
		
		protected function resultFetchRow($result){
			if ( NULL != $this->mySQL ){
				return $result->fetch_row();
			}else{
				return mysql_fetch_row($result);
			}
		}
		
		protected function resultClose($result){
			if ( NULL != $this->mySQL ){
				return $result->close();
			}else{
				return mysql_free_result($result);
			}
		}
		
		const raceQuery='SELECT r.edition,r.id_race as id,r.nom_fr,r.nom_en,r.nom_en_2,r.nom_fr_2,r.reroll FROM tournament_race r';
		const coachQuery='SELECT c.id, c.team_name, c.name, c.id_race,c.email,c.fan_factor,c.reroll,c.apothecary,c.assistant_coach,c.cheerleader,c.points,c.opponents_points, c.net_td, c.casualties,c.edition,c.naf_number, c.id_coach_team,r.nom_fr,ct.name,c.ready FROM tournament_coach c INNER JOIN tournament_race r ON c.id_race=r.id_race LEFT JOIN tournament_coach_team ct ON c.id_coach_team=ct.id';
		const setReadyCoachQuery='UPDATE tournament_coach SET ready = 1';
		const preCoachQuery ='SELECT p.id as id, p.name as coach, p.team_name as name,p.id_race as raceId,p.email as email,p.edition as edition,p.naf_number,p.id_coach_team,r.nom_fr,ct.name FROM tournament_precoach p INNER JOIN tournament_race r ON p.id_race=r.id_race LEFT JOIN tournament_coach_team tc ON p.id_coach_team=tc.id';
		const coachTeamQuery='SELECT c.name,c.id_coach_team,ct.name,c.id,c.team_name,c.points,c.opponents_points,c.net_td,c.casualties FROM tournament_coach c INNER JOIN tournament_coach_team ct ON c.id_coach_team=ct.id';
		const coachTeamPreCoachQuery='SELECT p.name,p.id_coach_team,ct.name,p.id,\'\',0,0,0,0 FROM tournament_precoach p INNER JOIN tournament_coach_team ct ON p.id_coach_team=ct.id';
		const matchQuery ='SELECT c1.name,c1.team_name,c1.id,m.td_1,m.casualties_1,m.points_1, c2.name,c2.team_name,c2.id,m.td_2,m.casualties_2,m.points_2,m.id,m.table_number,m.status,m.edition,m.round,m.finale FROM tournament_match m INNER JOIN tournament_coach c1 ON m.id_coach_1 = c1.id INNER JOIN tournament_coach c2 ON m.id_coach_2 = c2.id';
	   const insertTeamQuery ='INSERT INTO tournament_coach (team_name,name,id_race,email,fan_factor,naf_number,edition,ready,id_coach_team) VALUES(?,?,?,?,?,?,?,?,?)';
		const insertMatchQuery ='INSERT INTO tournament_match (id_coach_1,id_coach_2,round,edition,table_number) VALUES(?,?,?,?,?)';
		const resumeMatchQuery ='UPDATE tournament_match SET td_1=?,td_2=?,sortie_1=?,sortie_2=?,points_1=?,points_2=?,status=\'resume\'	WHERE id_match=?';
		const deleteMatchQuery ='DELETE FROM tournament_match';
		const updateRankingQuery ='UPDATE INTO tournament_coach SET points=?,opponents_points=?,net_td=?,casualties=? WHERE id=?';
		const editionQuery='SELECT id,day_1 as day1, day_2 as day2, round_number as roundNumber, current_round as currentRound, use_finale as useFinale, ranking_strategy as rankingStrategy,pairing_strategy as pairingStrategy FROM tournament_edition';
		const deletepreCoachQuery ='DELETE FROM tournament_precoach';
		const updateTeamQuery ='UPDATE INTO tournament_coach SET team_name=?,name=?,id_race=?,email=?,fan_factor=?,naf_number=?,edition=?,ready=? WHERE id=?';
	
		public function getRacesByEdition($edition){
			$query = self::raceQuery;
			$query .= ' WHERE r.edition < '.intval($edition);
			$query .= ' ORDER BY r.nom_fr ASC';
			$result = $this->query($query);
			$races = array();
			if ($result) 
			{
				$race = $this->resultFetchObject($result);
				while(null != $race)
				{
					$races[$race->id] = $race;
					$race = $this->resultFetchObject($result);
				}
				$this->resultClose($result);
			}
			return $races;
		}
				
		protected function convertRowInCoach($row){
			$coach = (object) array();
			$coach->id = intval($row[0]);
			$coach->name = mb_convert_encoding($row[1],'UTF-8');
			$coach->coach = mb_convert_encoding($row[2],'UTF-8');
			$coach->raceId = intval($row[3]);
			$coach->email = $row[4];
			$coach->ff = intval($row[5]);
			$coach->reroll = intval($row[6]);
			$coach->apothecary = intval($row[7]);
			$coach->assistants = intval($row[8]);
			$coach->cheerleaders = intval($row[9]);
			$coach->points = intval($row[10]);
			$coach->opponentsPoints= intval($row[11]);
			$coach->netTd = intval($row[12]);
			$coach->casualties = intval($row[13]);
			$coach->edition = intval($row[14]);
			$coach->nafNumber = intval($row[15]);
			$coach->coachTeamId = intval($row[16]);
			$coach->raceName = mb_convert_encoding($row[17],'UTF-8');
			$coach->coachTeamName = mb_convert_encoding($row[18],'UTF-8');
			$coach->ready = intval($row[19]);
			return $coach;
		}
		
		public function getPreCoachsByEdition($edition){
			$query = self::preCoachQuery;
			$query .= ' WHERE p.edition = '.intval($edition);
			$query .= ' ORDER BY p.name ASC';
			//echo 'query : [',$query,']<br />';
			$result = $this->query($query);
			$preteams = array();
			
			if ($result) 
			{
				$preteam = $this->resultFetchObject($result);
				while(null != $preteam){
					//print_r($preteam);
					$preteams[$preteam->id] = $preteam;
					$preteam =$this->resultFetchObject($result);
				}
				$this->resultClose($result);
			}
			return $preteams;
		}
		
		public function getCoachsByEdition($edition,$resetRankingStuff=false){
			$query = self::coachQuery;
			$query .= ' WHERE c.edition = '.intval($edition);
			$query .= ' ORDER BY c.name ASC';
			//echo 'request : [',$query,']<br />';
			$result = $this->query($query);
			$coachs = array();
			
			if ($result) 
			{
				$row = $this->resultFetchRow($result);
				while(null != $row){
					$coach = $this->convertRowInCoach($row);
					if(true == $resetRankingStuff){
						$coach->points = 0;
						$coach->opponentsPoints = 0;
						$coach->netTd = 0;
						$coach->casualties = 0;
						$coach->special = 0;
					}
					$coachs[$coach->id] = $coach;
					$row = $this->resultFetchRow($result);
				}
				$this->resultClose($result);
			}
			return $coachs;
		}

	public function getCoachById($id){
			$query = self::coachQuery;
			$query .= ' WHERE c.id = '.intval($id);
			//echo 'request : [',$query,']<br />';
			$result = $this->query($query);
			
			if ($result) 
			{
				$row = $this->resultFetchRow($result);
				while(null != $row){
					$coach = $this->convertRowInCoach($row);
					$row = $this->resultFetchRow($result);
				}
				$this->resultClose($result);
			}
			return $coach;
		}
		
		public function setReadyCoachById($id){
			$clause = ' WHERE c.id = '.intval($id);
			return $this->setReadyCoach($clause);
		}

		protected function setReadyCoach($clause){
			$query = self::setReadyCoachQuery;
			$query .= $clause;
			//echo 'request : [',$query,']<br />';
			return $this->query($query);
		}
		
		protected function convertRowInCoachTeamElement($row){
			$coachTeamElt = (object) array();
			$coachTeamElt->coach = $row[0];
			$coachTeamElt->coachTeamId = $row[1];
			$coachTeamElt->coachTeamName = $row[2];
			$coachTeamElt->teamId = $row[3];
			$coachTeamElt->teamName = $row[4];
			$coachTeamElt->points = $row[5];
			$coachTeamElt->opponentsPoints = $row[6];
			$coachTeamElt->netTd = $row[7];
			$coachTeamElt->casualties = $row[8];
			$coachTeamElt->isPrebooking = 0;
			return $coachTeamElt;
		}
		
		public function getCoachTeamById($coachTeamId){
			$clause = ' ct.id = '.intval($coachTeamId);
			$coachTeams = $this->getCoachTeamsByClause($clause);
			$coachTeam = array_shift($coachTeams);
			return $coachTeam;
		}		
		
		public function getCoachTeamsByEdition($edition){
			$clause = ' c.edition = '.intval($edition);
			$coachTeams = $this->getCoachTeamsByClause($clause);
			return $coachTeams;
		}
		
		protected function getCoachTeamsByClause($clause){
			$query = self::coachTeamQuery;
			$query .= ' WHERE '.$clause;
			$query .= ' ORDER BY c.id_coach_team ASC';
			//echo 'request : [',$query,']<br />';
			$result = $this->query($query);
			$coachTeams = array();
			$currentCoachTeam = (object) array();
			$currentCoachTeam->id = 0;
			if ($result)
			{
				$row = $this->resultFetchRow($result);
				$id = 0;
				while(null != $row){
					$coachTeamElt = $this->convertRowInCoachTeamElement($row);
					if( $currentCoachTeam->id != $coachTeamElt->coachTeamId ){
						if(0 != $currentCoachTeam-> id){
							$coachTeams[$currentCoachTeam->id] = $currentCoachTeam;
						}
						$id = $coachTeamElt->coachTeamId;
					
						$currentCoachTeam = (object) array();
						$currentCoachTeam->name = $coachTeamElt->coachTeamName;
						$currentCoachTeam->id = $id;
						$currentCoachTeam->coachTeamMates = array();
						$currentCoachTeam->points = 0;
						$currentCoachTeam->opponentsPoints = 0;
						$currentCoachTeam->netTd = 0;
						$currentCoachTeam->casualties = 0;
					}
					$currentCoachTeam->coachTeamMates[] = $coachTeamElt;
					$currentCoachTeam->points += $coachTeamElt->points;
					$currentCoachTeam->opponentsPoints += $coachTeamElt->opponentsPoints;
					$currentCoachTeam->netTd += $coachTeamElt->netTd;
					$currentCoachTeam->casualties += $coachTeamElt->casualties;
					$row = $this->resultFetchRow($result);
				}
				$this->resultClose($result);
				if( 0 != $currentCoachTeam->id ){
					$coachTeams[$currentCoachTeam->id] = $currentCoachTeam;
				}
			}
			$query = self::coachTeamPreCoachQuery;
			$query .= ' WHERE '.$clause;
			$query .= ' ORDER BY p.id_coach_team ASC';
			//echo 'request : [',$query,']<br />';
			$result = $this->query($query);
			if ( $result )
			{
				$row = $this->resultFetchRow($result);
				$id = 0;
				$currentCoachTeam = (object) array();
				$currentCoachTeam->id = 0;
				while( null != $row ){
					$coachTeamElt = $this->convertRowInCoachTeamElement($row);
					$coachTeamElt->isPrebooking = 1;
					if( $id != $coachTeamElt->coachTeamId ){
						if(0 != $id){
							$coachTeams[$id] = $currentCoachTeam;
						}
						$id = $coachTeamElt->coachTeamId;
						if(true == isset($coachTeams[$id]) ){
							$currentCoachTeam = $coachTeams[$id];
						}else{
							$currentCoachTeam = (object) array();
							$currentCoachTeam->name = $coachTeamElt->coachTeamName;
							$currentCoachTeam->id = $id;
							$currentCoachTeam->coachTeamMates = array();
							$currentCoachTeam->points = 0;
							$currentCoachTeam->opponentsPoints = 0;
							$currentCoachTeam->netTd = 0;
							$currentCoachTeam->casualties = 0;
						}
					}
					$currentCoachTeam->coachTeamMates[] = $coachTeamElt;
					$row = $this->resultFetchRow($result);
				}
				$this->resultClose($result);
				if(0 != $id){
					$coachTeams[$id] = $currentCoachTeam;
				}
			}
			return $coachTeams;
		}
		
		protected function convertRowInMatch($row){
			$match = (object) array();
			$match->coach1 = mb_convert_encoding($row[0], "UTF-8");
			$match->teamName1 = mb_convert_encoding($row[1], "UTF-8");
			$match->teamId1 = intval($row[2]);
			$match->td1 = intval($row[3]);
			$match->casualties1 = intval($row[4]);
			$match->points1 = intval($row[5]);
			$match->coach2 = mb_convert_encoding($row[6], "UTF-8");
			$match->teamName2 = mb_convert_encoding($row[7], "UTF-8");
			$match->teamId2 = intval($row[8]);
			$match->td2 = intval($row[9]);
			$match->casualties2 = intval($row[10]);
			$match->points2 = intval($row[11]);
			$match->id = intval($row[12]);
			$match->table = intval($row[13]);
			$match->status = $row[14];
			$match->edition = intval($row[15]);
			$match->round = intval($row[16]);
			$match->finale = intval($row[17]);
			
			return $match;
		}
		
		public function getPlayedMatchsByEditionAndRound($edition,$round){
			return $this->getPlayedMatchsByEditionAndRound($edition,$round,'!programme');
		}
		
		public function getToPlayMatchsByEditionAndRound($edition,$round){
			return $this->getPlayedMatchsByEditionAndRound($edition,$round,'programme');
		}		
		
		public function getMatchsByEditionAndRound($edition,$round,$state=''){
			
			$roundConverted = intval($round);			
			$query = self::matchQuery;
			$query .= ' WHERE m.edition='.intval($edition);
			
			if ( 0 !== $roundConverted ){
				$query .= ' AND m.round='.$roundConverted;	
			}
			if ('programme' === $state ){
				$query .= ' AND m.status == \'programme\'';
			}elseif( '!programme' === $state ) {
					$query .= ' AND m.status != \'programme\'';
			}
			$query .= ' ORDER BY m.round, m.table_number ASC';	
			//echo 'query : |',$query,"|<br />\n";
			$result = $this->query($query);
			$matches = array();
			if( $result )
			{
				$row = $this->resultFetchRow($result);
				while(null != $row){
					$match = $this->convertRowInMatch($row);
					$matches[] = $match;
					$row = $this->resultFetchRow($result);
				}
				$this->resultClose($result);
			}
			return $matches;
		}
		
		public function getMatchById($id){
			$query = self::matchQuery;
			$query .= ' WHERE m.id_match ='.intval($id);
			//echo 'request : [',$query,']<br />';
			$result = $this->query($query);
			$match = NULL;
			$row = $this->resultFetchRow($result);
			if( NULL != $row ){
				$match = $this->convertRowInMatch($row);
			}
			$this->resultClose($result);
			return $match;
		}		
		
		public function getMatchsByCoach($coachId){
			$convertedCoachId = intval($coachId);
			$query = self::matchQuery;
			$query .= ' WHERE m.id_coach_1='.$convertedCoachId;
			$query .= ' OR m.id_coach_2='.$convertedCoachId;
			$query .= ' ORDER BY m.edition, m.round ASC';
			$result = $this->query($query);
			$matches = array();
			if( $result )
			{
				$row = $this->resultFetchRow($result);
				while(null != $row){
					$match = $this->convertRowInMatch($row);
					$matches[] = $match;
					$row = $this->resultFetchRow($result);
				}
				$this->resultClose($result);
			}
			return $matches;
		}		
				
		public function getMatchsByCoachTeam($coachTeamId){
			$convertedCoachTeamId = intval($coachTeamId);
			$query = self::matchQuery;
			$query .= ' WHERE c1.id_coach_team='.$convertedCoachTeamId;
			$query .= ' OR c2.id_coach_team='.$convertedCoachTeamId;
			$query .= ' ORDER BY m.edition, m.round, m.table_number ASC';
			//echo "query : |",$query,"|<br />\n";
			$result = $this->query($query);
			$matches = array();
			if( $result )
			{
				$row = $this->resultFetchRow($result);
				while(null != $row){
					$match = $this->convertRowInMatch($row);
					$matches[] = $match;
					$row = $this->resultFetchRow($result);
				}
				$this->resultClose($result);
			}
			return $matches;
		}
		
		public function insertTeam($coach){
		  $coachs = array($coach);
		  $this->insertTeams($coachs);
		}
    
		public function insertTeams($coachs){
			if ( NULL != $this->mySQL ){
				if ($stmt = $this->mySQL->prepare(self::insertTeamQuery)){
					foreach ($coachs as $coach){
						$teamName = (property_exists($coach,'teamName')?$coach->teamName:'');
						$name = (property_exists($coach,'name')?$coach->name:'');
						$race = (property_exists($coach,'race')?intval($coach->race):0);
						$email = (property_exists($coach,'email')?$coach->email:'');
						$ff = (property_exists($coach,'ff')?intval($coach->ff):0);
						$naf = (property_exists($coach,'naf')?intval($coach->naf):0);
						$edition = (property_exists($coach,'edition')?intval($coach->edition):0);
						$ready = (property_exists($coach,'ready')?intval($coach->ready):0);
						$coachTeam = (property_exists($coach,'coach_team')?intval($coach->coach_team):0);

						$stmt->bind_param('ssdsddddd', $teamName,$name,$race,$email,$ff,$naf,$edition,$ready,$coachTeam);
						$stmt->execute();
					}
					$stmt->close();
				}
			}else{
				foreach ($coachs as $coach){
					$teamName = mysql_real_escape_string((property_exists($coach,'teamName')?$coach->teamName:''));
					$name = mysql_real_escape_string((property_exists($coach,'name')?$coach->name:''));
					$race = (property_exists($coach,'race')?intval($coach->race):0);
					$email = mysql_real_escape_string((property_exists($coach,'email')?$coach->email:''));
					$ff = (property_exists($coach,'ff')?intval($coach->ff):0);
					$naf = (property_exists($coach,'naf')?intval($coach->naf):0);
					$edition = (property_exists($coach,'edition')?intval($coach->edition):0);
					$ready = (property_exists($coach,'ready')?intval($coach->ready):0);
					$coachTeam = (property_exists($coach,'coachTeam')?intval($coach->coachTeam):0);

					$query = "INSERT INTO tournament_coach (team_name,name,id_race,email,fan_factor,naf_number,edition,ready,id_coach_team) VALUES('$teamName','$name',$race,'$email',$ff,$naf,$edition,$ready,$coachTeam)";
					//echo 'Insert team query  : [',$query,']<br />';
					$this->query($query);
				}
			}
		}
	
      public function modifyTeam($coach){
		  $coachs = array($coach);
		  $this->modifyTeams($coachs);
		}
    
		public function modifyTeams($coachs){
			if ( NULL != $this->mySQL ){
				if ($stmt = $this->mySQL->prepare(self::updateTeamQuery)){
					foreach ($coachs as $coach){
						$teamName = (property_exists($coach,'teamName')?$coach->teamName:'');
						$name = (property_exists($coach,'name')?$coach->name:'');
						$race = (property_exists($coach,'raceId')?intval($coach->raceId):0);
						$email = (property_exists($coach,'email')?$coach->email:'');
						$ff = (property_exists($coach,'ff')?intval($coach->ff):0);
						$naf = (property_exists($coach,'nafNumber')?intval($coach->nafNumber):0);
						$edition = (property_exists($coach,'edition')?intval($coach->edition):0);
						$ready = (property_exists($coach,'ready')?intval($coach->ready):0);
						$id = (property_exists($coach,'id')?intval($coach->id):0);
						$stmt->bind_param('ssisiiiii', $teamName,$name,$race,$email,$ff,$naf,$edition,$ready,$id);
						$stmt->execute();
					}
					$stmt->close();
				}
			}else{
				foreach ($coachs as $coach){
					$name = mysql_real_escape_string((property_exists($coach,'name')?$coach->name:''));
					$teamName = mysql_real_escape_string((property_exists($coach,'teamName')?$coach->teamName:''));
					$race = (property_exists($coach,'raceId')?intval($coach->raceId):0);
					$email = mysql_real_escape_string((property_exists($coach,'email')?$coach->email:''));
					$ff = (property_exists($coach,'ff')?intval($coach->ff):0);
					$naf = (property_exists($coach,'nafNumber')?intval($coach->nafNumber):0);
					$edition = (property_exists($coach,'edition')?intval($coach->edition):0);
					$ready = (property_exists($coach,'ready')?intval($coach->ready):0);
					$id = (property_exists($coach,'id')?intval($coach->id):0);

					$query = "UPDATE tournament_coach SET ";
					$query .= "team_name='$teamName',name='$name',id_race=$race,email='$email',";
					$query .= "fan_factor=$ff,naf_number=$naf,edition=$edition,ready=$ready";
					$query .= "WHERE id=$id";
					//echo 'Update team query  : [',$query,']<br />';
					$this->query($query);
				}
			}
		}	
	
		public function insertMatch($match){
			$coach1 = intval($match->teamId1);
			$coach2 = intval($match->teamId2);
			$round = intval($match->round);
			$edition = intval($match->edition);
			$table = intval($match->table);
			$result = 0;
			if( NULL != $this->mySQL ){
				if ($stmt = $this->mySQL->prepare(self::insertMatchQuery)) {
					$stmt->bind_param('ddddd', $coach1, $coach2, $round, $edition,$table);
					$stmt->execute();
					$result =  $stmt->insert_id;
					$stmt->close();
				}
			}else{
				$query ="INSERT INTO tournament_match (id_coach_1,id_coach_2,round,edition,table_number) VALUES($coach1,$coach2,$round,$edition,$table)";
				if (TRUE == $this->query($query) ){
					$result = mysql_insert_id();
				}
			}
			
			return $result;
		}
		
		public function resumeMatch($match){
			$id = intval($match->id);
			$td1 = intval($match->td1);
			$td2 = intval($match->td2);
			$cas1 = intval($match->casualties1);
			$cas2 = intval($match->casualties2);
			$points1 = intval($match->points1);
			$points2 = intval($match->points2);
			$result = 0;
			if ( NULL != $this->mySQL ){
				$stmt = $this->mySQL->prepare(self::resumeMatchQuery);

				if (NULL != $stmt) {
					$stmt->bind_param('ddddddd', $td1, $td2, $cas1, $cas2, $points1, $points2, $id);
					$tempResult = $stmt->execute();
					$result = intval($tempResult);
					$stmt->close();
				}
			}else{
				$query = "UPDATE tournament_match SET td_1=$td1,td_2=$td2,sortie_1=$cas1,sortie_2=$cas2,points_1=$points1,points_2=$points2,status='resume'	WHERE id=$id";
				if( TRUE == $this->query($query)){
					$result = 1;
				}
			}
			return $result;
		}
		
		public function deleteMatchById($id){
			$id = intval($id);
			$result = 0;
			$query = self::deleteMatchQuery;
			if(NULL != $this->mySQL){
				$query .= ' WHERE id=?';
				$stmt = $this->mySQL->prepare($query);

				if (NULL != $stmt) {
					$stmt->bind_param('d', $id);
					$tempResult = $stmt->execute();
					$result = intval($tempResult);
					$stmt->close();
				}
			}else{
				$query .= " WHERE id=$id";
				if( TRUE == $this->query($query) )
				{
					$result = 1;
				}
			}
			return $result;
		}
		
		public function getAvailableTeamsByEditionAndRound($edition,$roundNumber){
			$query = 'SELECT m.id_coach_1,m.id_coach_2';
			$query .= ' FROM tournament_match m';
			$query .= ' WHERE m.edition='.intval($edition);
			$query .= ' AND m.round='.intval($roundNumber);
			$query .= ' ORDER BY m.table_number ASC';
			$result = $this->query($query);
			$unavailableTeams = '';
			$row = $this->resultFetchRow($result);
			$first = true;
			while(null != $row){
				if( true != $first ){
					$unavailableTeams .= ','; 	
				}else{
					$first = false;
				}
				$unavailableTeams .= $row[0]; 
				$unavailableTeams .= ','; 	
				$unavailableTeams .= $row[1];
				$row = $this->resultFetchRow($result);
			}
			$this->resultClose($result);
			$query = self::teamQuery;
			$query .= ' WHERE c.edition = '.intval($edition);
			if( 0 != strlen($unavailableTeams) ){
				$query .= ' AND c.id NOT IN ('.$unavailableTeams.')';
			}
			$query .= ' ORDER BY c.id ASC';
			$result = $this->query($query);
			$coachs = array();
			$row = $this->resultFetchRow($result);
			while(null != $row){
				$coach = $this->convertRowInCoach($row);
				$coachs[] = $coach;
				$row = $this->resultFetchRow($result);
			}
			$this->resultClose($result);
			return $coachs;
		}
		
		public function getAvailableTablesByEditionAndRound($edition,$roundNumber){
			$query = 'SELECT count(c.id) FROM tournament_coach c';
			$query .= ' WHERE c.edition='.intval($edition);
			$result = $this->query($query);
			$row = $this->resultFetchRow($result);
			$first = true;
			while(null != $row){
				$coachNb = $row[0];
				$row = $this->resultFetchRow($result);
			}
			$this->resultClose($result);
			$tableNb = ($coachNb - ($coachNb % 2) )/ 2;
			$unavailableTables = range(1,$tableNb,1);
			
			$query = 'SELECT m.table_number FROM tournament_match m';
			$query .= ' WHERE m.edition='.intval($edition);
			$query .= ' AND m.round='.intval($roundNumber);
			$query .= ' ORDER BY m.table_number ASC';
			$result = $this->query($query);
			$row = $this->resultFetchRow($result);
			while( null != $row ){
				unset($unavailableTables[$row[0]-1]);
				$row = $this->resultFetchRow($result);
			}
			$this->resultClose($result);
			
			return $unavailableTables;
		}
		
		public function getTeamRanking($edition){
			$query = self::teamQuery;
			$query .= ' WHERE c.edition = '.intval($edition);
			$query .= ' ORDER BY c.points DESC, c.opponents_points DESC, c.net_td DESC, c.casualties DESC';
			//echo 'request : [',$query,']<br />';
			$result = $this->query($query);
			$coachs = array();
			$row = $this->resultFetchRow($result);
			while(null != $row){
				$coach = $this->convertRowInCoach($row);
				$coachs[] = $coach;
				$row = $this->resultFetchRow($result);
			}
			$this->resultClose($result);
			return $coachs;
		}
		
		public function getTeamRankingBetweenRounds($edition,$rankingStrategy,$beginRound,$endRound){
			$coachs = $this->getCoachsByEdition($edition,true);
			
			$query = self::matchQuery;
			$query .= ' WHERE m.edition='.intval($edition).' AND m.round <= '.intval($endRound).' AND m.round >= '.intval($beginRound);
			$query .= ' AND m.status <> \'programme\'';
			$result = $this->query($query);
			$row = $this->resultFetchRow($result);
			$first = 1;
			while(null != $row)
			{
				$match = $this->convertRowInMatch($row);
				$coach1 = $coachs[$match->teamId1];
				$coach1->points += $match->points1;
				$coach1->td += $match->td1;
				$coach1->netTd += $match->td1 - $match->td2;
				$coach1->casualties += $match->casualties1;
				$coach1->opponentDirectPoints = $match->points2;
				$coach1->opponents[]=$match->teamId2;
				$coach2 = $coachs[$match->teamId2];
				if("true" == $match->finale){
					if($match->td1 > $match->td2){
						$coach1->special=2;
						$coach2->special=1;
					}else{
						$coach1->special=1;
						$coach2->special=2;
					}
				}
				
				$coach2->points += $match->points2;
				$coach2->td += $match->td2;
				$coach2->netTd += $match->td2 - $match->td1;
				$coach2->casualties += $match->casualties2;
				$coach2->opponentDirectPoints = $match->points1;
				$coach2->opponents[]=$match->teamId1;
				$coachs[$match->teamId1] = $coach1;
				$coachs[$match->teamId2] = $coach2;

				$row = $this->resultFetchRow($result);
			}
			$this->resultClose($result);
			
			foreach($coachs as $key=>$coach)
			{
				$OpponentsPoints = 0;
				if( (true == property_exists($coach,'opponents')) && (true == is_array($coach->opponents)) )
				{
					foreach($coach->opponents as $opponent)
					{
						$opponentTeam = $coachs[$opponent];
						$OpponentsPoints += $opponentTeam->points;
					}
				}
				if( true == property_exists($coach,'opponentDirectPoints') )
				{
					$OpponentsPoints -= $coach->opponentDirectPoints;
				}
				$coach->opponentsPoints = $OpponentsPoints;
			}
			usort($coachs,array($rankingStrategy,'compareCoachs'));
			
			return $coachs;
		}
		
		public function updateRanking($edition,$coachs){
			$error = 0;
			$result = 0;
			if( NULL != $this->mySQL ){
				$stmt = $this->mySQL->prepare(self::updateRankingQuery);
				if (NULL != $stmt)
				{
					foreach($coachs as $coach){
						$stmt->bind_param('ddddd', $coach->points, $coach->opponentsPoints, $coach->netTd, $coach->casualties, $coach->id);
						$tempResult = $stmt->execute();
						if(false == $tempResult)
						{
							$error ++;
						}
					}
					$stmt->close();
				}
			}else{
				foreach($coachs as $coach){
						$query = 'UPDATE tournament_coach SET points='.intval($coach->points);
						$query .= ',opponents_points='.intval($coach->opponentsPoints);
						$query .= ',net_td='.intval($coach->netTd);
						$query .= ',casualties='.intval($coach->casualties);
						$query .= ' WHERE id='.intval($coach->id);
						$tempResult = $this->query($query);
						if(FALSE == $tempResult)
						{
							$error ++;
						}
				}
			}
			if(0 == $error)
			{
				$result = 1;
			}
			return $result;
			
		}
		
		protected function initCoachTeamForRanking(){
			$coachTeam = (object) array();
			$coachTeam = (object) array();
			$coachTeam->id = 0;
			$coachTeam->name = "";
			$coachTeam->coachTeamPoints = 0;
			$coachTeam->point = 0;
			$coachTeam->tdFor = 0;
			$coachTeam->tdAgainst = 0;
			$coachTeam->diffTd = 0;
			$coachTeam->netTd = 0;
			$coachTeam->casFor = 0;
			$coachTeam->sortie = 0;
			$coachTeam->casAgainst = 0;
			$coachTeam->netCas = 0;
			$coachTeam->opponentIdArray = array();
			$coachTeam->opponentCoachTeamIdArray = array();
			$coachTeam->teams = array();
			$coachTeam->opponentsPoints = 0;
			$coachTeam->libre = 1;
			return $coachTeam ;	
		}

		public function getCoachTeamRanking($edition,$rankingStrategy){
			// Attention requete de la mort pour recuperer tous les matchs du point de vue d'une coach_team
			$query = "(SELECT c.id_coach_team AS coachTeam, m.id_coach_1 AS team,";
			$query .= " m.round AS round, ct.name AS coachTeamName,";
			$query .= " m.points_1 AS points, m.points_2 AS opponentPoints,";
			$query .= " m.td_1 AS td,m.td_2 AS opponentTd, m.casualties_1 AS casualties,";
			$query .= " m.casualties_2 AS opponentCasualties, m.id_coach_2 AS opponentTeam,";
			$query .= " oc.id_coach_team AS opponentCoachTeam, c.name AS coach";
			$query .= " FROM tournament_match m";
			$query .= " INNER JOIN tournament_coach c ON c.id = m.id_coach_1";
			$query .= " INNER JOIN tournament_coach_team ct ON ct.id = c.id_coach_team ";
			$query .= " INNER JOIN tournament_coach oc ON oc.id = m.id_coach_2 ";
			$query .= " WHERE m.edition=".intval($edition)." AND m.status <> 'programme' )";
			$query .= " UNION ";
			$query .= "(SELECT c.id_coach_team AS coachTeam, m.id_coach_2 AS team,";
			$query .= " m.round AS round, ct.name AS coachTeamName,";
			$query .= " m.points_2 AS points, m.points_1 AS opponentPoints,";
			$query .= " m.td_2 AS td,m.td_1 AS opponentTd, m.casualties_2 AS casualties,";
			$query .= " m.casualties_1 AS opponentCasualties, m.id_coach_1 AS opponentTeam,";
			$query .= " oc.id_coach_team AS opponentCoachTeam, c.name AS coach";
			$query .= " FROM tournament_match m";
			$query .= " INNER JOIN tournament_coach c ON c.id = m.id_coach_2";
			$query .= " INNER JOIN tournament_coach_team ct ON ct.id = c.id_coach_team ";
			$query .= " INNER JOIN tournament_coach oc ON oc.id = m.id_coach_1 ";
			$query .= " WHERE m.edition=".intval($edition)." AND m.status <> 'programme' )";
			$query .= " ORDER BY coachTeam,round";


			$result = $this->query($query);
			
			$coachTeams = array();// List of coachTeams
			$pointsByTeamId = array();

			$currentCoachTeamId = 0;
			$currentRound = 0;
			$td1Array = array();
			$td2Array = array();
			$cas1Array = array();
			$cas2Array = array();
			$tempCoachTeamPoints1 = 0;
			$tempCoachTeamPoints2 = 0;
	
			$coachTeam = $this->initCoachTeamForRanking();
			$coachTeamMatchElt = $this->resultFetchObject($result);
			
			while(null !== $coachTeamMatchElt){
				if ( ( ( 0 !== $currentCoachTeamId ) 
						&& ( $currentCoachTeamId !== $coachTeamMatchElt->coachTeam ) ) 
					|| ( $currentRound !== $coachTeamMatchElt->round ) ) {
					if ( 0 !== $currentRound ){
						$tempCoachTeamPoints1 = 0;
						$tempCoachTeamPoints2 = 0;
						$rankingStrategy->computeCoachTeamPoints(	$tempCoachTeamPoints1,$tempCoachTeamPoints2,
																			$td1Array,$td2Array,
																			$cas1Array,$cas2Array);	
					
						$coachTeam->coachTeamPoints += $tempCoachTeamPoints1;
						$coachTeam->opponentCoachTeamPoints += $tempCoachTeamPoints2;
					}					
					$td1Array = array();
					$td2Array = array();
					$cas1Array = array();
					$cas2Array = array();
					$currentRound = $coachTeamMatchElt->round;
				}
				if ( $currentCoachTeamId !== $coachTeamMatchElt->coachTeam ){
					if( 0 !== $currentCoachTeamId ){
						$coachTeams[] = $coachTeam ;
					}
					$coachTeam = $this->initCoachTeamForRanking();
					$currentCoachTeamId = $coachTeamMatchElt->coachTeam;
					$coachTeam->id = $currentCoachTeamId;
					$coachTeam->name = $coachTeamMatchElt->coachTeamName;
					
					$currentRound = $coachTeamMatchElt->round;
				}
				if(false === in_array($coachTeamMatchElt->opponentCoachTeam,$coachTeam->opponentCoachTeamIdArray) ){
					$coachTeam->opponentCoachTeamIdArray[] = $coachTeamMatchElt->opponentCoachTeam;
				}
				$coachTeam->tdFor += $coachTeamMatchElt->td;
				$coachTeam->tdAgainst += $coachTeamMatchElt->opponentTd;
				$coachTeam->diffTd += $coachTeamMatchElt->td - $coachTeamMatchElt->opponentTd;
				$coachTeam->netTd = $coachTeam->diffTd;
				$coachTeam->casFor += $coachTeamMatchElt->casualties;
				$coachTeam->casualties = $coachTeam->casFor;
				$coachTeam->casAgainst += $coachTeamMatchElt->opponentCasualties;
				$coachTeam->netCas += $coachTeamMatchElt->casualties - $coachTeamMatchElt->opponentCasualties;

				$tempPoints1 = 0;
				$tempPoints2 = 0;
				$rankingStrategy->computePoints(	$tempPoints1,$tempPoints2,
															$coachTeamMatchElt->td,$coachTeamMatchElt->opponentTd,
															$coachTeamMatchElt->casualties,$coachTeamMatchElt->opponentCasualties);
				$coachTeam->points += $tempPoints1;
				if( false === array_key_exists($coachTeamMatchElt->team, $pointsByTeamId) ){
					$pointsByTeamId[$coachTeamMatchElt->team] = $tempPoints1;
				}else{
					$pointsByTeamId[$coachTeamMatchElt->team] += $tempPoints1;
				}
				if ( false === $rankingStrategy->useOpponentPointsOfYourOwnMatch() ){
					$coachTeam->opponentsPoints -= $tempPoints2;
				}
				$td1Array[] = $coachTeamMatchElt->td;
				$td2Array[] = $coachTeamMatchElt->opponentTd;
				$cas1Array[] = $coachTeamMatchElt->casualties;
				$cas2Array[] = $coachTeamMatchElt->opponentCasualties;
				if( false === array_key_exists($coachTeamMatchElt->team, $coachTeam->teams) ){
					$coach = (object) array();
					$coach->opponentIdArray = array();
					$coach->coach = $coachTeamMatchElt->coach;
					$coach->id = $coachTeamMatchElt->team;
					$coachTeam->teams[$coachTeamMatchElt->team]	= $coach;
				}
				$coach = $coachTeam->teams[$coachTeamMatchElt->team]; 
				$coach->points += $tempPoints1; 
				if ( false === $rankingStrategy->useOpponentPointsOfYourOwnMatch() ){
					$coach->opponentsPoints -= $tempPoints2;
				}
				$coach->opponentIdArray[] = $coachTeamMatchElt->opponentTeam;
				$coach->netTd += $coachTeamMatchElt->td - $coachTeamMatchElt->opponentTd;
				$coach->casualties += $coachTeamMatchElt->casualties;
				$coachTeam->teams[$coachTeamMatchElt->team]	= $coach;
				$coachTeamMatchElt = $this->resultFetchObject($result);
			}
			if( 0 !== $currentCoachTeamId ){
				$coachTeams[] = $coachTeam ;
			}
			
			//Calcul des points adversaires			
			foreach ( $coachTeams as $coachTeam ){
				foreach ( $coachTeam->teams as $coach){
					foreach ( $coach->opponentIdArray as $opponentId ){
						$coach->opponentsPoints += $pointsByTeamId[$opponentId];
						$coachTeam->opponentsPoints += $pointsByTeamId[$opponentId];
					}	
				}
			}
			
			// Tri des participants de la coach_team dans l'ordre du classement
			foreach( array_keys($coachTeams) as $id){
				$coachTeam = $coachTeams[$id];
				usort($coachTeam->teams,array($rankingStrategy,'compareCoachs'));
				$coachTeams[$id]=$coachTeam;
			}
			
			usort($coachTeams,array($rankingStrategy,'compareCoachTeams'));
			return $coachTeams;
			
		}

		public function getEditionById($id){
			$clause = 'id = '.intval($id);
			$editions = $this->getEditions($clause);
			$edition = NULL;
			if( 0 < count($editions) )
			{
				$edition = reset($editions);
			}
			return $edition;
		}

		public function getEditions($clause){
			$query = self::editionQuery;
			$query .= ' WHERE '.$clause;
			//echo 'request : [',$query,']<br />';
			$result = $this->query($query);
			$editions = array();
			$edition = $this->resultFetchObject($result);
			while(null != $edition)
			{
				$editions[] = $edition;
				$edition = $this->resultFetchObject($result);
			}
			$this->resultClose($result);
			return $editions;
		}
    
		protected function setPreteams($clause,$fields){
			$query = 'UPDATE tournament_precoach SET ';
			$isFirst = true;
			foreach($fields as $field => $value){
				if(true == $isFirst){	
					$isFirst = false;
				}else{
					$query .= ',';
				}
				$query .= $field .'='.$value;
			}
			$query .= ' WHERE '.$clause;
			//echo "request :|$query|\n"; 
			$result = $this->query($query);
			return $result;
		}
    
		public function deletePreteams($ids){
		  $query = self::deletePreCoachQuery;
		  $clause = ' WHERE id IN('.implode(',',$ids).')';
		  $query .= $clause;
		  echo "request :|$query|\n"; 
		  $result = $this->query($query);
		}
		
		public function setContactedPreteamById($id){
		  return $this->setPreteams('id='.intval($id),array('contact'=> 1));
		}
		

	}
?>
