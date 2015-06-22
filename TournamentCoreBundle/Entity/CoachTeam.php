<?php

namespace FantasyFootball\TournamentCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * CoachTeam
 *
 * @ORM\Table(name="tournament_coach_team")
  * @ORM\Entity(repositoryClass="FantasyFootball\TournamentCoreBundle\Entity\CoachTeamRepository")
 */
class CoachTeam
{
    /**
     * @ORM\OneToMany(targetEntity="Coach", mappedBy="coachTeam")
     */
    protected $coachs;

    public function __construct()
    {
        $this->coachs = new ArrayCollection();
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * Set name
     *
     * @param string $name
     * @return CoachTeam
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add coach
     *
     * @param \FantasyFootball\TournamentCoreBundle\Entity\Coach $coach
     * @return CoachTeam
     */
    public function addCoach(\FantasyFootball\TournamentCoreBundle\Entity\Coach $coach)
    {
        $this->coachs[] = $coach;
        $coach->setCoachTeam($this);
        return $this;
    }

    /**
     * Remove coachs
     *
     * @param \FantasyFootball\TournamentCoreBundle\Entity\Coach $coachs
     */
    public function removeCoach(\FantasyFootball\TournamentCoreBundle\Entity\Coach $coach)
    {
        $this->coachs->removeElement($coach);
        $coach->setCoachTeam();
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
