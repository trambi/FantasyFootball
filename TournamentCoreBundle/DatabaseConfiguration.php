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
