<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Database Test
 *
 * @package   frankmayer\ArangoDbPhpCoreCurl
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl\Tests\Integration;

require_once __DIR__ . '/ArangoDbPhpCoreCurlIntegrationTestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Database;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;
use function frankmayer\ArangoDbPhpCoreCurl\getClient;
use frankmayer\ArangoDbPhpCoreCurl\Protocols\Http\HttpResponse;


/**
 * Class DatabaseTest
 * @package frankmayer\ArangoDbPhpCoreCurl
 */
class DatabaseIntegrationTest extends
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
	public function testCreateDatabaseWithoutApiClasses()
	{
		$databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-Database';

		$databaseOptions    = [];
		$databaseParameters = [];
		$options            = $databaseOptions;
		$this->client->bind(
			'Request',
			function ()
			{
				return $this->client->getRequest();
			}
		);

		// And here's how one gets an HttpRequest object through the IOC.
		// Note that the type-name 'httpRequest' is the name we bound our HttpRequest class creation-closure to. (see above)
		$request          = $this->client->make('Request');
		$request->options = $options;
		$request->body    = ['name' => $databaseName];

		$request->body = self::array_merge_recursive_distinct($request->body, $databaseParameters);
		$request->body = json_encode($request->body);

		$request->path   = $this->client->fullDatabasePath . self::API_DATABASE;
		$request->method = self::METHOD_POST;

		$responseObject = $request->send();

		$body = $responseObject->body;

		static::assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		static::assertEquals(201, $decodedJsonBody['code']);
		static::assertTrue($decodedJsonBody['result']);
	}


	/**
	 * Test if we can get the server version
	 */
	public function testDeleteDatabaseWithoutApiClasses()
	{
		$databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-Database';

		$databaseOptions = [];
		$options         = $databaseOptions;
		$this->client->bind(
			'Request',
			function ()
			{
				return $this->client->getRequest();
			}
		);


		$request = $this->client->make('Request');

		$request->options = $options;
		$request->path    = $this->client->fullDatabasePath . self::API_DATABASE . '/' . $databaseName;
		$request->method  = self::METHOD_DELETE;

		$responseObject = $request->send();
		$body           = $responseObject->body;

		static::assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		static::assertEquals(200, $decodedJsonBody['code']);
	}


	/**
	 * Test if we can get the server version
	 */
	public function testCreateDatabaseViaIocContainer()
	{
		$databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-Database';

		$databaseOptions = [];


		$database = new Database($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $database->create($databaseName, $databaseOptions);

		$body = $responseObject->body;

		static::assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		static::assertEquals(201, $decodedJsonBody['code']);
		static::assertTrue($decodedJsonBody['result']);
	}


	/**
	 * Test if we can get the server version
	 */
	public function testDeleteDatabase()
	{

		$databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-Database';

		$database = new Database($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $database->drop($databaseName);

		$body = $responseObject->body;

		static::assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		static::assertEquals(200, $decodedJsonBody['code']);
	}


	/**
	 * Test if we can get all databases
	 */
	public function testGetDatabases()
	{
		$database = new Database($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $database->getAll();

		$response = json_decode($responseObject->body);

		static::assertEquals('_system', $response->result[0]);
	}


	/**
	 *
	 */
	public function tearDown()
	{
		$databaseName = 'ArangoDB-PHP-Core-DatabaseTestSuite-DatabaseViaIocContainer';
		$database     = new Database($this->client);

		/** @var $responseObject HttpResponse */
		$database->drop($databaseName);
	}
}
