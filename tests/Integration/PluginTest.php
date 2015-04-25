<?php

/**
 * ArangoDB PHP Core Client Test-Suite: Plugin Test
 *
 * @package   frankmayer\ArangoDbPhpCore
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl;

require_once('ArangoDbPhpCoreCurlApiTestCase.php');
require __DIR__ . '/../../vendor/frankmayer/arangodb-php-core/tests/Integration/PluginTest.php';

use frankmayer\ArangoDbPhpCore\Api\Rest\Collection;
use frankmayer\ArangoDbPhpCore\Client;
use frankmayer\ArangoDbPhpCore\ClientOptions;
use frankmayer\ArangoDbPhpCore\Plugins\PluginManager;
use frankmayer\ArangoDbPhpCore\Plugins\TestPlugin;
use frankmayer\ArangoDbPhpCore\Tests\Integration\PluginIntegrationTest;
use frankmayer\ArangoDbPhpCoreCurl\Connectors\Connector;


/**
 * Class PluginTest
 * @package frankmayer\ArangoDbPhpCore
 */
class PluginTest extends PluginIntegrationTest
{
    /**
     * @var ClientOptions $clientOptions
     */
    public $clientOptions;

    /**
     * @var Client $client
     */
    public $client;


    /**
     *
     */
    public function setUp()
    {
        $connector    = new Connector();
        $this->client = getClient($connector);
    }

    // todo 1 Frank Complete plugin tests


    /**
     *
     */
    public function tearDown()
    {
    }
}