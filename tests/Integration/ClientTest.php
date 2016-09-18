<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Client Test
 *
 * @package   frankmayer\ArangoDbPhpCoreCurl
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl\Tests\Integration;

require_once __DIR__ . '/ArangoDbPhpCoreCurlIntegrationTestCase.php';

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;
use function frankmayer\ArangoDbPhpCoreCurl\getClient;

//todo: fix tests


/**
 * Class ClientTest
 * @package frankmayer\ArangoDbPhpCore
 */
class ClientIntegrationTest extends
	ArangoDbPhpCoreCurlIntegrationTestCase
{

	/**
	 * @var Client
	 */
	public $client;
	/**
	 * @var Connector
	 */
	public $connector;


	/**
	 *
	 */
	public function setUp()
	{
		$this->connector = new Connector();
		$this->client    = getClient($this->connector);
	}


	/**
	 *
	 */
	public function tearDown()
	{
	}
}
