<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\Tests\Common\Annotations\Null;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * JoueurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class JoueurRepository extends EntityRepository
{
    public function findENameJLike($mot,$numero = null)
    {
        $qb = $this
            ->createQueryBuilder('j');
            $qb->where('j.nom LIKE :mot' )
                ->orWhere('j.prenom LIKE :mot');
            if($numero)
            {
                $qb->join('j.equipe','e')
                    ->join('e.course','c')
                    ->join('c.edition','ed','WITH','ed.numero = :numero')
                    ->setParameters(array('numero' => $numero, 'mot' => $mot));
            }

            /*else
            {
                $qb->setParameter('mot', '%'.$mot.'%');
            }*/
        $joueurs = $qb->getQuery()->getResult();
        $listeEquipes = new ArrayCollection();
        foreach($joueurs as $joueur)
        {
           $equipe = $joueur->getEquipe();
           if(!($listeEquipes->contains($equipe)))
            {
                $listeEquipes->add($equipe);
            }
        }

        return $listeEquipes;
    }

    public function findJWithoutMail($edition = null)
    {
        $qb = $this->createQueryBuilder('j');
        if($edition) {
            $qb->join('j.equipe','e')
                ->join('e.course','c')
                ->join('c.edition','ed','WITH','ed.numero =:edition')
                ->setParameter('edition',$edition);
        }
        $qb->where('j.email is NULL')
            ->andWhere('j.telephone is NOT NULL');
        $query = $qb->getQuery();
        $joueurs = $query->getResult();
        return $joueurs;
    }

    public function findJWithoutMailNorTel($edition = null)
    {
        $qb = $this->createQueryBuilder('j');
        if($edition) {
            $qb->join('j.equipe','e')
                ->join('e.course','c')
                ->join('c.edition','ed','WITH','ed.numero =:edition')
                ->setParameter('edition',$edition);
        }
        $qb->where('j.email is NULL')
            ->andWhere('j.telephone is NULL');
        $query = $qb->getQuery();
        $joueurs = $query->getResult();
        return $joueurs;
    }

    public function countCoureurs($edition = null)
    {
        $qb = $this->createQueryBuilder('j');
        $qb->select('count(j.id)');
        if($edition){
            $qb->join('j.equipe','e')
                ->join('e.course','c')
                ->join('c.edition','ed','WITH','ed.numero =:edition')
                ->setParameter('edition',$edition);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findCoureurs($edition = null)
    {
        $qb = $this->createQueryBuilder('j');
        if($edition){
            $qb->join('j.equipe','e')
                ->join('e.course','c')
                ->join('c.edition','ed','WITH','ed.numero =:edition')
                ->setParameter('edition',$edition);
        }
        return $qb->getQuery()->getResult();
    }

    public function findInvalides($edition = null)
    {
        $qb = $this->createQueryBuilder('j');
        if($edition){
            $qb->join('j.equipe','e')
                ->join('e.course','c')
                ->join('c.edition','ed','WITH','ed.numero =:edition')
                ->where($qb->expr()->neq('j.papiers_ok','true'))
                ->orWhere($qb->expr()->andX(
                    $qb->expr()->eq('j.etudiant','true'),
                    $qb->expr()->neq('j.carte_ok','true')
                ))
                ->orWhere($qb->expr()->neq('j.paiement_ok','true'))
                ->setParameter('edition',$edition);
        }
        return $qb->getQuery()->getResult();
    }

    public function countCoureursByCourse($course)
    {
        $qb = $this->createQueryBuilder('j');
        $qb->select('count(j.id)');
        $qb->join('j.equipe','e')
            ->join('e.course','c','WITH','c.id =:course')
            ->setParameter('course',$course);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
