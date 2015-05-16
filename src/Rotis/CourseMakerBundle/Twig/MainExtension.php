<?php
namespace Rotis\CourseMakerBundle\Twig;

use Rotis\CourseMakerBundle\Entity\Equipe;

class MainExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('news', array($this,'newsFile')),
            new \Twig_SimpleFunction('valid', array($this,'validTeam')),
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

    public function validTeam(Equipe $equipe)
    {
        if(!$equipe->getValide()){
            $count = 0;
            foreach($equipe->getJoueurs() as $joueur)
            {

                if( ( ($joueur->getEtudiant() && $joueur->getCarteOk()) || !$joueur->getEtudiant()) && $joueur->getPapiersOk() && $joueur->getPaiementOk()){
                    $count++;
                }
            }
            if($count === count($equipe->getJoueurs())){
                return 'à valider!';
            }
        }
        return '';
    }

    public function getName()
    {
        return 'news_extension';
    }
}