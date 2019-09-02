<?php

namespace App\Controller;

use App\Entity\ExchangeRates;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ExchangeController extends AbstractController
{
	private $params;
	
	/**
	 * Constructor function
	 * @param ParameterBagInterface $params
	 */
	public function __construct(ParameterBagInterface $params)
	{
		$this->params = $params;
	}
    /**
     * @Route("/", name="exchange", methods={"GET"})
     */
    public function index()
    {
        return $this->render('exchange/index.html.twig');
    }
	
    /**
     * @Route("/latestrates", name="getExchangeRates", methods={"GET"})
     */
    public function getExchangeRates()
    {
		$exchnageRatesClient = HttpClient::create();
		$exchangeRates = $exchnageRatesClient->request('GET', $this->params->get('exchange_api'). 'base=' . $this->params->get('base_currency') . '&symbols=' . $this->params->get('quote_currencies') . '')->getContent();
		
		$this->saveBulkExchangeRates(json_decode($exchangeRates));
		
		return new Response('Latest exchanges rates updated');
    }
	
    /**
     * @Route("/exchange/add", name="addExchangeRates", methods={"POST"})
     */
    public function addExchangeRate(Request $httpRequest)
    {
		$entityManager = $this->getDoctrine()->getManager();
		$exchangeRates = new ExchangeRates();

		$exchangeRates->setCurrency($httpRequest->request->get('currency'));
		$exchangeRates->setExchangeRate(number_format((float)$httpRequest->request->get('exchangeRate'), 2, '.', ''));
		$exchangeRates->setCreatedDatetime(new \DateTime('@' . strtotime('now')));

		$entityManager->persist($exchangeRates);
		$entityManager->flush();
		
		return new Response('Data saved ' . $exchangeRates->getId());
    }
	
    /**
     * @Route("/exchange/update", name="updateExchangeRate", methods={"PUT"})
     */
    public function updateExchangeRate(Request $httpRequest)
    {
		$entityManager = $this->getDoctrine()->getManager();		
		$exchangeRates = $entityManager->getRepository(ExchangeRates::class)->find($httpRequest->request->get('id'));
		
		if(!is_null($exchangeRates)) {
			$exchangeRates->setCurrency($httpRequest->request->get('currency'));
			$exchangeRates->setExchangeRate(number_format((float)$httpRequest->request->get('exchangeRate'), 2, '.', ''));
			$exchangeRates->setUpdatedDatetime(new \DateTime('@' . strtotime('now')));

			$entityManager->persist($exchangeRates);
			$entityManager->flush();
			
			return new Response('Data saved ' . $exchangeRates->getId());
		}
		
		return new Response('Could not save data. Please try again');
    }
	
    /**
     * @Route("/exchange/remove", name="removeExchangeRate", methods={"DELETE"})
     */
    public function removeExchangeRate(Request $httpRequest)
    {
		$entityManager = $this->getDoctrine()->getManager();
		$exchangeRates = $entityManager->getRepository(ExchangeRates::class)->find($httpRequest->request->get('id'));
		
		if(!is_null($exchangeRates)) {
			$entityManager->remove($exchangeRates);
			$entityManager->flush();
		
			return new Response('Data deleted');
		}
		return new Response('Could not delete');
    }
	
	/**
	 * Save exchange rates information to DB
	 * @param JSON $exchangeRatesData
	 * @return int
	 */
	private function saveBulkExchangeRates($exchangeRatesData)
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
	
    /**
     * @Route("/rates/{action}", name="displayRatesDashboard", methods={"GET"})
     */
    public function displayRatesDashboard($action = 'view')
    {
		/**
		 * Get all the exchange rates stored
		 */
		$savedExchangeRates = $this->getDoctrine()->getRepository(ExchangeRates::class)->findAll();
		$savedExchangeRates = $this->serializeObject($savedExchangeRates);
		
        return $this->render('exchange/rates.html.twig', [
            'exchange_rates' => json_decode($savedExchangeRates),
			'quote_currencies' => explode(',', $this->params->get('quote_currencies')),
			'base_currency' => $this->params->get('base_currency'),
			'action' => $action,
			'available_currencies' => explode(',', $this->params->get('available_currencies')),
        ]);
    }
	
	/**
	 * Function to serilize objects to JSON
	 */
	private function serializeObject($inputObject)
	{
		$serilizer = $this->container->get('serializer');		
		$jsondata = $serilizer->serialize($inputObject, 'json');
		
		return $jsondata;
	}
	
	/**
     * @Route("/exchange/getalldata", name="getSavedExchangeRates", methods={"GET"})
	 */
	public function getSavedExchangeRates()
	{
		/**
		 * Get all the exchange rates stored
		 */
		$savedExchangeRates = $this->getDoctrine()->getRepository(ExchangeRates::class)->findAll();
		$savedExchangeRates = $this->serializeObject($savedExchangeRates);
		
		return new Response($savedExchangeRates);
	}
}
