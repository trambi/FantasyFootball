<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FantasyFootball\TournamentCoreBundle\Entity\Game;
use FantasyFootball\TournamentCoreBundle\Entity\CoachRepository;

use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

class GameController extends Controller
{
    public function DeleteAction(Request $request,$coachId)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')->findById($gameIdeditionId);
        $conf = $this->get('fantasy_football_core_db_conf');
        $data = new DataUpdater($conf);
        $coach = new Coach($data->getCoachById($coachId));
		
        $form = $this->createFormBuilder($coach)
                	->add('delete','submit')
			->getForm();

        $form->handleRequest($request);
	$coach->id = $coachId;
	if ($form->isValid()) {
            $data->deleteCoach($coach);
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_homepage'));
	}
	return $this->render('FantasyFootballTournamentAdminBundle:Coach:Delete.html.twig', array(
            'coach'=>$coach,
            'form' => $form->createView()
	));
    }

    public function ScheduleAction(Request $request,$edition)
    {
        $game = new Game();
        $em = $this->getDoctrine()->getManager();
        $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')->findOneById($edition);
        $currentRound = $editionObj->getCurrentRound();
        $roundChoice = array($currentRound);
        
        $coachs = $em->getRepository('FantasyFootballTournamentCoreBundle:Coach')->findByEdition($edition);
        $coachNumber = count($coachs) ;
        $tableMaxNumber = ( $coachNumber - ( $coachNumber %2 ) )/ 2;
        $tableChoice = range(1,$tableMaxNumber);
        
        $form = $this->createFormBuilder($game)
                    ->add('round', 'choice',
                        array('label'=>'Tour :',
                            'choices'   => $roundChoice,
                            'required'  => true))
                    ->add('tableNumber', 'choice',
                        array('label'=>'Table :',
                            'choices'   => $tableChoice,
                            'required'  => true))
                    ->add('coach1', 'entity',
                        array('label'=>'Coach 1 :',
                            'class'   => 'FantasyFootballTournamentCoreBundle:Coach',
                            'property'  => 'name',
                            'query_builder' => function(CoachRepository $cr){
                                return $cr->qbForFreeCoachsCurrentEditionAndCurrentRound();
                            }))
                    ->add('coach2', 'entity',
                        array('label'=>'Coach 2 :',
                            'class'   => 'FantasyFootballTournamentCoreBundle:Coach',
                            'property'  => 'name',
                            'query_builder' => function(CoachRepository $cr){
                                return $cr->qbForFreeCoachsCurrentEditionAndCurrentRound();
                            }))
                    ->add('save','submit',array('label'=>'Valider'))
                    ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $game->setRound($currentRound);
            $game->setEdition($edition);
            $em->persist($game);
            $em->flush();
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
        }

        return $this->render('FantasyFootballTournamentAdminBundle:Game:schedule.html.twig', array(
                                'form' => $form->createView() ) );
        
    }

    public function ResumeAction(Request $request,$gameId)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('FantasyFootballTournamentCoreBundle:Game')->findOneById($gameId);
	$name1 = $game->getCoach1()->getName();
	$name2 = $game->getCoach2()->getName();
	$form = $this->createFormBuilder($game)
            ->add('td1', 'integer',
                array('label'=>'Touchdown de '.$name1.' :',
                    'required'  => true))
            ->add('td2', 'integer',
                array('label'=>'Touchdown de '.$name2.' :',
                    'required'  => true))
            ->add('casualties1', 'integer',
                array('label'=>'Sorties de '.$name1.' :',
                    'required'  => true))
            ->add('casualties2', 'integer',
                array('label'=>'Sorties de '.$name2.' :',
                    'required'  => true))
            ->add('save','submit',array('label'=>'Valider'))
            ->getForm();
	$form->handleRequest($request);
	if ($form->isValid()) {
            $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                    ->findOneById($game->getEdition());
            $strategy = RankingStrategyFabric::getByName($editionObj->getRankingStrategy());
            $points1 = 0;
            $points2 = 0;
            $strategy->computePoints($points1, $points2, $game->getTd1(), $game->getTd2(),
                    $game->getCasualties1(), $game->getCasualties2() );
            $game->setPoints1($points1);
            $game->setPoints2($points2);
            $game->setStatus('resume');
            $em->flush();
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
	}
	return $this->render('FantasyFootballTournamentAdminBundle:Game:resume.html.twig', array(
				'form' => $form->createView(),
                                'game' => $game) );    
    }

    public function modifyAction()
    {
        return array(
                // ...
            );    }

    public function viewAction()
    {
        return array(
                // ...
            );    }

}
