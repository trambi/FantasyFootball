<?php
namespace FantasyFootball\TournamentCoreBundle\Entity;

class Coach
{
	protected $teamName;
	protected $name;
	protected $race;
	protected $emailAddress;
	protected $nafNumber;
	protected $ready;
	protected $edition;
	protected $coachTeam;
	
	public function __construct($stdClass=null){
		if (null === $stdClass){
			$this->teamName = "";
			$this->name = "";
			$this->race = 0;
			$this->emailAddress = "";
			$this->nafNumber = 0;
			$this->ready = false;
			$this->edition = 0;
			$this->coachTeam = 0;	
		}else{
			$this->teamName = $stdClass->name;
			$this->name = $stdClass->coach;
			$this->race = $stdClass->raceId;
			$this->emailAddress = $stdClass->email;
			$this->nafNumber = $stdClass->nafNumber;
			$this->ready = (1 == $stdClass->ready?true:false);
			$this->edition = $stdClass->edition;
			$this->coachTeam = \intval($stdClass->coachTeamId);
		}
	}
	
	public function getTeamName(){
		return $this->teamName;	
	}
	
	public function setTeamName($teamName){
		$this->teamName = \mb_convert_encoding($teamName,'UTF-8');	
	}
	
	public function getName(){
		return $this->name;	
	}	
	
	public function setName($name){
		$this->name = 	\mb_convert_encoding($name,'UTF-8');
	}
	
	public function getRace(){
		return $this->race;	
	}
	
	public function setRace($raceId){
		$this->race = \intval($raceId);	
	}	
	
	public function getEmailAddress(){
		return $this->emailAddress;	
	}
	
	public function setEmailAddress($email){
		$this->emailAddress = \mb_convert_encoding($email,'UTF-8');
	}
	
	public function getNafNumber(){
		return $this->nafNumber;	
	}
	
	public function setNafNumber($nafNumber){
		$this->nafNumber = \intval($nafNumber);	
	}
	
	public function getReady(){
		return $this->ready;	
	}
	
	public function setReady($ready){
		$this->ready = \boolval($ready);	
	}
	
	public function getEdition(){
		return $this->edition;	
	}
	
	public function setEdition($edition){
		$this->edition = \intval($edition);	
	}

	public function getCoachTeam(){
		return $this->coachTeam;	
	}
	
	public function setCoachTeam($coachTeamId){
		$this->coachTeam = \intval($coachTeamId);	
	}
}
?>