<?php
/**
 *
 * File: PromiseTest.php
 *
 * @package
 * @author Frank Mayer
 */

namespace frankmayer\ArangoDbPhpCoreCurl;

require_once('ArangoDbPhpCoreCurlApiTestCase.php');

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;


class ExceptionTest extends ArangoDbPhpCoreCurlApiTestCase
{

    /**
     * base URL part for cursor related operations
     */
    const URL_CURSOR = '/_api/cursor';

    const API_COLLECTION = '/_api/collection';

    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_PATCH   = 'PATCH';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';

    /**
     * @var Client
     */
    public $client;


    public function setUp()
    {
        $connector    = new Connector();
        $this->client = getClient($connector);
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