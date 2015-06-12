<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentAdminBundle\Util\DataUpdater;
use Symfony\Component\HttpFoundation\Request;
use FantasyFootball\TournamentCoreBundle\Entity\Coach;
use FantasyFootball\TournamentCoreBundle\Entity\CoachTeam;

class CoachTeamController extends Controller
{
    /*
    protected function createCustomForm(Coach $coach,array $races)
    {
        $coachChoice = array();
        foreach($races as $key=>$obj){
            $raceChoice[$key]=$obj->nom_fr;
        }
        $form = $this->createFormBuilder($coach)
                    ->add('teamName', 'text',array('label'=>'Nom de l\'équipe :'))
                    ->add('name', 'text',array('label'=>'Nom :'))
                    ->add('race', 'choice',
                        array('label'=>'Race :',
                            'choices'   => $raceChoice,
                            'required'  => true))
                    ->add('emailAddress', 'email',array('label'=>'Courriel :'))
                    ->add('nafNumber', 'integer',array('label'=>'Numéro NAF :'))
                    ->add('ready', 'checkbox',array(
                        'label' => 'Coach prêt ?',
                        'required' => false))
                    ->add('save','submit',array('label'=>'Valider'))
                    ->getForm();
        return $form;
    }*/
    
    public function AddAction(Request $request,$edition)
    {
        $coachTeam = new CoachTeam();
        $form = $this->createFormBuilder($coachTeam)->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            //$data->insertCoach($coach);
            //return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_homepage'));
        }
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Add.html.twig', array(
            'form' => $form->createView()
            ));    }

    public function ModifyAction()
    {
        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Modify.html.twig', array(
                // ...
            ));    }
  
    public function DeleteAction(Request $request,$coachTeamId)
    {      
        $em = $this->getDoctrine()->getManager();
        $coachTeam = $em->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')->find($coachTeamId);

        if (!$coachTeam) {
            throw $this->createNotFoundException(
                'Aucun coachTeam trouvé pour cet id : '.$coachTeamId
            );
        }
        
        $form = $this->createFormBuilder($coachTeam)
                	->add('delete','submit')
			->getForm();

        $form->handleRequest($request);
	//$coachTeam->setId($coachTeamId);
	if ($form->isValid()) {
            $em->remove($coachTeam);
            $em->flush();
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_homepage'));
	}
	return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Delete.html.twig', array(
            'coachTeam'=>$coachTeam,
            'form' => $form->createView()
	));
    }

    protected function convert($filename, $delimiter = ',') 
    {
        if(!file_exists($filename) || !is_readable($filename)) {
            return FALSE;
        }
        
        $header = NULL;
        $data = array();
        
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if(!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }        
            
    public function LoadAction(Request $request,$edition)
    {
        $loadParameters = array();
         $form = $this->createFormBuilder($loadParameters)
                    ->add('attachment', 'file',array('label'=>'Fichier :'))
                    ->add('edition', 'integer',array('label'=>'Edition :','data'=>$edition))
                    ->add('save','submit',array('label'=>'Valider'))
                    ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form['attachment']->getData();
            $edition = $form['edition']->getData();
            // générer un nom aléatoire et essayer de deviner l'extension (plus sécurisé)
            $extension = $file->guessExtension();
            if ('txt' === $extension) {
                $filename = rand(1, 99999).'.'.$extension;
                $file->move('./', $filename);
                // Getting the CSV from filesystem
                $fileName = './'.$filename;
                $dataArray = $this->convert($fileName, ',');
                $coachs = array();
                foreach($dataArray as $row){
                    $coachTeam = new CoachTeam();
                    $coachTeam->setName($row['coach_team_name']);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($coachTeam);
                    $em->flush();
                    $i = 1;
                    while( array_key_exists('coach_'.$i.'_name', $row) ){
                        $coach = new Coach();
                        $coach->setCoachTeam($coachTeam->getId());
                        $coach->setName($row['coach_'.$i.'_name']);
                        $coach->setNafNumber($row['coach_'.$i.'_naf']);
                        $coach->setEmailAddress($row['email']);
                        $coach->setEdition($edition);
                        $i ++;
                        $coachs[] = $coach;
                    }
                }
                $conf = $this->get('fantasy_football_core_db_conf');
                $data = new DataUpdater($conf);
                $data->insertCoachs($coachs);
            }
            
            return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_homepage'));
        }

        return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:Load.html.twig', array(
                'form' => $form->createView(),
                'edition' => $edition
            ));    }
            
    public function viewAction($coachTeamId)
    {
        
	$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataUpdater($conf);
    	//$coachTeam = $data->getCoachTeamById($coachTeamId);
        $coachTeam = $this->getDoctrine()
        ->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')
        ->find($coachTeamId);
    	$matchs = $data->getMatchsByCoachTeam($coachTeamId);
	return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:View.html.twig', array(
  		'coachTeam'=>$coachTeam,'matchs'=>$matchs));
    }
      
    public function ListAction($edition)
    {
        $coachTeams = $this->getDoctrine()->getRepository('FantasyFootballTournamentCoreBundle:CoachTeam')->findByEditionJoined($edition);
	return $this->render('FantasyFootballTournamentAdminBundle:CoachTeam:List.html.twig', array(
      	'coachTeams' => $coachTeams, 'edition'=>$edition));
    }
}
?>