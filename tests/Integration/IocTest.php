<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Batch Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl;

require_once('ArangoDbPhpCoreCurlApiTestCase.php');
require __DIR__ . '/../../vendor/frankmayer/arangodb-php-core/tests/Integration/IocTest.php';

use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientException;
use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequestInterface;
use frankmayer\ArangoDbPhpCore\Protocols\ResponseInterface;
use frankmayer\ArangoDbPhpCore\Tests\Integration\IocIntegrationTest;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;
use frankmayer\ArangoDbPhpCoreCurl\Protocols\Http\HttpRequest;


/**
 * Class IocTest
 * @package frankmayer\ArangoDbPhpCore
 */
class IocTest extends IocIntegrationTest
{
    /**
     * @var Client
     */
    public $client;
    /**
     * @var
     */
    public $collectionNames;

    /**
     * @var HttpRequestInterface
     */
    public $request;

    /**
     * @var ResponseInterface
     */
    public $response;
    /**
     * @var
     */
    public $connector;


    /**
     *
     */
    public function setUp()
    {
        $connector       = new Connector();
        $this->connector = $connector;

        $this->client = $this->client = getClient($connector);
        $this->client->bind(
            'Request',
            function () {
                $request = $this->client->getRequest();

                return $request;
            }
        );
    }
}