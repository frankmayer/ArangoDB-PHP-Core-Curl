<?php

/**
 * ArangoDB PHP Core Client: Curl HTTP Connector
 *
 * @package   frankmayer\ArangoDbPhpCoreCurl
 * @author    Frank Mayer
 * @copyright Copyright 2013, FRANKMAYER.NET, Athens, Greece
 */

namespace frankmayer\ArangoDbPhpCoreCurl\Connectors;

use frankmayer\ArangoDbPhpCore\ClientOptions;
use frankmayer\ArangoDbPhpCore\Connectors\AbstractHttpConnector;
use frankmayer\ArangoDbPhpCore\Protocols\Http\AbstractHttpRequest;
use frankmayer\ArangoDbPhpCore\ServerException;


/**
 * This connector acts as a wrapper to PHP's curl class.
 * It must be injected into the client object upon the client's creation.
 *
 * @package frankmayer\ArangoDbPhpCoreCurl
 */
class Connector extends
	AbstractHttpConnector
{
	/**
	 * @param AbstractHttpRequest $request
	 *
	 * @return mixed
	 * @throws ServerException
	 *
	 */
	public function request(AbstractHttpRequest $request)
	{
		$curlHeaders = [];

		$ch   = curl_init($request->address);
		$body = $request->body;

		$request->headers['Content-Length'] = strlen($body);

		foreach ($request->headers as $headerKey => $headerVal)
		{
			$curlHeaders[] = $headerKey . ': ' . $headerVal;
		}

		curl_setopt_array(
			$ch,
			[
				CURLOPT_CUSTOMREQUEST  => $request->method,
				CURLOPT_VERBOSE        => $this->verboseLogging,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER         => true,
				CURLOPT_POSTFIELDS     => $body,
				CURLOPT_HTTPHEADER     => $curlHeaders,
			]
		);

		$clientOptions = $request->client->clientOptions;
		// Ignoring this, as the server needs to have authentication enabled in order to run through this.
		// @codeCoverageIgnoreStart
		if (isset ($clientOptions[ClientOptions::OPTION_AUTH_TYPE]))
		{
			if (strtolower($clientOptions[ClientOptions::OPTION_AUTH_TYPE]) === 'basic')
			{
				curl_setopt(
					$ch,
					CURLOPT_USERPWD,
					$clientOptions[ClientOptions::OPTION_AUTH_USER] . ':' . $clientOptions[ClientOptions::OPTION_AUTH_PASSWD]
				);
			}
		}
		// @codeCoverageIgnoreEnd

		$response = curl_exec($ch);
		if ($response === false)
		{
			throw new ServerException(curl_error($ch), curl_errno($ch));
		}

		return $response;
	}
}