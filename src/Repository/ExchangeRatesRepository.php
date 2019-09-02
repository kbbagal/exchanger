<?php

namespace App\Repository;

use App\Entity\ExchangeRates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExchangeRates|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeRates|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeRates[]    findAll()
 * @method ExchangeRates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRates::class);
    }
	
	/**
	 * Save exchange rates information to DB
	 * @param JSON $exchangeRatesData
	 * @return int
	 */
	private function saveBulkExchangeRatess($exchangeRatesData)
	{
		$entityManager = $this->getDoctrine()->getManager();
			
		foreach($exchangeRatesData->rates as $key => $value) {
			$exchangeRates = new ExchangeRates();
			
			$exchangeRates->setCurrency($key);
			$exchangeRates->setExchangeRate(number_format((float)$value, 2, '.', ''));
			$exchangeRates->setCreatedDatetime(new \DateTime('@' . strtotime('now')));
			
			$entityManager->persist($exchangeRates);
		}
		
		$entityManager->flush();
	}
	
	
}
