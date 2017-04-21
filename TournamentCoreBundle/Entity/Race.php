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
namespace FantasyFootball\TournamentCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Race
 *
 * @ORM\Table(name="tournament_race")
  * @ORM\Entity(repositoryClass="FantasyFootball\TournamentCoreBundle\Entity\RaceRepository")
 */
class Race
{
    /**
     * @ORM\OneToMany(targetEntity="Coach", mappedBy="race")
     */
    protected $coachs;

    public function __construct()
    {
        $this->coachs = new ArrayCollection();
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id_race", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="edition", type="smallint")
     */
    private $edition;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_fr", type="string", length=33)
     */
    private $frenchName;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_en", type="string", length=33)
     */
    private $englishName;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_en_2", type="string", length=33)
     */
    private $englishName2;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_fr_2", type="string", length=33)
     */
    private $frenchName2;

    /**
     * @var integer
     *
     * @ORM\Column(name="reroll", type="smallint")
     */
    private $reroll;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set edition
     *
     * @param integer $edition
     * @return Race
     */
    public function setEdition($edition)
    {
        $this->edition = $edition;

        return $this;
    }

    /**
     * Get edition
     *
     * @return integer 
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * Set frenchName
     *
     * @param string $nomFr
     * @deprecated since version 1
     * @return Race
     */
    public function setNomFr($nomFr)
    {
        $this->frenchName = $nomFr;

        return $this;
    }
    
    /**
     * Set frenchName
     *
     * @param string $frenchName
     * @return Race
     */
    public function setFrenchName($frenchName)
    {
        $this->frenchName = $frenchName;

        return $this;
    }

    /**
     * Get frenchName
     * @deprecated since version 1
     * @return string 
     */
    public function getNomFr()
    {
        return $this->frenchName;
    }
    
    /**
     * Get frenchName
     *
     * @return string 
     */
    public function getFrenchName()
    {
        return $this->frenchName;
    }

    /**
     * Set englishName
     *
     * @deprecated since version 1
     * @param string $nomEn
     * @return Race
     */
    public function setNomEn($nomEn)
    {
        $this->englishName = $nomEn;

        return $this;
    }

    /**
     * Get englishName
     *
     * @deprecated since version 1
     * @return string 
     */
    public function getNomEn()
    {
        return $this->englishName;
    }

    /**
     * Set englishName2
     *
     * @deprecated since version 1
     * @param string $nomEn2
     * @return Race
     */
    public function setNomEn2($nomEn2)
    {
        $this->englishName2 = $nomEn2;

        return $this;
    }

    /**
     * Get englishName2
     * @deprecated since version 1
     * @return string 
     */
    public function getNomEn2()
    {
        return $this->englishName2;
    }

    /**
     * Set frenchName2
     *
     * @deprecated since version 1
     * @param string $nomFr2
     * @return Race
     */
    public function setNomFr2($nomFr2)
    {
        $this->frenchName2 = $nomFr2;

        return $this;
    }

    /**
     * Get frenchName2
     * @deprecated since version 1
     * @return string 
     */
    public function getNomFr2()
    {
        return $this->frenchNameFr2;
    }

    /**
     * Set englishName
     *
     * @param string $englishName
     * @return Race
     */
    public function setEnglishName($englishName)
    {
        $this->englishName = $englishName;

        return $this;
    }

    /**
     * Get englishName
     *
     * @return string 
     */
    public function getEnglishName()
    {
        return $this->englishName;
    }

    /**
     * Set englishName2
     *
     * @param string $englishName2
     * @return Race
     */
    public function setEnglishName2($englishName2)
    {
        $this->englishName2 = $englishName2;

        return $this;
    }

    /**
     * Get englishName2
     *
     * @return string 
     */
    public function getEnglishName2()
    {
        return $this->englishName2;
    }

    /**
     * Set frenchName2
     *
     * @param string $frenchName2
     * @return Race
     */
    public function setFrenchName2($frenchName2)
    {
        $this->frenchName2 = $frenchName2;

        return $this;
    }

    /**
     * Get frenchName2
     *
     * @return string 
     */
    public function getFrenchName2()
    {
        return $this->frenchName2;
    }
    
    /**
     * Set reroll
     *
     * @param integer $reroll
     * @return Race
     */
    public function setReroll($reroll)
    {
        $this->reroll = $reroll;

        return $this;
    }

    /**
     * Get reroll
     *
     * @return integer 
     */
    public function getReroll()
    {
        return $this->reroll;
    }

    /**
     * Add coachs
     *
     * @param \FantasyFootball\TournamentCoreBundle\Entity\Coach $coachs
     * @return Race
     */
    public function addCoach(\FantasyFootball\TournamentCoreBundle\Entity\Coach $coachs)
    {
        $this->coachs[] = $coachs;

        return $this;
    }

    /**
     * Remove coachs
     *
     * @param \FantasyFootball\TournamentCoreBundle\Entity\Coach $coachs
     */
    public function removeCoach(\FantasyFootball\TournamentCoreBundle\Entity\Coach $coachs)
    {
        $this->coachs->removeElement($coachs);
    }

    /**
     * Get coachs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCoachs()
    {
        return $this->coachs;
    }
}
