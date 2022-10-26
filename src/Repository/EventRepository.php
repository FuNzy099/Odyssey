<?php

namespace App\Repository;

use App\Entity\Event;
use App\Data\SearchData;
use App\Data\SearchAdmin;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{

    private $flash;

    /*
        PaginatorInterface est un bundle servant à la pagination

        DOCUMENTATION : 
            https://github.com/KnpLabs/KnpPaginatorBundle
    */
    public function __construct(ManagerRegistry $registry, FlashBagInterface $flash, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Event::class);
        $this -> paginator = $paginator;
        $this->flash = $flash;
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Permets de récupèrer les evenements en lien avec une recherche
     * 
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this 
            ->createQueryBuilder('event')
            ->select('event')
            ->orderBy('event.startDate', 'DESC');
          
            
            // Condition permettant à l'utilisateur de filtrer les évènements par mot clef 
            if(!empty($search -> q)){

                $query = $query
                
                    /*
                        andWhere permet d'ajouter une ou plusieurs restrictions au resultats de la requête,
                        C'est le même principe que lorsqu'on bindParam en PDO

                        L'opérateur LIKE est utilisé dans la clause WHERE en SQL, Ce mot-clé permet d'effectuer une recherche sur un modèle particulier !

                        DOCUMENTATION : 
                            andWhere :  https://symfonycasts.com/screencast/doctrine-queries/and-where-or-where#building-and-where-into-a-query
                            LIKE :      https://sql.sh/cours/where/like
                    */ 
                    ->andWhere('event.title LIKE :q')

                    /*
                        setPArameter permet de deffinir un paramètre pour la requête en cours de construction, cela évite les attaques par injection SQL

                        DOCUMENTATION : 
                            setPArameter : https://symfonycasts.com/screencast/doctrine-queries/and-where-or-where#building-and-where-into-a-query

                    */
                    ->setParameter('q', "%{$search->q}%");
            }

            // Condition permettant à l'utilisateur de filtrer les évènements par date de début
            if(!empty($search -> startDate)){
                $query = $query
                    ->andWhere('event.startDate >= :startDate')
                    ->setParameter('startDate', "{$search->startDate->format('Y-m-d H:i')}");
            }

            // Condition permettant à l'utilisateur de filtrer les évènements par date de fin
            if(!empty($search -> endDate)){
                $query = $query
                    ->andWhere('event.endDate <= :endDate')
                    ->setParameter('endDate', "{$search->endDate->format('Y-m-d H:i')}");
            }

            // Condition permettant à l'utilisateur de filtrer les évènements par ville
            if(!empty($search -> town)){
                $query = $query
                    ->andWhere('event.town LIKE :town')
                    ->setParameter('town', "%{$search->town}%");
            }

            // Condition permettant à l'utilisateur de filtrer les évènements par code postal
            if(!empty($search -> zipCode)){
                $query = $query
                    ->andWhere('event.zipCode LIKE :zipCode')
                    ->setParameter('zipCode', "{$search->zipCode}");
            }
        
            // Condition qui permet de verifier si le résultat de la requette n'est pas vide, si t'elle est le cas on retourne le résultat
            if(!empty($query -> getQuery() -> getResult())){

                // On récupère la requête 
                $query = $query -> getQuery() ;

                //  On passe la requête au paginator
                return $this -> paginator -> paginate(
                    $query,             // 1er parametre: la reqûete
                    $search -> page,    // 2eme parametre: le numéro de la page (page fait référence à la propriété public dans la class SearchData)
                    5                   // 3eme parametre: le nombre d'item par page
                );

            // Si le résultat de la requette est vide on retourne un flash à l'utlisateur
            } else{ 
               
                $this->flash->add('warningSearch', 'Aucun resultats ne correspond avec votre recherche !');

                return $this -> paginator -> paginate(
                    $query,     // 1er parametre: la reqûete
                );
         
            }
    }

    /**
     * Permets de récupèrer les evenements en lien avec une recherche
     * 
     * @return PaginationInterface
     */
    public function adminFindSearch(SearchAdmin $search): PaginationInterface
    {
        $query = $this 
            ->createQueryBuilder('event');
            $query 
                ->select('e')
                ->from('App\Entity\Event', 'e')
                ->leftJoin('e.userCreator', 'u');
                // ->orderBy('event.startDate', 'DESC')

                // $em = $this->getEntityManager();
                // $sub = $em->createQueryBuilder();
                // $sub->select('user')
                // ->from('App\Entity\User', 'user')
                // ->where($sub->expr()->notIn('user.id', $query->getDQL()))
                // ->setParameter('id', $search);
    
            if(!empty($search -> userCreator)){   
                
                $query = $query
                    ->where('u.pseudonyme = :userCreator')
                    ->andWhere('u.pseudonyme LIKE :userCreator')
                    ->setParameter('userCreator', "%{$search->userCreator}%");
                
            }
            
            
            // dd($query->getQuery()->getResult());
            
            if(!empty($query -> getQuery() -> getResult())){
        
                // On récupère la requête 
                $query = $query -> getQuery() ;
                // dd($query);

                //  On passe la requête au paginator
                return $this -> paginator -> paginate(
                    $query,             // 1er parametre: la reqûete
                    $search -> page,    // 2eme parametre: le numéro de la page (page fait référence à la propriété public dans la class SearchData)
                    10                  // 3eme parametre: le nombre d'item par page
                );
                
            }else{ 
               
                $this->flash->add('warningSearch', 'Aucun resultats ne correspond avec votre recherche !');

                return $this -> paginator -> paginate(
                    $query,     // 1er parametre: la reqûete
                );
         
            }
    }


















    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
