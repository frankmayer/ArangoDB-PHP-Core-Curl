<?php

/**
 * ArangoDB PHP Core Client: HTTP Request
 *
 * @package   frankmayer\ArangoDbPhpCoreCurl
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl\Protocols\Http;

use frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequestInterface;


/**
 * HTTP-Request object that holds a request. Requests are in some cases not directly passed to the server,
 * for instance when a request is destined for a batch.
 *
 * @package frankmayer\ArangoDbPhpCoreCurl
 */
class HttpRequest extends
	\frankmayer\ArangoDbPhpCore\Protocols\Http\HttpRequest implements
	HttpRequestInterface
{
}