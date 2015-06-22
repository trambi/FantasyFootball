<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FantasyFootball\TournamentAdminBundle\Util;

/**
 * Description of PairingContext
 *
 * @author Trambi
 */
interface IPairingContext {
    /**
     * Return a array
     * first element is toPair an array of already paired games
     * second element is constraints an array of forbidden games
     * 
     * 
     */
    
    public function init();
    public function persist(Array $games,$round);
}
