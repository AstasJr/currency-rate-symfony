<?php

namespace App\Repository;

use App\Entity\CurrencyRate;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyRate>
 *
 * @method CurrencyRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyRate[]    findAll()
 * @method CurrencyRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRateRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private CurrencyRepository $currencyRepository,
    )
    {
        parent::__construct($registry, CurrencyRate::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByIdDateRate($id, $date, $rate)
    {
        return $this->createQueryBuilder('cr')
            ->andWhere('cr.currency_id = :id')
            ->andWhere('cr.date = :date')
            ->andWhere('cr.rate = :rate')
            ->setParameter('id', $id)
            ->setParameter('date', $date)
            ->setParameter('rate', $rate)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    public function create($id, $date, $rate): void
    {
        $currencyRate = new CurrencyRate();
        $currency = $this->currencyRepository->find($id);
        $currencyRate->setCurrency($currency);
        $currencyRate->setCurrencyId($id);
        $dateObject = Carbon::createFromFormat('Y-m-d', $date);
        $currencyRate->setDate($dateObject);
        $currencyRate->setRate(str_replace(',', '.', $rate));

        $this->_em->persist($currencyRate);
        $this->_em->flush();;
    }
}
