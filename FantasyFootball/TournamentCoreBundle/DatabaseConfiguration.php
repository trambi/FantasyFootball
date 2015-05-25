<?php

namespace FantasyFootball\TournamentCoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DatabaseConfiguration
{
	protected $host;
	protected $name;
	protected $user;
	protected $password;
	
	public function __construct($host,$name,$user,$password){
		$this->host = $host;
		$this->name = $name;
		$this->user = $user;
		$this->password = $password;		
	}
	
	public function getHost(){
		return $this->host;	
	}
	
	public function getName(){
		return $this->name;	
	}
	
	public function getUser(){
		return $this->user;	
	}
	
	public function getPassword(){
		return $this->password;	
	}
}
