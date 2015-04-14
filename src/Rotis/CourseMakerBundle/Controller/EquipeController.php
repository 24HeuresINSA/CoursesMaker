<?php
namespace Rotis\CourseMakerBundle\Controller;


use Doctrine\ORM\UnexpectedResultException;
use mageekguy\atoum\tools\diffs\variable;
use Rotis\CourseMakerBundle\Entity\Carte;
use Rotis\CourseMakerBundle\Entity\Certif;
use Rotis\CourseMakerBundle\Entity\Equipe;
use Rotis\CourseMakerBundle\Entity\Log;
use Rotis\CourseMakerBundle\Entity\Paiement;

use Rotis\CourseMakerBundle\Form\Model\PlayerAddition;
use Rotis\CourseMakerBundle\Form\Type\PlayerAdditionType;

use Rotis\CourseMakerBundle\Form\Type\RegistrationType;
use Rotis\CourseMakerBundle\Form\Model\Registration;

use Rotis\CourseMakerBundle\Form\Type\AdminEditType;
use Rotis\CourseMakerBundle\Form\Type\RechercheType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use JMS\SerializerBundle\JMSSerializerBundle;

class EquipeController extends Controller
{
    public function registerAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        if (true === $this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('accueil'));
        }

        $form = $this->createForm(
            new RegistrationType(),
            new Registration()
        );

        $repocourse = $em->getRepository('RotisCourseMakerBundle:Course');

        $categories = $em->getRepository('RotisCourseMakerBundle:Categorie')->findAll();

        $coursesForCategories = array();

        foreach ($categories as $categorie) {
            $coursesForCategories[$categorie->getId()] = $categorie->getCourses()->map(function($p) {
                return $p->getId();
            })->toArray();
        }

        return $this->render(
            'RotisCourseMakerBundle:Equipe:register.html.twig', array(
                'form' => $form->createView(),
                'categorie_course' => json_encode($coursesForCategories)
            )
        );
    }

    public function editAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe');
        $em = $this->getDoctrine()->getEntityManager();
        // forms creation
        $form = $this->createForm(new PlayerAdditionType(), new PlayerAddition());

        $equipe = $repository->find($id);
        if ($equipe->getValide())
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
        $nombre = count($equipe->getJoueurs());
        $tarifrepo = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Tarif');
        $tarif = $tarifrepo->findTarifByCourseCate($equipe->getCourse(),$equipe->getCategorie());
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (!method_exists($this->get('security.context')->getToken()->getUser(), 'getId')
                || $this->get('security.context')->getToken()->getUser()->getId() != $id)
        ) {
            return $this->redirect($this->generateUrl('accueil'));
        }

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $factory = $this->get('security.encoder_factory');
                $registration = $form->getData();
                $joueur = $registration->getJoueur();
                $joueur->setPapiersOk(false)
                    ->setCarteOk(false)
                    ->setPaiementOk(false);
                $joueur->setTailleTshirt('NA');
                $tarif = $tarifrepo->findTarifByCourseCate($equipe->getCourse(),$equipe->getCategorie());
                $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('RotisCourseMakerBundle:Equipe');

                $joueur->setEquipe($equipe);
                $equipe->addJoueur($joueur);
                $em->persist($joueur);
                $em->persist($equipe);
                $em->flush();
                $this->get('session')->setFlash(
                    'notice',
                    'Ajout réussi!'
                );
                $message = \Swift_Message::newInstance();
                $message->setSubject('Confirmation d\'inscription à courses.24heures.org')
                    ->setFrom('courses@24heures.org')
                    ->setTo($joueur->getEmail())
                    ->setBody(
                        $this->renderView(
                            'RotisCourseMakerBundle:Equipe:mail.html.twig',
                            array('joueur' => $joueur, 'username' => $equipe->getUsername(), 'password' => "0")
                        )
                    );
                $this->get('mailer')->send($message);
                return $this->redirect($this->generateUrl('accueil'));
            }
        }
        $tousjoueurs = $equipe->getJoueurs();
        $validable = true;
        foreach($tousjoueurs as $joueur){
            if ((!$joueur->getPapiersOk()) || ($joueur->getPaiements()->count() == 0))
            {
                $validable = false;
            }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('validable' => $validable, 'tarif' => $tarif, 'nombre' => $nombre, 'equipe' => $equipe, 'form' => $form->createView()));
    }

    public function eraseAction($id)
    {
        $repository = $this->getDoctrine()
            ->getRepository('RotisCourseMakerBundle:Equipe');

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') || (NULL == ($repository->find($id))))
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
        else
        {
            $form = $this->createForm(new RechercheType());

            $em = $this->get('doctrine.orm.entity_manager');
            $equipe = $repository->find($id);
            if ($equipe->getValide())
            {
                return $this->redirect($this->generateUrl('accueil'));
            }
            foreach($equipe->getJoueurs() as $joueur)
            {
                $equipe->removeJoueur($joueur);
                $em->remove($joueur);
            }
            $em->merge($equipe);
            $em->remove($equipe);
            $em->flush();
            $listeEquipes = $repository->findAll();
            $totalEquipes = count($listeEquipes);
            $equipesValides = $repository->countEquipesValides();
            $this->get('session')->setFlash(
                'notice',
                'Suppression de l\'équipe réalisée'
            );
            return $this->render('RotisCourseMakerBundle:Admin:equipes.html.twig', array('nbEquipesValides' => $equipesValides, 'nbTotalEquipes' => $totalEquipes, 'name' => "equipe", 'equipes' => $listeEquipes, 'form' => $form->createView()));
        }
    }

    public function createAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new RegistrationType(), new Registration());
        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $factory = $this->get('security.encoder_factory');
                $registration = $form->getData();
                $user = $registration->getUser();
                $joueur = $registration->getJoueur();
                $joueur->setPapiersOk(false)
                    ->setCarteOk(false)
                    ->setTailleTshirt('NA');
                $tarifrepo = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('RotisCourseMakerBundle:Tarif');
                $tarif = $tarifrepo->findTarifByCourseCate($user->getCourse()->getId(),$user->getCategorie()->getId());

                $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('RotisCourseMakerBundle:Equipe');
                $encoder = $factory->getEncoder($user);
                $plainPassword = $user->getPassword();
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $joueur->setEquipe($user);
                $user->addJoueur($joueur);
                $em->persist($joueur);
                $em->persist($user);
                $em->flush();
                $this->get('session')->setFlash(
                    'notice',
                    'Votre équipe à bien été créée. Rendez vous dans l\'onglet Connexion pour avoir accès à toutes vos informations'
                );
                if($joueur->getEmail())
                {
                    $message = \Swift_Message::newInstance();
                $message->setSubject('Confirmation d\'inscription à CoursesMaker')
                    ->setFrom('courses@24heures.org')
                    ->setTo($joueur->getEmail())
                    ->setBody(
                        $this->renderView(
                            'RotisCourseMakerBundle:Equipe:mail.html.twig',
                            array('joueur' => $joueur, 'username' => $user->getUsername(), 'password' => $plainPassword)
                        )
                    );
                $this->get('mailer')->send($message);
                }
                return $this->redirect($this->generateUrl('accueil'));
            }
        }
        $categories = $em->getRepository('RotisCourseMakerBundle:Categorie')->findAll();

        $coursesForCategories = array();

        foreach ($categories as $categorie) {
            $coursesForCategories[$categorie->getId()] = $categorie->getCourses()->map(function($p) {
                return $p->getId();
            })->toArray();
        }
        return $this->render('RotisCourseMakerBundle:Equipe:register.html.twig', array('form' => $form->createView(),'categorie_course' => json_encode($coursesForCategories)));
    }

    public function edit_equipeAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe');
        $equipe = $repository->find($id);
        $nombre = count($equipe->getJoueurs());
        $tarifrepo = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Tarif');
        $tarif = $tarifrepo->findTarifByCourseCate($equipe->getCourse(),$equipe->getCategorie());
        $em = $this->get('doctrine.orm.entity_manager');

        // form creations
        $form = $this->createForm(new PlayerAdditionType(), new PlayerAddition());

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (!method_exists($this->get('security.context')->getToken()->getUser(), 'getId')
                || $this->get('security.context')->getToken()->getUser()->getId() != $id)
        ) {
            return $this->redirect($this->generateUrl('accueil'));
        }
        $tousjoueurs = $equipe->getJoueurs();
        $validable = true;
        foreach($tousjoueurs as $joueur){
            if ((!$joueur->getPapiersOk()) || (!$joueur->getPaiementOk()))
            {
                $validable = false;
            }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array(
            'validable' => $validable,
            'tarif' => $tarif,
            'nombre' => $nombre,
            'equipe' => $equipe,
            'form' => $form->createView(),
        ));

    }

    public function setOptionalDataAction(Request $request,$id)
    {
        if($request->getMethod() == 'POST')
        {
            if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (!method_exists($this->get('security.context')->getToken()->getUser(), 'getId')
                || $this->get('security.context')->getToken()->getUser()->getId() != $id))
            {
                throw new AccessDeniedException();
            }
            $content = json_decode($request->getContent());
            if(is_object($content) && property_exists($content, 'id') && is_numeric($content->id) && $content->id > 0)
            {
                $validator = $this->get('validator');
                $idjoueur = $content->id;
                $joueur = $this->getDoctrine()->getManager()->getRepository('RotisCourseMakerBundle:Joueur')->find($idjoueur);
                if(property_exists($content, 'email'))
                {
                    $email = $content->email;
                    //var_dump('mail');die;
                    $joueur->setEmail($email);
                    if(count($validator->validate($joueur)) > 0)
                    {
                        $joueur->setEmail(null);
                    }
                    else
                    {
                        $message = \Swift_Message::newInstance();
                        $message->setSubject('Confirmation d\'inscription à CoursesMaker')
                            ->setFrom('courses@24heures.org')
                            ->setTo($email)
                            ->setBody(
                                $this->renderView(
                                    'RotisCourseMakerBundle:Equipe:mail.html.twig',
                                    array('joueur' => $joueur, 'username' => $joueur->getEquipe()->getUsername(), 'password' => null)
                                )
                            );
                        $this->get('mailer')->send($message);
                    }
                }
                //var_dump('nomail');die;
                if(property_exists($content, 'telephone'))
                {
                    $telephone = $content->telephone;
                    $joueur->setTelephone($telephone);
                    if(count($validator->validate($joueur)) > 0)
                    {
                        $joueur->setTelephone(null);
                    }
                }
                $this->getDoctrine()->getManager()->flush();
                $serializer = $this->get('jms_serializer');
                $jsoncontent = $serializer->serialize($joueur, 'json');
                return new Response($jsoncontent);
            }
            throw new \Exception('Something went wrong!');
        }
    }

    public function updateAction($id)
    {
        $user = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Equipe')->find($id);
        $form = $this->createForm(new AdminEditType(), $user);
        if($this->getRequest()->getMethod() == 'POST')
        {
            $form->bind($this->getRequest());
            if($form->isValid())
            {
                if($form->getData()->getPassword() !== $user->getPassword()){
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                    $user->setPassword($password);
                }
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->setFlash(
                    'notice',
                    'Equipe modifiée!'
                );
                return $this->redirect($this->generateUrl('account', array('id' => $id)));
            }
        }
        $categories = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Categorie')->findAll();

        $coursesForCategories = array();

        foreach ($categories as $categorie) {
            $coursesForCategories[$categorie->getId()] = $categorie->getCourses()->map(function($p) {
                return $p->getId();
            })->toArray();
        }

        return $this->render('RotisCourseMakerBundle:Equipe:admin_edit.html.twig',array(
            'equipe' => $user,
            'categorie_course' => json_encode($coursesForCategories),
            'form' => $form->createView(),
        ));
    }

    public function switchValAction($id, $etat, $objet)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Joueur');
        $em = $this->getDoctrine()->getEntityManager();
        $lejoueur = $repository->find($id);
        $equipe = $lejoueur->getEquipe();
        if ($equipe->getValide())
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
        $nombre = count($equipe->getJoueurs());
        $tarifrepo = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Tarif');
        $tarif = $tarifrepo->findTarifByCourseCate($equipe->getCourse(),$equipe->getCategorie());
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (!method_exists($this->get('security.context')->getToken()->getUser(), 'getId')
                || $this->get('security.context')->getToken()->getUser()->getId() != $id)
        ) {
            return $this->redirect($this->generateUrl('accueil'));
        }
        // form creations
        $form = $this->createForm(new PlayerAdditionType(), new PlayerAddition());

        //
        if ('certificat' === $objet) {
            if (true == $etat) //Si l'on a true, cela signifie que le certificat a été validé -> on souhaite annuler la validation
            {
                $lejoueur->setPapiersOk(false);
            } elseif (false == $etat) {
                $lejoueur->setPapiersOk(true);
            }
        } elseif ('paiement' === $objet) {

            if (true == $etat) //Si l'on a true, cela signifie que le paiement a été validé -> on souhaite annuler la validation
            {
                $lejoueur->setPaiementOk(false);
            } elseif (false == $etat) {
                $lejoueur->setPaiementOk(true);
            }
        } elseif ('carte' === $objet) {
            if (true == $etat) //Si l'on a true, cela signifie que la carte a été validée -> on souhaite annuler la validation
            {
                $lejoueur->setCarteOk(false);
            } elseif (false == $etat) {
                $lejoueur->setCarteOk(true);
            }
        } else {
            throw $this->createNotFoundException('Objet non trouvé');
        }
        $em->merge($lejoueur);
        $em->flush();
        $this->get('session')->setFlash(
            'notice',
            'Action effectuée'
        );
        $tousjoueurs = $equipe->getJoueurs();
        $validable = true;
        foreach($tousjoueurs as $joueur){
            if ((!$joueur->getPapiersOk()) || ($joueur->getPaiements()->count() == 0))
            {
                $validable = false;
            }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('validable' => $validable, 'tarif' => $tarif, 'nombre' => $nombre, 'equipe' => $equipe, 'form' => $form->createView()));
    }

    public function remove_joueurAction($id, $idjoueur)
    {
        $equipe = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe')->find($id);
        if ($equipe->getValide())
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
        $joueur = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Joueur')->find($idjoueur);
        $tarifrepo = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Tarif');
        $em = $this->getDoctrine()->getEntityManager();
        $tarif = $tarifrepo->findTarifByCourseCate($equipe->getCourse(),$equipe->getCategorie());
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (!method_exists($this->get('security.context')->getToken()->getUser(), 'getId')
                || $this->get('security.context')->getToken()->getUser()->getId() != $id)
        ) {
            return $this->redirect($this->generateUrl('accueil'));
          }

        // forms creation
        $form = $this->createForm(new PlayerAdditionType(), new PlayerAddition());

        //

        if (true == $equipe->getJoueurs()->contains($joueur))
        {
            $equipe->removeJoueur($joueur);
            $em->remove($joueur);
            $em->merge($equipe);
            $em->flush();
            $this->get('session')->setFlash(
                'notice',
                'Coureur supprimé'
            );

        }
        $nombre = count($equipe->getJoueurs());
        $tousjoueurs = $equipe->getJoueurs();
        $validable = true;
        foreach($tousjoueurs as $joueur){
            if ((!$joueur->getPapiersOk()) || ($joueur->getPaiements()->count() == 0))
            {
                $validable = false;
            }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('validable' => $validable, 'tarif' => $tarif, 'nombre' => $nombre, 'equipe' => $equipe, 'form' => $form->createView()));

    }

    public function switchTeamValidAction($id, $status)
    {
        $em = $this->getDoctrine()->getManager();
        $equipe = $em->getRepository('RotisCourseMakerBundle:Equipe')->find($id);
        $course = $equipe->getCourse();
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
        if (true == $status)
        {
            $equipe->setValide(false);
        }
        else
        {
            $equipe->setValide(true);
            $tousjoueurs = $equipe->getJoueurs();
            foreach($tousjoueurs as $joueur)
            {

            $message = \Swift_Message::newInstance();
            $message->setSubject('Validation de l\'inscription à courses.24heures.org')
                ->setFrom('courses@24heures.org')
                ->setTo($joueur->getEmail())
                ->setBody(
                    $this->renderView(
                        'RotisCourseMakerBundle:Equipe:mail_admin_valid.html.twig',
                        array('joueur' => $joueur, 'nomCourse' => $course->getNom(), 'date' => $course->getDatetimeDebut())
                    )
                );
            $this->get('mailer')->send($message);

            }
        }
        $em->flush();
        $this->get('session')->setFlash(
            'notice',
            'Action effectuée'
        );
        return $this->redirect($this->generateUrl('edit', array('id' => $id)));

    }

    public function uploadAction($joueur,$type)
    {
        $owner = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Joueur')->find($joueur);
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN') && (null === $owner || $this->get('security.context')->getToken()->getUser() !== $owner->getEquipe())){
            throw new AccessDeniedException;
        }
        $certif = new Certif();

        $carte = new Carte();


        $em = $this->getDoctrine()->getManager();

        if($this->getRequest()->isMethod('POST')){
            if($type === 'certif'){
                $file = $this->getRequest()->files->get('file');

                if($owner->getCertif()){
                    $old = $owner->getCertif();
                    $owner->setCertif(null);
                    $em->remove($old);
                    $em->flush();
                }
                $certif->setFile($file);
                $certif->setJoueur($owner);
                $em->persist($certif);
                $em->flush();

                $this->get('session')->setFlash(
                    'notice',
                    'Upload réussi'
                );

                $serializer = $this->get('jms_serializer');
                $jsoncontent = $serializer->serialize($owner, 'json');
                return new Response($jsoncontent);
            }

            if($type === 'carte'){
                $file = $this->getRequest()->files->get('file');


                if($owner->getCarte()){
                    $old = $owner->getCarte();
                    $owner->setCarte(null);
                    $em->remove($old);
                    $em->flush();
                }
                $carte->setFile($file);
                $carte->setJoueur($owner);
                $em->persist($carte);
                $em->flush();

                $this->get('session')->setFlash(
                    'notice',
                    'Upload réussi'
                );

                $serializer = $this->get('jms_serializer');
                $jsoncontent = $serializer->serialize($owner, 'json');
                return new Response($jsoncontent);
            }
        }
        throw new AccessDeniedException();
    }

    public function payAction($equipe,$joueurs)
    {
        $arrayJoueurs = explode('-',$joueurs);

        $prix = 0;

        $em = $this->getDoctrine()->getManager();

        $paiement = new Paiement();

        foreach($arrayJoueurs as $id)
        {
            $joueur = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Joueur')->find($id);
            $team = $joueur->getEquipe();
            if($equipe != $team->getId()){
                throw new AccessDeniedException;
            }
            $tarif = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Tarif')->findTarifByCourseCate($team->getCourse(),$team->getCategorie());


            if($joueur->getEtudiant()){
                $prix += $tarif->getPrixEtudiant();
            } else {
                $prix += $tarif->getPrix();
            }
            $joueur->addPaiement($paiement);
        }
        $paiement->setMontant($prix)
            ->setStatut('creating');
        $em->persist($paiement);
        $em->flush();

        $log = $this->createLog($this->getRequest(),'access creer-un-paiement');

        $paiement->addLog($log);


        $em->persist($log);
        $em->flush();

        $link = $this->pay($paiement,$equipe);

        return new Response($link);
    }

    // lancer pour checker un paiement
    public function payCheckAction($equipe,$id)
    {
        $paiement = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Paiement')->find($id);

        $this->checkPaiement($paiement);

        return $this->redirect($this->generateUrl('account',array('id' => $equipe)));
    }

    public function teamCheckAction($equipe)
    {
        $team = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Equipe')->find($equipe);
        $pToCheck = array();
        foreach($team->getJoueurs() as $joueur)
        {
            $paiements = $joueur->getPaiements();
            if(count($this->getDoctrine()->getRepository('RotisCourseMakerBundle:Paiement')->findByStatut($joueur,'finished')) === 0)
            {
                foreach($paiements as $pay){
                    if(!in_array($pay->getId(),$pToCheck)){
                        $pToCheck[] = $pay->getId();
                    }
                }
            }
        }
        foreach($pToCheck as $pId)
        {
            $paiement = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Paiement')->find($pId);
            $this->checkPaiement($paiement);
        }
        return $this->redirect($this->generateUrl('account',array('id' => $equipe)));
    }

    private function createLog(Request $request,$message,$errorCode = null)
    {
        $log = new Log();
        $log->setDate(new \DateTime("now"))
            ->setIp($request->getClientIp())
            ->setNavigateur($request->headers->get('User-Agent'))
            ->setMessage($message)
            ->setSession($request->getSession()->getId())
            ->setErrorCode($errorCode);
        return $log;
    }

    private function payLog(Paiement $paiement,Log $log)
    {
        $em = $this->getDoctrine()->getManager();

        $paiement->addLog($log);
        $em->persist($log);
        $em->flush();
    }

    private function checkPaiement(Paiement $paiement)
    {

        if(! ($this->container->hasParameter('payment.baseUrl') && $this->container->hasParameter('payment.checkUrl') && $this->container->hasParameter('payment.token')) ){
            throw new InvalidConfigurationException;
        }

        $checkUrl = $this->container->getParameter('payment.baseUrl').$this->container->getParameter('payment.checkUrl').'?';
        $token = $this->container->getParameter('payment.token');

        $fields = array(
            'token' => $token,
            'hash' => $paiement->getHash(),
        );

        foreach($fields as $key => $val)
        {
            $checkUrl .= $key. '=' .$val.'&';
        }
        $checkUrl = rtrim($checkUrl, '&');

        //initialisation de session curl
        $cSession = curl_init();
        $options = array(
            //url
            CURLOPT_URL            => $checkUrl,

            CURLOPT_RETURNTRANSFER => true,
        );
        //setting options
        curl_setopt_array( $cSession, $options );

        //Json result au format string
        $answer = json_decode(curl_exec($cSession), true);
        curl_close($cSession);

        // permet de checker le changement de statut ou non
        $oldStatut = $paiement->getStatut();

        if(is_array($answer)){
            if($answer['success']){
                $paiement->setStatut($answer['status']);
                if($paiement->getStatut() === 'finished'){
                    foreach($paiement->getJoueurs() as $joueur)
                    {
                        $joueur->setPaiementOk(true);
                    }
                }
                $log = $this->createLog($this->getRequest(),'check statut');
                $this->payLog($paiement,$log);
            } else{
                $log = $this->createLog($this->getRequest(),'failed check statut');
                $this->payLog($paiement,$log);
            }
        } else{
            $log = $this->createLog($this->getRequest(),'failed request check statut');
            $this->payLog($paiement,$log);
        }
    }

    private function pay(Paiement $paiement, $equipe)
    {
        if(! ($this->container->hasParameter('payment.baseUrl') && $this->container->hasParameter('payment.payUrl') && $this->container->hasParameter('payment.token')) ){
            throw new InvalidConfigurationException;
        }

        $payUrl = $this->container->getParameter('payment.baseUrl').$this->container->getParameter('payment.payUrl');
        $token = $this->container->getParameter('payment.token');

        $backUrl = $this->generateUrl('pay_check',array('id' => $paiement->getId(),'equipe' => $equipe),true);
        // construction du form
        $fields = array(
            'token' => $token,
            'amount' => $paiement->getMontant(),
            'back_url' => $backUrl,
        );

        $postData = '';
        foreach($fields as $key => $val)
        {
            $postData .= $key. '=' .$val.'&';
        }
        $postData = rtrim($postData, '&');


        //initialisation de session curl
        $cSession = curl_init();
        $options = array(
            //url
            CURLOPT_URL            => $payUrl,

            //Post data length
            CURLOPT_POST => count($fields),

            //Post data
            CURLOPT_POSTFIELDS => $postData,

            CURLOPT_RETURNTRANSFER => true,
        );
        //var_dump($payUrl.'?token='.$token.'&amount='.$prix);die;
        //setting options
        curl_setopt_array( $cSession, $options );

        //Json result au format string
        $answer = json_decode(curl_exec($cSession), true);
        curl_close($cSession);

        if(is_array($answer)){
            if($answer['success']){
                // redirection sur link + store data
                $paiement->setLien($answer['link'])
                    ->setStatut('created')
                    ->setHash($answer['hash']);
                $log = $this->createLog($this->getRequest(),'success creer-un-paiement');
                $this->payLog($paiement,$log);
            } else{
                $log = $this->createLog($this->getRequest(),$answer['errorMsg'],$answer['errorCode']);
                $this->payLog($paiement,$log);
            }
        } else {

            // erreur contact site : creer log manuel et return error
            $log = $this->createLog($this->getRequest(),'erreur creer-un-paiement');
            $this->payLog($paiement,$log);
        }
        return $paiement->getLien();
    }
}