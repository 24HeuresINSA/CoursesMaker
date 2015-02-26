<?php
namespace Rotis\CourseMakerBundle\Controller;


use Rotis\CourseMakerBundle\Entity\Joueur;
use Rotis\CourseMakerBundle\Entity\Equipe;
use Rotis\CourseMakerBundle\Entity\Tarif;

use Rotis\CourseMakerBundle\Form\Model\PlayerAddition;
use Rotis\CourseMakerBundle\Form\Type\PlayerAdditionType;

use Rotis\CourseMakerBundle\Form\Type\RegistrationType;
use Rotis\CourseMakerBundle\Form\Model\Registration;

use Rotis\CourseMakerBundle\Form\Type\AdminEditType;
use Rotis\CourseMakerBundle\Form\Type\EditionType;
use Rotis\CourseMakerBundle\Form\Type\RechercheType;

use Rotis\CourseMakerBundle\Form\Type\OptionalDataType;
use Rotis\CourseMakerBundle\Form\Model\OptionalData;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotNull;

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
        $tarifs = $tarifrepo->findTarifByCourseCate($equipe->getCourse()->getId(),$equipe->getCategorie()->getId());
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
                $joueur->setPapiersOk(false);
                $joueur->setPaiementOk(false)
                    ->setTailleTshirt('NA');
                $tarif = $tarifrepo->findTarifByCourseCate($equipe->getCourse()->getId(),$equipe->getCategorie()->getId());
                if (0 == $tarif[0]->getPrix()) {
                    $joueur->setPaiementOk(true);
                }
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
            if ((!$joueur->getPapiersOk()) || (!$joueur->getPaiementOk()))
            {
                $validable = false;
            }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('validable' => $validable, 'tarifs' => $tarifs, 'nombre' => $nombre, 'equipe' => $equipe, 'form' => $form->createView()));
    }

    public function eraseAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
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
            $equipesValides = $repository->findEquipesValides();
            $this->get('session')->setFlash(
                'notice',
                'Suppression de l\'équipe réalisée'
            );
            return $this->render('RotisCourseMakerBundle:Equipe:control_equipe.html.twig', array('nbEquipesValides' => $equipesValides, 'nbTotalEquipes' => $totalEquipes, 'name' => "equipe", 'equipes' => $listeEquipes, 'form' => $form->createView()));
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
                    ->setPaiementOk(false)
                    ->setTailleTshirt('NA');
                $tarifrepo = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('RotisCourseMakerBundle:Tarif');
                $tarif = $tarifrepo->findTarifByCourseCate($user->getCourse()->getId(),$user->getCategorie()->getId());

                if (0 == $tarif[0]->getPrix()) {
                    $joueur->setPaiementOk(true);
                }
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
        $tarifs = $tarifrepo->findTarifByCourseCate($equipe->getCourse()->getId(),$equipe->getCategorie()->getId());
        $em = $this->get('doctrine.orm.entity_manager');
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
            'tarifs' => $tarifs,
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
                return new Response('error');
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

    public function listeParCourseAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe');
        $listeEquipes = $repository->findByJoinedCourseId($id);
        $repocourse = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Course');
        $course = $repocourse->find($id);
        if (count($listeEquipes) > 0) {
            return $this->render('RotisCourseMakerBundle:Equipe:equipe_par_course.html.twig', array('existence' => true, 'equipes' => $listeEquipes, 'course' => $course));
        } else {
            return $this->render('RotisCourseMakerBundle:Equipe:equipe_par_course.html.twig', array('existence' => false, 'course' => $course));
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
        $tarifs = $tarifrepo->findTarifByCourseCate($equipe->getCourse()->getId(),$equipe->getCategorie()->getId());
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (!method_exists($this->get('security.context')->getToken()->getUser(), 'getId')
                || $this->get('security.context')->getToken()->getUser()->getId() != $id)
        ) {
            return $this->redirect($this->generateUrl('accueil'));
        }
        $form = $this->createForm(new PlayerAdditionType(), new PlayerAddition());
        if ('certificat' === $objet) {
            if (true == $etat) //Si l'on a true, cela signifie que le certificat a été validé -> on souhaite annuler la validation
            {
                $lejoueur->setPapiersOk(false);
            }
            if (false == $etat) {
                $lejoueur->setPapiersOk(true);
            }
        } elseif ('paiement' === $objet) {

            if (true == $etat) //Si l'on a true, cela signifie que le paiement a été validé -> on souhaite annuler la validation
            {
                $lejoueur->setPaiementOk(false);
            }
            if (false == $etat) {
                $lejoueur->setPaiementOk(true);
            }
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
            if ((!$joueur->getPapiersOk()) || (!$joueur->getPaiementOk()))
            {
                $validable = false;
            }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('validable' => $validable, 'tarifs' => $tarifs, 'nombre' => $nombre, 'equipe' => $equipe, 'form' => $form->createView()));
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
        $tarifs = $tarifrepo->findTarifByCourseCate($equipe->getCourse()->getId(),$equipe->getCategorie()->getId());
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (!method_exists($this->get('security.context')->getToken()->getUser(), 'getId')
                || $this->get('security.context')->getToken()->getUser()->getId() != $id)
        ) {
            return $this->redirect($this->generateUrl('accueil'));
          }
        $form = $this->createForm(new PlayerAdditionType(), new PlayerAddition());
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
            if ((!$joueur->getPapiersOk()) || (!$joueur->getPaiementOk()))
            {
                $validable = false;
            }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('validable' => $validable, 'tarifs' => $tarifs, 'nombre' => $nombre, 'equipe' => $equipe, 'form' => $form->createView()));

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
        return $this->render('RotisCourseMakerBundle:CourseMaker:accueil.html.twig');

    }
}