<?php
namespace Rotis\CourseMakerBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class EditionController extends Controller
{
    public function numeroAction($numero)
    {
        $edition = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Edition')->findOneBy(array('numero' => $numero));

        return $this->render('RotisCourseMakerBundle:Edition:show.html.twig', array(
            'edition' => $edition,
        ));
    }
}
