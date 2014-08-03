<?php

namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Form\Type\EditionChoiceType;
use Rotis\CourseMakerBundle\Form\Type\RechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function relousAction($edition)
    {
        $coureursNoMail =  $this->getDoctrine()->getManager()->getRepository('RotisCourseMakerBundle:Joueur')->findJWithoutMail();
        $coureursNada = $this->getDoctrine()->getManager()->getRepository('RotisCourseMakerBundle:Joueur')->fidJWithoutMailNorTel();

        if(null !== $edition)
        {

            foreach($coureursNada as $key => $coureur)
            {
                if($coureur->getEquipe()->getCourse()->getEdition()->getId() != $edition)
                {
                    unset($coureursNada[$key]);
                }
            }
            foreach($coureursNoMail as $key => $coureur)
            {
                if($coureur->getEquipe()->getCourse()->getEdition()->getId() != $edition)
                {
                    unset($coureursNoMail[$key]);
                }
            }
        }
        return $this->render('RotisCourseMakerBundle:Admin:relous.html.twig', array(
            'coureursNoMail' => $coureursNoMail,
            'coureursNada' => $coureursNada, 
        ));
    }

    public function excelAction($edition = null)
    {
        if(!$edition)
        {
            $courses = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->findAll();
        }
        else
        {
            $courses = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->findByEdition($edition);
        }
        return $this->render('RotisCourseMakerBundle:Admin:excel.html.twig', array(
            'courses' => $courses,
        ));
    }


    public function adminAction()
    {
        if (true === $this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            $form = $this->createForm(new EditionChoiceType());
            $edition = null;
            if ($this->getRequest()->getMethod() == 'POST') {
                $form->bind($this->getRequest());
                if ($form->isValid()) {
                    if($form->getData() !== null){
                        $data = $form->getData();
                        $edition = $data['edition'];
                    }
                }
            }

            return $this->render('RotisCourseMakerBundle:Admin:admin.html.twig',array(
                'form' => $form->createView(),
                'edition' => $edition,
            ));
        }
        else
        {
            return $this->redirect($this->generateUrl('accueil'));
        }
    }

    public function listeAction($name,$edition = null)
    {
        if(!$edition)
        {
            $edition = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Edition')->findLast()->getNumero();
        }
        $form = $this->createForm(new RechercheType());
        if ($name === "equipe")
        {
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Equipe');
            $listeEquipes = $repository->findEdition($edition);
            $totalEquipes = count($listeEquipes);
            $equipesValides = $repository->countEquipesValides($edition);
            if ($this->getRequest()->getMethod() == 'POST') {
                $form->bind($this->getRequest());
                if ($form->isValid()) {
                    $mot = $form->get('mot')->getData();

                    if ($mot)
                    {
                        $listeEquipes = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Joueur')->findENameJLike($mot,$edition);
                    }
                }
            }
            return $this->render('RotisCourseMakerBundle:Admin:equipes.html.twig', array(
                'nbEquipesValides' => $equipesValides,
                'nbTotalEquipes' => $totalEquipes,
                'name' => $name,
                'equipes' => $listeEquipes,
                'edition' => $edition,
                'form' => $form->createView()));
        }
        elseif ($name === "course")
        {

            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Course');


            $listeCourses = $repository->findByEdition($edition);
            if ($this->getRequest()->getMethod() == 'POST') {

                $form->bind($this->getRequest());
                if ($form->isValid()) {
                    $mot = $form->get('mot')->getData();
                    if ($mot)
                    {
                        $listeCourses = $repository->findLike($mot,$edition);
                    }
                }
            }
            $totalValidTeams = 0;
            $totalTeams = 0;
            $totalCoureurs = 0;
            foreach($listeCourses as $course)
            {
                foreach($course->getEquipes() as $equipe)
                {
                    if($equipe->getValide())
                    {
                        $totalValidTeams++;
                    }
                    $totalTeams++;
                    $totalCoureurs+= $equipe->getJoueurs()->count();
                }
            }

            return $this->render('RotisCourseMakerBundle:Admin:courses.html.twig', array(
                'name' => $name,
                'courses' => $listeCourses,
                'totalCoureurs' => $totalCoureurs,
                'totalValidTeams' => $totalValidTeams,
                'totalTeams' => $totalTeams,
                'edition' => $edition,
                'form' => $form->createView(),
            ));

        }
    }

    public function mailingAction($edition)
    {
        if($edition === null)
        {
            $listeCourses = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Course')
                ->findAll();
            $tousJoueurs = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Joueur')
                ->findAll();
        }
        else
        {
            $listeCourses = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Course')
                ->findByEdition($edition);
            $tousJoueurs = Array();
            foreach($listeCourses as $course)
            {
                foreach($course->getEquipes() as $equipe)
                {
                    foreach($equipe->getJoueurs() as $joueur)
                    {
                        $tousJoueurs[] = $joueur;
                    }
                }
            }
        }
        return $this->render('RotisCourseMakerBundle:Admin:mailing.html.twig', array('tousJoueurs' => $tousJoueurs, 'listeCourses' => $listeCourses));
    }

    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();
        $editions = $em->getRepository('RotisCourseMakerBundle:Edition')->findAll();
        $categories = $em->getRepository('RotisCourseMakerBundle:Categorie')->findAll();
        $types = $em->getRepository('RotisCourseMakerBundle:Type')->findAll();
        $courses = $em->getRepository('RotisCourseMakerBundle:Course')->findAll();
        $tarifs = $em->getRepository('RotisCourseMakerBundle:Tarif')->findAll();

        return $this->render('RotisCourseMakerBundle:CRUD:dashboard.html.twig', array(
            'editions' => $editions, 'categories' => $categories, 'types' => $types, 'courses' => $courses, 'tarifs' => $tarifs,
        ));
    }

    public function switchOuvertureAction($idcourse,$status)
    {
        $course = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Course')
            ->find($idcourse);
        $em = $this->getDoctrine()->getManager();
        $course->setInscriptionsOuvertes(!$status);
        $em->merge($course);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_control', array(
            'name' => 'course',
        )));
    }
}
