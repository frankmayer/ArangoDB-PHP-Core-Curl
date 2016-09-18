<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Collection Test
 *
 * @package   frankmayer\ArangoDbPhpCoreCurl
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl\Tests\Integration;

require_once __DIR__ . '/ArangoDbPhpCoreCurlIntegrationTestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;
use function frankmayer\ArangoDbPhpCoreCurl\getClient;
use HttpResponse;

//todo: fix tests

/**
 * Class CollectionTest
 * @package frankmayer\ArangoDbPhpCoreCurl
 */
class CollectionIntegrationTest extends
	ArangoDbPhpCoreCurlIntegrationTestCase
{
	/**
	 * @var Client
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


	/**
	 * Test if we can get the server version
	 */
	public function testCreateCollectionWithoutApiClasses()
	{
		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

		$collectionOptions    = ['waitForSync' => true];
		$collectionParameters = [];
		$options              = $collectionOptions;
		$this->client->bind(
			'Request',
			function ()
			{
				$request = $this->client->getRequest();

				return $request;
			}
		);

		// And here's how one gets an HttpRequest object through the IOC.
		// Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
		$request          = $this->client->make('Request');
		$request->options = $options;
		$request->body    = ['name' => $collectionName];

		$request->body = self::array_merge_recursive_distinct($request->body, $collectionParameters);
		$request->body = json_encode($request->body);

		$request->path   = $this->client->fullDatabasePath . self::API_COLLECTION;
		$request->method = self::METHOD_POST;

		$responseObject = $request->send();

		$body = $responseObject->body;

		$this->assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		$this->assertEquals(200, $decodedJsonBody['code']);
		$this->assertEquals($collectionName, $decodedJsonBody['name']);
	}


	/**
	 * Test if we can get the server version
	 */
	public function testDeleteCollectionWithoutApiClasses()
	{
		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

		$collectionOptions = ['waitForSync' => true];
		$options           = $collectionOptions;
		$this->client->bind(
			'Request',
			function ()
			{
				$request = $this->client->getRequest();

				return $request;
			}
		);


		$request = $this->client->make('Request');

		$request->options = $options;
		$request->path    = $this->client->fullDatabasePath . self::API_COLLECTION . '/' . $collectionName;
		$request->method  = self::METHOD_DELETE;

		$responseObject = $request->send();
		$body           = $responseObject->body;

		$this->assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		$this->assertEquals(200, $decodedJsonBody['code']);
	}


	/**
	 * Test if we can get the server version
	 */
	public function testCreateCollectionViaIocContainer()
	{
		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

		$collectionOptions = ['waitForSync' => true];


		$collection = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $collection->create($collectionName, $collectionOptions);

		$body = $responseObject->body;

		$this->assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		$this->assertEquals(200, $decodedJsonBody['code']);
		$this->assertEquals($collectionName, $decodedJsonBody['name']);
	}


	/**
	 * Test if we can get the server version
	 */
	public function testTruncateCollection()
	{
		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

		$collection = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $collection->truncate($collectionName);

		$body = $responseObject->body;

		$this->assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		$this->assertEquals(200, $decodedJsonBody['code']);
		$this->assertEquals($collectionName, $decodedJsonBody['name']);
	}


	/**
	 * Test if we can get the server version
	 */
	public function testDeleteCollection()
	{

		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

		$collection = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $collection->drop($collectionName);

		$body = $responseObject->body;

		$this->assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		$this->assertEquals(200, $decodedJsonBody['code']);
	}


	/**
	 * Test if we can get all collections
	 */
	public function testGetCollections()
	{
		$collection = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $collection->getAll();

		$response    = json_decode($responseObject->body);
		$foundGraphs = false;

		foreach ($response->result as $value)
		{
			if ($value->name === '_graphs')
			{
				$foundGraphs = true;
			}
		}
		$this->assertTrue($foundGraphs);
	}


	/**
	 * Test if we can get all collections
	 */
	public function testGetCollectionsExcludeSystem()
	{
		$collection = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $collection->getAll(['excludeSystem' => true]);

		$response = json_decode($responseObject->body);

		$foundGraphs = false;

		foreach ($response->result as $value)
		{
			if ($value->name === '_graphs')
			{
				$foundGraphs = true;
			}
		}
		$this->assertFalse($foundGraphs);
	}


	/**
	 *
	 */
	public function tearDown()
	{
		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-CollectionViaIocContainer';
		$collection     = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$collection->drop($collectionName);
	}
}
