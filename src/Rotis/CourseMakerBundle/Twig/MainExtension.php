<?php
namespace Rotis\CourseMakerBundle\Twig;

use Rotis\CourseMakerBundle\Entity\Equipe;

class MainExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('news', array($this,'newsFile')),
        );
    }

    public function newsFile(Equipe $equipe)
    {
        foreach($equipe->getJoueurs() as $joueur)
        {
            if($joueur->getCarte() && !$joueur->getCarteOk()){
                return 'new';
            }
            if($joueur->getCertif() && !$joueur->getPapiersOk()){
                return 'new';
            }
        }
        return '';
    }

    public function getName()
    {
        return 'news_extension';
    }
}