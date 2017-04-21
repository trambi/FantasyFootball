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
namespace FantasyFootball\TournamentAdminBundle\Util;

class SwissRoundStrategy extends PairingStrategy{
  
  /**
   * 
   * @param type $paired Liste d'appariements déjà effectués
   * @param type $toPaired Liste des restants à apparier triée
   * @param type $constraints Liste des appariements interdits
   * @return liste d'appariement satisfaisant les appariements interdits
   */
  public function pairing(Array $paired, Array $toPaired, Array $constraints)
  {
    //echo 'Appel pairing ' . $this->show2DTab($paired) . ' / ' . $this->showLinearTab($toPaired) . '<br>';
    $nbToPaired = count($toPaired);
    if ( 2 === $nbToPaired ) {
      if ($this->isAllowed($toPaired[0], $toPaired[1], $constraints)) {
        $paired[] = array($toPaired[0], $toPaired[1]);
        return $paired;
      }
      return null;
    }
    
    if( 1 === $nbToPaired){
      return $paired;
    }

    $roster = $toPaired[0];
    for ($i = 1; $i < $nbToPaired; $i++) {
      if ( $this->isAllowed($roster, $toPaired[$i], $constraints) ) {
        $pair = array($roster, $toPaired[$i]);

        //echo 'Pair ' . $this->showLinearTab($pair) . '<br>';
        $paired[] = $pair;

        // on crée un nouveau tableau sans $tab[0] ni $tab[i]
        $newToPaired = $this->removeValuesByKeys($toPaired, array(0, $i));
            
        $tab_pairs = $this->pairing($paired, $newToPaired, $constraints);
        if ( null === $tab_pairs ){
          continue;
        }
        return $tab_pairs;
      }
    }
    return null;
  }
  
}