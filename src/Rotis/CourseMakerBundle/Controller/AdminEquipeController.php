<?php

namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminEquipeController extends Controller
{
    public function listeCourseAction()//liste toutes les courses
    {
        $repository = $this->getDoctrine()
                           ->getManager()
                           ->getRepository('RotisCourseMakerBundle:Course');

        $listeCourses = $repository->findAll();
        return $this->render('RotisCourseMakerBundle:Admin:adminCourses.html.twig',array('courses' => $listeCourses));
    }
    
    public function listeEquipeAction($name) //liste les equipes correspondantes à une course
    {	
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT e FROM RotisCourseMakerBundle:Equipe e WHERE e.course IN (SELECT c from RotisCourseMakerBundle:Course c WHERE c.nom = :course) ORDER BY e.username ASC')->setParameter('course', $name);
		$listeEquipes = $query->getResult();
        return $this->render('RotisCourseMakerBundle:Admin:adminEquipes.html.twig',array('equipes' => $listeEquipes));
    }
}