<?php
/**
 *
 * File: PromiseTest.php
 *
 * @package
 * @author Frank Mayer
 */

namespace frankmayer\ArangoDbPhpCoreCurl\Tests\Integration;


use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;
use function frankmayer\ArangoDbPhpCoreCurl\getClient;

require_once __DIR__ . '/ArangoDbPhpCoreCurlIntegrationTestCase.php';


class ExceptionIntegrationTest extends
	ArangoDbPhpCoreCurlIntegrationTestCase
{

	/**
	 * base URL part for cursor related operations
	 */
	const URL_CURSOR = '/_api/cursor';

	const API_COLLECTION = '/_api/collection';

	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_PUT = 'PUT';
	const METHOD_PATCH = 'PATCH';
	const METHOD_DELETE = 'DELETE';
	const METHOD_HEAD = 'HEAD';
	const METHOD_OPTIONS = 'OPTIONS';

	/**
	 * @var Client
	 */
	public $client;
	protected $connector;


	public function setUp()
	{
		$this->connector = new Connector();
		$this->client    = getClient($this->connector);
	}

	public function testTimeoutException()
	{
		//        $query = 'RETURN SLEEP(13)';
		//
		//        $statement = new Statement($this->connection, ["query" => $query]);
		//
		//        try {
		//            $statement->execute();
		//        } catch (ClientException $exception) {
		//            $this->assertEquals($exception->getCode(), 408);
		//            throw $exception;
		//        }
	}
}
