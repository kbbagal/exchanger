<?php

namespace App\Tests\Controller;

use App\Controller\ExchangeController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeControllerTest extends WebTestCase
{
	
	public function testgetExchangeRates()
	{
		$client = static::createClient();
		$client->request('GET', '/latestrates');
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}