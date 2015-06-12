<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tisseurs\Bundle\BloodBowlBundle\Entity\Game;
use Tisseurs\Bundle\BloodBowlBundle\Entity\GameResult;
use Tisseurs\Bundle\BloodBowlBundle\Entity\Ranking;
use Tisseurs\Bundle\BloodBowlBundle\Model\RDVBBRankingStrategy;
use Tisseurs\Bundle\EventManagerBundle\Entity\Event;
use Tisseurs\Bundle\EventManagerBundle\Form\EventType;
use Tisseurs\Bundle\EventManagerBundle\Model\RondeSuisseStrategy;

class EventController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('TisseursEventManagerBundle:Event')->findAll();
        
        if (count($events) === 0) {
            return $this->configureAction($request);
        }
        
        $bundle = 'TisseursBloodBowlBundle';
        $rosters = $em->getRepository($bundle.':Roster')->findAll();
        //$archetypes = $em->getRepository($bundle.':Archetype')->findAll();
        
        $form = $this->createForm(new EventType(), new Event());

        return $this->render('TisseursEventManagerBundle:Event:view.html.twig', array(
            //'event'         => $event,
            'events'        => $events,
            'rosters'       => $rosters,
            'roster_repartition' => $this->getRosterRepartition($rosters),
            //'archetypes'    => $archetypes
            'form' => $form->createView()
        ));
    }
    
    public function configureAction(Request $request)
    {
        $form = $this->createForm(new EventType(), new Event());

        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $event = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();            
            
            return $this->redirect($this->generateUrl('event_view', 
                array(
                    'id' => $event->getId()
                )
            ));
        }

        return $this->render('TisseursEventManagerBundle:Event:setup.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @ParamConverter("event", options={"mapping" : {"event_name" : "name", "event_edition" : "edition"}})
     * @param Event $event
     * @param Request $request
     * @return type
     */
    public function viewAction(Event $event, Request $request)
    {
        // on rÃ©cupÃ¨re le jeu afin de pouvoir rediriger vers le bon bundle
        $game = $event->getGame()->getName();
        
        return $this->redirect($this->generateUrl(strtolower($game).'_roster_list', 
            array(
                'event_name' => $event->getName(),
                'event_edition' => $event->getEdition()
            )
        ));
        
    }
    
    /**
     * @ParamConverter("event", options={"mapping" : {"event_name" : "name", "event_edition" : "edition"}})
     * @param Event $event
     * @param Request $request
     * @return type
     */
    public function editAction(Event $event, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $rankingStrat = new RDVBBRankingStrategy();
        $earningMatrix = $em->getRepository('TisseursEventManagerBundle:EarningMatrix')->findOneByEvent($event);
        
        /*$scores = array(
            array(3, 0),
            array(2, 1),
            array(2, 2),
            array(1, 2),
            array(0, 3),
        );
        
        foreach ($scores as $score) {
            $gains = $rankingStrat->computePoints($score[0], $score[1], $earningMatrix);
            echo $score[0] . '/' . $score[1] . " => " . $gains[0] . '/' . $gains[1] . '<br>';
        }*/
        $roster1 = $em->getRepository('TisseursBloodBowlBundle:Roster')->find(1);
        $roster2 = $em->getRepository('TisseursBloodBowlBundle:Roster')->find(2);
        
        $game = new Game();
        $game->setEvent($event);
        $game->setRound(2);
        $game->setRoster1($roster1);
        $game->setRoster2($roster2);
        $resultMatch = $this->generateResultMatch($game);
        $game->setResult($resultMatch);
        
        //$em->persist($game);
        //$em->flush();
        
        $this->updateRanking($event, 2, $em->getRepository('TisseursBloodBowlBundle:GameResult')->find(13));
        
        $rankings = $em->getRepository('TisseursBloodBowlBundle:Ranking')->findBy(array('event' => $event, 'round' => 2));
        
        var_dump($rankings);
        
        $ranks_ = array();
        foreach ($rankings as $ranking) {
            $ranks_[] = $ranking;
        }
        
        return $this->render('TisseursEventManagerBundle:Event:edit.html.twig', 
            array(
                'event' => $event,
                'rankings' => $rankings
            )
        );
    }
    
    /**
     * @ParamConverter("event", options={"mapping" : {"event_name" : "name", "event_edition" : "edition"}})
     * @param Event $event
     * @param Request $request
     */
    public function simulTournamentAction(Event $event, Request $request) 
    {        
        $em = $this->getDoctrine()->getManager();
        $module = $event->getGame()->getName();
        
        $nb_coachs = $event->getNbMaxPlayers();
        $max_rondes = $event->getNbRounds();
        $pairingStrategy = new RondeSuisseStrategy();
        $rankingStrategy = new RDVBBRankingStrategy();
        
        $logs = array();

        $constraints_ = array();
        for ($i = 1; $i <= $nb_coachs; $i++) {
            $constraints_[$i] = array();
        }

        $ranks_ = array();
        $toPaired_ = array();
        $rosters = $em->getRepository('Tisseurs'.$module.'Bundle:Roster')->findByEvent($event);
        
        foreach ($rosters as $roster) {
            $rankingRepo = $em->getRepository('Tisseurs'.$module.'Bundle:Ranking');
            $ranking = $rankingRepo->findOneBy(array('event' => $event, 'roster' => $roster));
            if ($ranking === null) {
                $ranking = new Ranking();
                $ranking->setEvent($event);
                $ranking->setRoster($roster);
                $ranking->setRound(0);
                $em->persist($ranking);
                $em->flush();
            }
                
            $ranks_[] = $ranking->getId();
            $toPaired_[] = $roster->getId();
        }
        
        /*$paired_ = array();
        $pairing = $pairingStrategy->pairing($paired_, $toPaired_, $constraints_);
        echo $pairingStrategy->showLinearTab($ranks_) . '<br>'. $pairingStrategy->show2DTab($pairing); exit;
        */
        //$max_rondes = 1;
        $this->get('session')->getFlashBag()->clear();
        for ($ronde = 1; $ronde <= $max_rondes; $ronde++) {
            
            $paired_ = array();
            $pairing = $pairingStrategy->pairing($paired_, $toPaired_, $constraints_);
            $this->get('session')->getFlashBag()->add('notice', 'Pairing : ' . $pairingStrategy->show2DTab($pairing));
            // simulation des matchs
            $this->generateMatchs($event, $ronde, $pairing);

            // update des contraintes pour interdire que les matches ne soient rejouÃ©s
            $this->updateConstraints($pairing, $constraints_);
            
            // on ordonne la liste suivant les gains dans ranks_
            $ranks_ = $em->getRepository('Tisseurs'.$module.'Bundle:Ranking')->getRanking($event, $ronde, $rankingStrategy);
            
            $toPaired_ = $this->extractIdRosters($ranks_);//$rankingStrategy->getRanking($ranks_);       
            $this->get('session')->getFlashBag()->add('notice', 'Ranking : ' . $pairingStrategy->showLinearTab($toPaired_));
        }
        
        return $this->render('TisseursEventManagerBundle:Event:edit.html.twig', 
            array(
                'event' => $event,
                'logs'  => $logs
            )
        );
    }

    private function extractIdRosters($rankings)
    {
        $ranks_ = array();
        foreach ($rankings as $ranking) {
            $ranks_[] = $ranking->getRoster()->getId();
        }
        
        return $ranks_;
    }
    
    private function updateConstraints($pairing, &$constraints)
    {
        foreach ($pairing as $pair) {
            $roster1 = $pair[0];
            $roster2 = $pair[1];
            // on met Ã  jour le tableau des contraintes
            $constraints[$roster1][] = $roster2;
            $constraints[$roster2][] = $roster1;
        }
    }
    
    private function generateMatchs(Event $event, $round, $pairing)
    {
        $em = $this->getDoctrine()->getManager();
        
        $module = $event->getGame()->getName();
        
        foreach ($pairing as $pair) {
            //echo 'Generation du match entre le roster : ' . $pair[0] . ' et ' . $pair[1] . '<br>'; 
            $roster1 = $em->find('Tisseurs'. $module . 'Bundle:Roster', $pair[0]);
            $roster2 = $em->find('Tisseurs'. $module . 'Bundle:Roster', $pair[1]);

            $game = new Game();
            $game->setEvent($event);
            $game->setRoster1($roster1);
            $game->setRoster2($roster2);
            $game->setRound($round);
            
            $resultMatch = $this->generateResultMatch($game);
            $game->setResult($resultMatch);
            $em->persist($game);
            $em->flush();
            
            // mise Ã  jour du ranking
            $this->updateRanking($event, $round, $game);
        }
        
    }
    
    
    /*
     * $gains = array(6, 5, 3, 1, 0);
        $libResults = array('victoire majeure', 'victoire', 'null', 'dÃ©faite', 'defaite majeure');
     */
    private function generateResultMatch(Game $game)
    {        
        $gameResult = new GameResult();
        //$gameResult->setGame($game);
        //$gameResult->setRoster1($game->getRoster1());
        //$gameResult->setRoster2($game->getRoster2());
        $gameResult->setTouchdown1(rand(0, 5));
        $gameResult->setTouchdown2(rand(0, 5));
        $gameResult->setInjury1(rand(0, 4));
        $gameResult->setInjury2(rand(0, 4));
        $gameResult->setPass1(rand(0, 2));
        $gameResult->setPass2(rand(0, 2));
        $gameResult->setInterception1(rand(0, 1));
        $gameResult->setInterception2(rand(0, 1));
        $gameResult->setKill1(rand(0,1));
        $gameResult->setKill2(0);
        $gameResult->setKo1(0);
        $gameResult->setKo2(0);
        
        return $gameResult;
    }
    
    private function updateRanking(Event $event, $round, Game $game)
    {
        $em = $this->getDoctrine()->getManager();

        $module = $event->getGame()->getName();
        $roster1 = $game->getRoster1();
        $roster2 = $game->getRoster2();
        $resultMatch = $game->getResult();
        
        $rankingStrategy = new RDVBBRankingStrategy(); // TODO à fixer pour être fonction de event
        $earningMatrix = $em->getRepository('TisseursEventManagerBundle:EarningMatrix')->findOneByEvent($event);
        $gains = $rankingStrategy->computePoints($resultMatch->getTouchdown1(), $resultMatch->getTouchdown2(), $earningMatrix);
        
        
        $rankingRepository = $em->getRepository('Tisseurs'. $module . 'Bundle:Ranking');
        
        $ranking1 = $rankingRepository->findOneBy(array('event' => $event, 'roster' => $roster1));        
        $ranking1->setRound($round);
        $ranking1->addToCurrentScore($gains[0]);
        $ranking1->addToScoredTouchdown($resultMatch->getTouchdown1());
        $ranking1->addToConcededTouchdown($resultMatch->getTouchdown2());
        //$ranking1->setDiffTouchdown($ranking1->getScoredTouchdown() - $ranking1->getConcededTouchdown());
        $ranking1->addToScoredInjury($resultMatch->getInjury1());
        $ranking1->addToConcededInjury($resultMatch->getInjury2());
        //$ranking1->setDiffInjury($ranking1->getScoredInjury() - $ranking1->getConcededInjury());
        $ranking1->addToScoredPass($resultMatch->getPass1());
        $ranking1->addToConcededPass($resultMatch->getPass2());
        //$ranking1->setDiffPass($ranking1->getScoredPass() - $ranking1->getConcededPass());
        $ranking1->addToScoredInterception($resultMatch->getInterception1());
        $ranking1->addToConcededInterception($resultMatch->getInterception2());
        //$ranking1->setDiffInterception($ranking1->getScoredInterception() - $ranking1->getConcededInterception());
        $ranking1->addToScoredKill($resultMatch->getKill1());
        $ranking1->addToConcededKill($resultMatch->getKill2());
        //$ranking1->setDiffKill($ranking1->getScoredKill() - $ranking1->getConcededKill());
        $ranking1->addToScoredKo($resultMatch->getKo1());
        $ranking1->addToConcededKo($resultMatch->getKo2());
        //$ranking1->setDiffKo($ranking1->getScoredKo() - $ranking1->getConcededKo());
        $em->persist($ranking1);
        $em->flush();
        
        $ranking2 = $rankingRepository->findOneBy(array('event' => $event, 'roster' => $roster2));
        $ranking2->setRound($round);
        $ranking2->addToCurrentScore($gains[1]);
        $ranking2->addToScoredTouchdown($resultMatch->getTouchdown2());
        $ranking2->addToConcededTouchdown($resultMatch->getTouchdown1());
        //$ranking2->setDiffTouchdown($ranking2->getScoredTouchdown() - $ranking2->getConcededTouchdown());
        $ranking2->addToScoredInjury($resultMatch->getInjury2());
        $ranking2->addToConcededInjury($resultMatch->getInjury1());
        //$ranking2->setDiffInjury($ranking2->getScoredInjury() - $ranking2->getConcededInjury());
        $ranking2->addToScoredPass($resultMatch->getPass2());
        $ranking2->addToConcededPass($resultMatch->getPass1());
        //$ranking2->setDiffPass($ranking2->getScoredPass() - $ranking2->getConcededPass());
        $ranking2->addToScoredInterception($resultMatch->getInterception2());
        $ranking2->addToConcededInterception($resultMatch->getInterception1());
        //$ranking2->setDiffInterception($ranking2->getScoredInterception() - $ranking2->getConcededInterception());
        $ranking2->addToScoredKill($resultMatch->getKill2());
        $ranking2->addToConcededKill($resultMatch->getKill1());
        //$ranking2->setDiffKill($ranking2->getScoredKill() - $ranking2->getConcededKill());
        $ranking2->addToScoredKo($resultMatch->getKo2());
        $ranking2->addToConcededKo($resultMatch->getKo1());
        //$ranking2->setDiffKo($ranking2->getScoredKo() - $ranking2->getConcededKo());
        $em->persist($ranking2);
        $em->flush();
    }
    
    private function getRosterRepartition($rosters)
    {
        $repart = array();
        $nbRoster = count($rosters);
        
        foreach ($rosters as $roster) {
            $race = $roster->getRace()->getName();
            if (!array_key_exists($race, $repart)) {
                $repart[$race] = array();
                $repart[$race]['nb'] = 0;
            }
            
            $repart[$race]['nb'] = $repart[$race]['nb'] + 1;
            $repart[$race]['percent'] = round($repart[$race]['nb'] * 100 / $nbRoster, 2);
        }
        //var_dump($repart); exit;
        return $repart;
    }

}
