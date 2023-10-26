<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Currency>
 *
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function firstOrCreate($id, $code, $name): Currency
    {
        $currency = $this->find($id);

        if (!$currency) {
            $currency = new Currency();
            $currency->setId($id);
            $currency->setCode($code);
            $currency->setName($name);
            $this->_em->persist($currency);
            $this->_em->flush();
        }

        return $currency;
    }

    public function create($id, $code, $name): void
    {
        $currency = new Currency();
        $currency->setId($id);
        $currency->setCode($code);
        $currency->setName($name);
        $this->_em->persist($currency);
        $this->_em->flush();
    }
}
