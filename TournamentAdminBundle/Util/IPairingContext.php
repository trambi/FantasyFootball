<?php
namespace FantasyFootball\TournamentAdminBundle\Util;

interface IPairingContext {
  /**
  * Return a array
  * first element is an array of opponent toPair
  * second element is constraints an array of forbidden games
  * third element is an array of already paired games
  * 
  */
  public function init();
  public function persist(Array $games,$round);
}
