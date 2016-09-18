<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Plugin Test
 *
 * @package   frankmayer\ArangoDbPhpCoreCurl
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl\Tests\Integration;

require_once __DIR__ . '/ArangoDbPhpCoreCurlIntegrationTestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientOptions;
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Plugins\TestPlugin;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;
use function frankmayer\ArangoDbPhpCoreCurl\getClient;


/**
 * Class PluginTest
 * @package frankmayer\ArangoDbPhpCoreCurl
 */
class PluginIntegrationTest extends
	ArangoDbPhpCoreCurlIntegrationTestCase
{
	/**
	 * @var ClientOptions $clientOptions
	 */
	public $clientOptions;

	/**
	 * @var Client $client
	 */
	public $client;
	protected $connector;


	/**
	 *
	 */
	public function setUp()
	{
		$this->connector = new Connector();
		$this->client    = getClient($this->connector);
	}

	// todo 1 Frank Complete plugin tests

	/**
	 *
	 */
	public function testRegisterPluginsWithDifferentPrioritiesTestAndUnRegisterPlugin()
	{
		$this->client->setPluginManager(new PluginManager($this->client));
		$this->assertInstanceOf('\frankmayer\ArangoDbPhpCore\Plugins\PluginManager', $this->client->getPluginManager());

		$tracer            = new TestPlugin();
		$tracer->priority  = 0;
		$tracer2           = new TestPlugin();
		$tracer2->priority = 20;
		$tracer3           = new TestPlugin();
		$tracer3->priority = -30;
		$tracer4           = new TestPlugin();
		$tracer4->priority = 20;

		$this->clientOptions['plugins'] = [
			'tracer1' => $tracer,
			'tracer2' => $tracer2,
			'tracer3' => $tracer3,
			'tracer4' => $tracer4,
		];

		$this->client->setPluginsFromPluginArray($this->clientOptions['plugins']);
		$this->assertArrayHasKey('tracer3', $this->client->pluginManager->pluginStorage);

		$e = null;
		try
		{
			$this->client->setPluginsFromPluginArray(['tracer5' => new \stdClass()]);
		}
		catch (\Exception $e)
		{
		}
		$this->assertInstanceOf('\Exception', $e);

		/** @var $responseObject HttpResponse */
		$collection = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $collection->getAll();

		$this->assertInstanceOf(
			'frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequestInterface',
			$responseObject->request
		);
	}


	/**
	 *
	 */
	public function tearDown()
	{
	}
}
