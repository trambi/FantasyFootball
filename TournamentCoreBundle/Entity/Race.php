<?php

namespace FantasyFootball\TournamentCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Race
 *
 * @ORM\Table(name="tournament_race")
 * @ORM\Entity
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
    private $nomFr;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_en", type="string", length=33)
     */
    private $nomEn;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_en_2", type="string", length=33)
     */
    private $nomEn2;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_fr_2", type="string", length=33)
     */
    private $nomFr2;

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
     * Set nomFr
     *
     * @param string $nomFr
     * @return Race
     */
    public function setNomFr($nomFr)
    {
        $this->nomFr = $nomFr;

        return $this;
    }

    /**
     * Get nomFr
     *
     * @return string 
     */
    public function getNomFr()
    {
        return $this->nomFr;
    }

    /**
     * Set nomEn
     *
     * @param string $nomEn
     * @return Race
     */
    public function setNomEn($nomEn)
    {
        $this->nomEn = $nomEn;

        return $this;
    }

    /**
     * Get nomEn
     *
     * @return string 
     */
    public function getNomEn()
    {
        return $this->nomEn;
    }

    /**
     * Set nomEn2
     *
     * @param string $nomEn2
     * @return Race
     */
    public function setNomEn2($nomEn2)
    {
        $this->nomEn2 = $nomEn2;

        return $this;
    }

    /**
     * Get nomEn2
     *
     * @return string 
     */
    public function getNomEn2()
    {
        return $this->nomEn2;
    }

    /**
     * Set nomFr2
     *
     * @param string $nomFr2
     * @return Race
     */
    public function setNomFr2($nomFr2)
    {
        $this->nomFr2 = $nomFr2;

        return $this;
    }

    /**
     * Get nomFr2
     *
     * @return string 
     */
    public function getNomFr2()
    {
        return $this->nomFr2;
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
