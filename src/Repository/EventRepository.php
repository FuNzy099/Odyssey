<?php

namespace App\Repository;

use App\Entity\Event;
use App\Data\SearchData;
use App\Data\SearchAdmin;
use DateTime;
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
    public function findSearchActualEvents(SearchData $search): PaginationInterface
    {
        $now = new DateTime();


        $query = $this 
            ->createQueryBuilder('event')
            ->andWhere('event.startDate >= :val')
            ->setParameter('val', $now -> format('Y-m-d H:i'))
            ->orderBy('event.startDate', 'ASC');
          
            
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



    public function pastEvent()
    {
        $now = new DateTime();

        return $this ->createQueryBuilder('event')
                     ->andWhere('event.startDate < :val')
                     ->setParameter('val', $now->format('Y-m-d'))
                     ->orderBy('event.creationDate', 'ASC')
                     ->getQuery()
                     ->getResult();
    }

    
    public function showEvent($id){

        return   $this->createQueryBuilder('r')

        ->select('r')

        ->andWhere('r.userCreator = :id')

        ->setParameter('id', $id)

        ->getQuery()

        ->getResult();    

    }


    // ! Code mort à supprimer si j'ai pas de problème avec les nouveaux parametres du filre
    // /**
    //  * Permets d'afficher uniquement les évènements à venir
    //  * 
    //  * @return PaginationInterface
    //  */
    // public function actualEvent(SearchData $search): PaginationInterface
    // {
    //     $now = new DateTime();

    //     $query = $this -> createQueryBuilder('event')
    //         ->andWhere('event.startDate >= :val')
    //         ->setParameter('val', $now -> format('Y-m-d H:i'))
    //         ->orderBy('event.startDate', 'ASC');

    //         return $this->paginator->paginate(
    //             $query,
    //             $search -> page,
    //             5
    //         );
    // }
   
  


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
