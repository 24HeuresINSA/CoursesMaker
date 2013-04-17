<?php
namespace Rotis\CourseMakerBundle\Controller;


use Rotis\CourseMakerBundle\Entity\Joueur;
use Rotis\CourseMakerBundle\Entity\Equipe;
use Rotis\CourseMakerBundle\Entity\Tarif;
use Rotis\CourseMakerBundle\Form\Model\PlayerAddition;
use Rotis\CourseMakerBundle\Form\Type\AdminEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Rotis\CourseMakerBundle\Form\Type\RegistrationType;
use Rotis\CourseMakerBundle\Form\Model\Registration;
use Rotis\CourseMakerBundle\Form\Type\PlayerAdditionType;
use Rotis\CourseMakerBundle\Form\Type\EditionType;
use Rotis\CourseMakerBundle\Form\Type\RechercheType;
use Symfony\Component\Validator\Constraints\NotNull;

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
        $tarifs = $tarifrepo->findTarifByIdCourse($equipe->getCourse()->getId());
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
                $tarif = $tarifrepo->findTarifByIdCourse($equipe->getCourse()->getId());
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
                $message = \Swift_Message::newInstance()
                    ->setSubject('Confirmation d\'inscription à courses.24heures.org')
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
                $tarif = $tarifrepo->findTarifByIdCourse($user->getCourse()->getId());
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
                $message = \Swift_Message::newInstance()
                    ->setSubject('Confirmation d\'inscription à CoursesMaker')
                    ->setFrom('courses@24heures.org')
                    ->setTo($joueur->getEmail())
                    ->setBody(
                        $this->renderView(
                            'RotisCourseMakerBundle:Equipe:mail.html.twig',
                            array('joueur' => $joueur, 'username' => $user->getUsername(), 'password' => $plainPassword)
                        )
                    );
                $this->get('mailer')->send($message);
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
        $tarifs = $tarifrepo->findTarifByIdCourse($equipe->getCourse()->getId());
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
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('validable' => $validable,'tarifs' => $tarifs, 'nombre' => $nombre, 'equipe' => $equipe, 'form' => $form->createView()));

    }

    public function listeAction($name)
    {
        if ($name === "equipe") {
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Equipe');
            $form = $this->createForm(new RechercheType());
            $listeEquipes = $repository->findAll();
            return $this->render('RotisCourseMakerBundle:Equipe:control_equipe.html.twig', array('name' => $name, 'equipes' => $listeEquipes, 'form' => $form->createView()));
        } elseif ($name === "course") {

            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Course');


            $listeCourses = $repository->findAll();
            return $this->render('RotisCourseMakerBundle:Course:control_course.html.twig', array('name' => $name, 'courses' => $listeCourses));

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

    public function editInfosAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe');
        $equipe = $repository->find($id);
        $form = $this->createForm(
            new AdminEditType(),$equipe);
        return $this->render('RotisCourseMakerBundle:Equipe:admin_edit.html.twig', array('equipe' => $equipe, 'form' => $form->createView()));
    }

    public function updateAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe');
        $em = $this->getDoctrine()->getEntityManager();
        $olduser = $repository->find($id);
        $form = $this->createForm(new AdminEditType(), $olduser);
        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $factory = $this->get('security.encoder_factory');
                $registration = $form->getData();
                $user = $registration->getUser();
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                //$user->addJoueur($olduser->getJoueurs());
                $em->merge($user);
                $em->flush();
                $this->get('session')->setFlash(
                    'notice',
                    'Equipe modifiée!'
                );
                return $this->redirect($this->generateUrl('accueil'));
            }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:admin_edit.html.twig', array('equipe' => $olduser, 'form' => $form->createView()));
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
        $tarifs = $tarifrepo->findTarifByIdCourse($equipe->getCourse()->getId());
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
        $tarifs = $tarifrepo->findTarifByIdCourse($equipe->getCourse()->getId());
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
        $em = $this->getDoctrine()->getEntityManager();
        $equipe = $em->getRepository('RotisCourseMakerBundle:Equipe')->find($id);
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
        }
        $em->merge($equipe);
        $em->flush();
        $this->get('session')->setFlash(
            'notice',
            'Action effectuée'
        );
        return $this->render('RotisCourseMakerBundle:CourseMaker:accueil.html.twig');

    }
}