<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Batch Test
 *
 * @package   frankmayer\ArangoDbPhpCoreCurl
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl\Tests\Integration;

require_once __DIR__ . '/ArangoDbPhpCoreCurlIntegrationTestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Batch;
use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpResponse;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;
use function frankmayer\ArangoDbPhpCoreCurl\getClient;


/**
 * Class BatchTest
 * @package frankmayer\ArangoDbPhpCoreCurl
 */
class BatchIntegrationTest extends
	ArangoDbPhpCoreCurlIntegrationTestCase
{
	/**
	 * @var
	 */
	public $client;
	/**
	 * @var
	 */
	protected $connector;

	protected $collectionNames = [];


	/**
	 *
	 */
	public function setUp()
	{
		$this->connector = new Connector();
		$this->client    = getClient($this->connector);

		$this->collectionNames[0] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection-01';
		$this->collectionNames[1] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection-02';
		$this->collectionNames[2] = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection-03';
	}


	/**
	 * Test if we can get the server version
	 */
	public function testCreateCollectionInBatchAndDeleteThemAgainInBatch()
	{
		$collectionOptions = ['waitForSync' => true];

		$batchParts = [];

		foreach ($this->collectionNames as $collectionName)
		{
			$collection = new Collection($this->client);

			/** @var $responseObject HttpResponse */
			$batchPart = $collection->create($collectionName, $collectionOptions, ['isBatchPart' => true]);

			$batchParts[] = $batchPart;
		}

		/** @var HttpResponse $responseObject */
		$responseObject = Batch::send($this->client, $batchParts);
		static::assertEquals(200, $responseObject->status);

		$batchResponseParts = $responseObject->batch;

		/** @var $batchPart HttpResponse */
		foreach ($batchResponseParts as $batchPart)
		{
			$body = $batchPart->body;
			static::assertArrayHasKey('code', json_decode($body, true));
			$decodedJsonBody = json_decode($body, true);
			static::assertEquals(200, $decodedJsonBody['code']);
		}

		$batchParts = [];

		foreach ($this->collectionNames as $collectionName)
		{
			$collection = new Collection($this->client);

			/** @var $responseObject HttpResponse */
			$batchParts[] = $collection->drop($collectionName, ['isBatchPart' => true]);
		}

		$responseObject = Batch::send($this->client, $batchParts);

		$batchResponseParts = $responseObject->batch;

		foreach ($batchResponseParts as $batchPart)
		{
			$body = $batchPart->body;
			static::assertArrayHasKey('code', json_decode($body, true));
			$decodedJsonBody = json_decode($body, true);
			static::assertEquals(200, $decodedJsonBody['code']);
		}
	}


	/**
	 *
	 */
	public function tearDown()
	{
		$batchParts = [];
		foreach ($this->collectionNames as $collectionName)
		{
			$collection = new Collection($this->client);

			/** @var $responseObject HttpResponse */
			$batchParts[] = $collection->drop($collectionName, ['isBatchPart' => true]);
		}
		$responseObject = Batch::send($this->client, $batchParts);
		static::assertEquals(200, $responseObject->status);
	}
}
