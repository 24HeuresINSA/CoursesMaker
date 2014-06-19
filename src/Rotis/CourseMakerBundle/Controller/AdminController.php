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

    public function listeAction($name)
    {
        $form = $this->createForm(new RechercheType());
        if ($name === "equipe")
        {
            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Equipe');
            $listeEquipes = $repository->findAll();
            $totalEquipes = count($listeEquipes);
            $equipesValides = $repository->findEquipesValides();
            if ($this->getRequest()->getMethod() == 'POST') {
                $form->bind($this->getRequest());
                if ($form->isValid()) {
                    $mot = $form->getData();

                    if (0 != $mot)
                    {
                        $listeEquipes = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Joueur')->findENameJLike($mot);
                    }
                }
            }
            return $this->render('RotisCourseMakerBundle:Equipe:control_equipe.html.twig', array('nbEquipesValides' => $equipesValides, 'nbTotalEquipes' => $totalEquipes, 'name' => $name, 'equipes' => $listeEquipes, 'form' => $form->createView()));
        }
        elseif ($name === "course")
        {

            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('RotisCourseMakerBundle:Course');


            $listeCourses = $repository->findAll();
            if ($this->getRequest()->getMethod() == 'POST') {

                $form->bind($this->getRequest());
                if ($form->isValid()) {
                    $mot = $form->getData();
                    if (0 != $mot)
                    {
                        $listeCourses = $repository->findLike($mot);
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

            return $this->render('RotisCourseMakerBundle:Course:control_course.html.twig', array(
                'name' => $name, 'courses' => $listeCourses, 'totalCoureurs' => $totalCoureurs, 'totalValidTeams' => $totalValidTeams, 'totalTeams' => $totalTeams, 'form' => $form->createView(),
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
        return $this->render('RotisCourseMakerBundle:Course:mailing.html.twig', array('tousJoueurs' => $tousJoueurs, 'listeCourses' => $listeCourses));
    }
}
