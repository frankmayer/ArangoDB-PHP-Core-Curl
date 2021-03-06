<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Async Test
 *
 * @package   frankmayer\ArangoDbPhpCoreCurl
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl\Tests\Integration;

require_once __DIR__ . '/ArangoDbPhpCoreCurlIntegrationTestCase.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Async;
use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Api\Rest\Document;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;
use function frankmayer\ArangoDbPhpCoreCurl\getClient;
use frankmayer\ArangoDbPhpCoreCurl\Protocols\Http\HttpResponse;


/**
 * Class AsyncTest
 * @package frankmayer\ArangoDbPhpCoreCurl
 */
class AsyncIntegrationTest extends
	ArangoDbPhpCoreCurlIntegrationTestCase
{
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


	/**
	 *
	 */
	public function testCreateCollectionAndSimpleAsyncDocumentCreation()
	{
		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

		$collectionOptions = ['waitForSync' => true];

		$collection = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $collection->create($collectionName, $collectionOptions);
		/** @var $responseObject HttpResponse */
		$body = $responseObject->body;

		static::assertArrayHasKey('code', json_decode($body, true));
		$decodedJsonBody = json_decode($body, true);
		static::assertEquals(200, $decodedJsonBody['code']);
		static::assertEquals($collectionName, $decodedJsonBody['name']);

		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

		$requestBody = ['name' => 'frank', '_key' => '1'];
		$document    = new Document($this->client);


		$responseObject = $document->create($collectionName, $requestBody, null, ['async' => true]);

		static::assertEquals(202, $responseObject->status);

		sleep(1);

		$document = new Document($this->client);

		$responseObject = $document->get($collectionName . '/1', $requestBody);

		$responseBody    = $responseObject->body;
		$decodedJsonBody = json_decode($responseBody, true);

		static::assertEquals($collectionName . '/1', $decodedJsonBody['_id']);
	}

	/**
	 *
	 */
	public function testCreateCollectionAndStoredAsyncDocumentCreation()
	{

		$job = new Async($this->client);
		$job->deleteJobResult('all');

		// todo 1 Frank Write real test for deleting job results with stamp
		$job->deleteJobResult('all', time());


		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

		$collectionOptions = ['waitForSync' => true];
		$collection        = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$responseObject = $collection->create($collectionName, $collectionOptions);

		$body = $responseObject->body;

		$decodedJsonBody = json_decode($body, true);
		static::assertEquals(200, $decodedJsonBody['code']);
		static::assertEquals($collectionName, $decodedJsonBody['name']);

		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';

		$requestBody = ['name' => 'frank', '_key' => '1'];
		$document    = new Document($this->client);

		$responseObject = $document->create($collectionName, $requestBody, null, ['async' => 'store']);

		static::assertEquals(202, $responseObject->status);

		sleep(1);

		$jobId    = $responseObject->headers['X-Arango-Async-Id'][0];
		$jobList  = $job->listJobResults('done', 1);
		$jobArray = json_decode($jobList->body, true);

		static::assertTrue(in_array($jobId, $jobArray, true));

		$jobResult = $job->fetchJobResult($responseObject->headers['X-Arango-Async-Id'][0]);
		static::assertSame($jobResult->headers['X-Arango-Async-Id'], $responseObject->headers['X-Arango-Async-Id']);
		static::assertArrayHasKey('X-Arango-Async-Id', $jobResult->headers);


		$document = new Document($this->client);

		$responseObject = $document->get($collectionName . '/1', $requestBody);

		$responseBody    = $responseObject->body;
		$decodedJsonBody = json_decode($responseBody, true);
		static::assertEquals($collectionName . '/1', $decodedJsonBody['_id']);
	}


	/**
	 *
	 */
	public function tearDown()
	{
		$collectionName = 'ArangoDB-PHP-Core-CollectionTestSuite-Collection';


		$collection = new Collection($this->client);

		/** @var $responseObject HttpResponse */
		$collection->drop($collectionName);
	}
}
