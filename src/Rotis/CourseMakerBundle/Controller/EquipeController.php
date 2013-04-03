<?php
namespace Rotis\CourseMakerBundle\Controller;


use Rotis\CourseMakerBundle\Entity\Joueur;
use Rotis\CourseMakerBundle\Entity\Equipe;
use Rotis\CourseMakerBundle\Entity\Tarif;
use Rotis\CourseMakerBundle\Form\Model\PlayerAddition;
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
       if (true === $this->get('security.context')->isGranted('ROLE_USER')) {
           return $this->redirect($this->generateUrl('accueil'));
       }

       $form = $this->createForm(
        new RegistrationType(),
        new Registration()
    );

        return $this->render(
            'RotisCourseMakerBundle:Equipe:register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function editAction($id)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe');
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new PlayerAdditionType(), new PlayerAddition());
        $equipe= $repository->find($id);
        $nombre = count($equipe->getJoueurs());
        $tarifrepo = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Tarif');
        $tarifs = $tarifrepo->findTarifByIdCourse($equipe->getCourse()->getId());
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (! method_exists($this->get('security.context')->getToken()->getUser(),'getId')
            || $this->get('security.context')->getToken()->getUser()->getId() != $id))
        {
            return $this->redirect($this->generateUrl('accueil'));
        }

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid())
            {
                    $factory = $this->get('security.encoder_factory');
                    $registration = $form->getData();
                    $user = $this->get('security.context')->getToken()->getUser();
                    $joueur = $registration->getJoueur();
                    $joueur->setPapiersOk(false);
                    $joueur->setPaiementOk(false);
                    $tarif = $tarifrepo->findTarifByIdCourse($user->getCourse()->getId());
                    if (0 == $tarif[0]->getPrix())
                    {
                        $joueur->setPaiementOk(true);
                    }
                    $repository = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('RotisCourseMakerBundle:Equipe');

                    $joueur->setEquipe($user);
                    $user->addJoueur($joueur);
                    $em->persist($joueur);
                    $em->persist($user);
                    $em->flush();
                    $this->get('session')->setFlash(
                        'notice',
                        'Ajout réussi!'
                    );
                $message = \Swift_Message::newInstance()
                    ->setSubject('Confirmation d\'inscription à CoursesMaker')
                    ->setFrom('courses@24heures.org')
                    ->setTo($joueur->getEmail())
                    ->setBody(
                        $this->renderView(
                            'RotisCourseMakerBundle:Equipe:mail.html.twig',
                            array('prenom' => $joueur->getPrenom(),'nom' => $joueur->getNom(),'username' => $user->getUsername(),'password' => "0")
                        )
                    );
                $this->get('mailer')->send($message);
                    return $this->redirect($this->generateUrl('accueil'));
                }
        }

        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('tarifs' => $tarifs,'nombre' => $nombre,'equipe' => $equipe,'form' => $form->createView()));
    }

    public function createAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new RegistrationType(), new Registration());
        if ($this->getRequest()->getMethod() == 'POST') {
        $form->bind($this->getRequest());
        if ($form->isValid())
        {
            $factory = $this->get('security.encoder_factory');
            $registration = $form->getData();
            $user = $registration->getUser();
            $joueur = $registration->getJoueur();
            $joueur->setPapiersOk(false)
                ->setPaiementOk(false);
            $tarifrepo = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Tarif');
            $tarif = $tarifrepo->findTarifByIdCourse($user->getCourse()->getId());
            if (0 == $tarif[0]->getPrix())
            {
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
                'Equipe créée!'
            );
            $message = \Swift_Message::newInstance()
                ->setSubject('Confirmation d\'inscription à CoursesMaker')
                ->setFrom('courses@24heures.org')
                ->setTo($joueur->getEmail())
                ->setBody(
                    $this->renderView(
                        'RotisCourseMakerBundle:Equipe:mail.html.twig',
                        array('prenom' => $joueur->getPrenom(),'nom' => $joueur->getNom(),'username' => $user->getUsername(),'password' => $plainPassword)
                    )
                );
            $this->get('mailer')->send($message);
            return $this->redirect($this->generateUrl('accueil'));
        }
        }
        return $this->render('RotisCourseMakerBundle:Equipe:register.html.twig', array('form' => $form->createView()));
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
            && (! method_exists($this->get('security.context')->getToken()->getUser(),'getId')
                || $this->get('security.context')->getToken()->getUser()->getId() != $id))
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
                return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig',array('tarifs' => $tarifs,'nombre' => $nombre,'equipe' => $equipe,'form' => $form->createView()));

        }

    public function listeAction($name)
    {
        if ( $name === "equipe" )
        {
                $repository = $this->getDoctrine()
                           ->getManager()
                           ->getRepository('RotisCourseMakerBundle:Equipe');
                $form = $this->createForm(new RechercheType());
        $listeEquipes = $repository->findAll();
        return $this->render('RotisCourseMakerBundle:Equipe:control_equipe.html.twig',array('name' => $name,'equipes' => $listeEquipes, 'form' => $form->createView()));
        }
        elseif ( $name === "course" )
        {

            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Course');


            $listeCourses = $repository->findAll();
            return $this->render('RotisCourseMakerBundle:Course:control_course.html.twig',array('name' => $name,'courses' => $listeCourses));

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
        if (count($listeEquipes) > 0)
        {
            return $this->render('RotisCourseMakerBundle:Equipe:equipe_par_course.html.twig', array('existence' => true,'equipes' => $listeEquipes, 'course' => $nomCourse));
        }
        else
        {
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
            new EditionType(),
            $equipe
        );
        return $this->render('RotisCourseMakerBundle:Equipe:admin_edit.html.twig', array('equipe' => $equipe, 'form' => $form->createView()));
    }

    public function updateAction($id)
    {
        $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('RotisCourseMakerBundle:Equipe');
        $em = $this->getDoctrine()->getEntityManager();
        $olduser = $repository->find($id);
        $form = $this->createForm(new EditionType(), $olduser );
        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid())
            {
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

    public function switchValAction($id,$etat,$objet)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Joueur');
        $em = $this->getDoctrine()->getEntityManager();
        $joueur = $repository->find($id);
        $equipe = $joueur->getEquipe();
        $nombre = count($equipe->getJoueurs());
        $tarifrepo = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Tarif');
        $tarifs = $tarifrepo->findTarifByIdCourse($equipe->getCourse()->getId());
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')
            && true === $this->get('security.context')->isGranted('ROLE_USER')
            && (! method_exists($this->get('security.context')->getToken()->getUser(),'getId')
                || $this->get('security.context')->getToken()->getUser()->getId() != $id))
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
        $form = $this->createForm(new PlayerAdditionType(), new PlayerAddition());
        if ('certificat' === $objet)
        {
            if (true == $etat) //Si l'on a true, cela signifie que le certificat a été validé -> on souhaite annuler la validation
            {
                $joueur->setPapiersOk(false);
            }
            if (false == $etat)
            {
                $joueur->setPapiersOk(true);
            }
        }
        elseif('paiement' === $objet)
        {

            if (true == $etat) //Si l'on a true, cela signifie que le paiement a été validé -> on souhaite annuler la validation
            {
                $joueur->setPaiementOk(false);
            }
            if (false == $etat)
            {
                $joueur->setPaiementOk(true);
            }
        }
        $em->merge($joueur);
        $em->flush();
        $this->get('session')->setFlash(
            'notice',
            'Action effectuée'
        );
        return $this->render('RotisCourseMakerBundle:Equipe:edit_equipe.html.twig', array('tarifs' => $tarifs,'nombre' => $nombre,'equipe' => $equipe,'form' => $form->createView()));
    }
}