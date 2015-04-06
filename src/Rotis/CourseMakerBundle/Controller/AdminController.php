<?php

namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Form\Type\EditionChoiceType;
use Rotis\CourseMakerBundle\Form\Type\RechercheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function relousAction($edition = null)
    {
        if(!$edition) {
            $entity = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Edition')->findLast();
            $edition = $entity->getNumero();
        }
        $coureursNoMail =  $this->getDoctrine()->getManager()->getRepository('RotisCourseMakerBundle:Joueur')->findJWithoutMail($edition);
        $coureursNada = $this->getDoctrine()->getManager()->getRepository('RotisCourseMakerBundle:Joueur')->findJWithoutMailNorTel($edition);

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


    public function infosAction()
    {
        return $this->render('RotisCourseMakerBundle:Admin:infos.html.twig');
    }

    public function adminAction()
    {
        if (true === $this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            $form = $this->createForm(new EditionChoiceType());

            $editions = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Edition')->findAll();

            return $this->render('RotisCourseMakerBundle:Admin:admin.html.twig',array(
                'form' => $form->createView(),
                'editions' => $editions,
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
            $totalValidTeams = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Equipe')->countEquipesValides($edition);
            $totalTeams = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Equipe')->countEquipes($edition);
            $totalCoureurs = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Joueur')->countCoureurs($edition);

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


    public function listeParCourseAction($id,$edition)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Equipe');
        $listeEquipes = $repository->findByJoinedCourseId($id);
        $repocourse = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Course');
        $course = $repocourse->find($id);

        $countValid = $repository->countEquipesValidesByCourse($course->getId());
        $countTotal = $repository->countEquipesByCourse($course->getId());
        $countCoureurs = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Joueur')->countCoureursByCourse($course->getId());

        return $this->render('RotisCourseMakerBundle:Admin:equipes_par_course.html.twig', array(
            'equipes' => $listeEquipes,
            'course' => $course,
            'countValid' => $countValid,
            'countTotal' => $countTotal,
            'countCoureurs' => $countCoureurs,
        ));
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
        $resultats = $em->getRepository('RotisCourseMakerBundle:Resultat')->findAll();

        return $this->render('RotisCourseMakerBundle:CRUD:dashboard.html.twig', array(
            'editions' => $editions,
            'categories' => $categories,
            'types' => $types,
            'courses' => $courses,
            'tarifs' => $tarifs,
            'resultats' => $resultats,
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
        return $this->redirect($this->generateUrl('admin_liste', array(
            'name' => 'course',
        )));
    }
}
