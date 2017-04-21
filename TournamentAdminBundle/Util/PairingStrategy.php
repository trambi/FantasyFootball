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
