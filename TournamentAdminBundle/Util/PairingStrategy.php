<?php

namespace FantasyFootball\TournamentAdminBundle\Util;

/**
 * Description of PairingStrategy
 *
 * @author Nicolas
 */
abstract class PairingStrategy 
{

    public function isAllowed($roster1, $roster2, $constraints = array())
    {
        $isAllowed = true;
        if (array_key_exists($roster1, $constraints))
        {
            $isAllowed = !in_array($roster2, $constraints[$roster1]);
        }
        return $isAllowed;
    }

    public function showLinearTab($tab)
    {
        $show = '[';
        $i = 1;
        if (count($tab) > 0) {
            foreach ($tab as $element) {
                $show = $show . $element;
                if ($i < count($tab)) {
                    $show = $show . ', ';
                }
                $i++;
            }
        }
        return $show . ']';
    }
    

    public function show2DTab($elements) 
    {
        $show = '[';
        $i = 1;
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                $show = $show . $this->showLinearTab($element);
                if ($i < count($elements)) {
                    $show = $show . ', ';
                }
                $i++;
            }
        }
        return $show . ']';
    }
    
    protected function removeValuesByKeys(Array $arraySrc, Array $keyToRemove)
    {
        $arrayDst = array();
        for ($i = 0; $i < count($arraySrc); $i++)
        {
            if(!in_array($i, $keyToRemove)) {
                $arrayDst[] = $arraySrc[$i];
            }
        }
        
        return $arrayDst;
    }
    
    /**
     * 
     * @param type $paired Liste d'appariements déjà effectués
     * @param type $toPaired Liste des restants à apparier triée
     * @param type $constraints Liste des appariements interdits
     * @return liste d'appariement satisfaisant les appariements interdits
     */
    public abstract function pairing(Array $paired, Array $toPaired, Array $constraints);
}
