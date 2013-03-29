<?php
namespace Rotis\CourseMakerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;



class SecurityController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // TODO : no error is displayed if there's one?

        // recupere l erreur de login si presente
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
        {
            return $this->render('RotisCourseMakerBundle:Security:login.html.twig',
                array(
                    //last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error'         => "Nom d'équipe et/ou mot de passe invalide"
                )
            );
        } else {
            $session->remove(SecurityContext::AUTHENTICATION_ERROR); // TODO : maybe to remove?

            return $this->render('RotisCourseMakerBundle:Security:login.html.twig',
                array(
                    //last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME)
                )
            );
        }

    }

}
