<?php

namespace FantasyFootball\TournamentAdminBundle\Util;

/**
 * Description of SwissRoundStrategy
 *
 * @author Nicolas
 */
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

        $roster = $toPaired[0];
        for ($i = 1; $i < $nbToPaired; $i++) {
            if ( $this->isAllowed($roster, $toPaired[$i], $constraints) ) {
                $pair = array($roster, $toPaired[$i]);

                //echo 'Pair ' . $this->showLinearTab($pair) . '<br>';
                $paired[] = $pair;

                // on crée un nouveau tableau sans $tab[0] ni $tab[i]
                $newToPaired = $this->removeValuesByKeys($toPaired, array(0, $i));
                
                $tab_pairs = $this->pairing($paired, $newToPaired, $constraints);
                if ( null === $tab_pairs )
                    continue;
                
                return $tab_pairs;
            }
        }

        return null;
    }
    
}
