<?php

namespace Fahriztx\EzLaravelSsoClient\Core;

use Fahriztx\EzLaravelSsoClient\Utils\Token;
use GuzzleHttp\Client as HTTPClient;

class EzLaravelSsoClient
{
	private $error = false;
	private $payload = null;

	public function login($token)
	{
		$token = Token::checkToken($token);

		if (!$token['status']) {
			$this->setError($token['message']);
			return $token['status'];
		}

		$this->setPayload($token['data']);

		return $this;
	}

	public function getPayload()
	{
		return $this->payload;
	}

	public function setPayload($payload=null)
	{
		$this->payload = $payload;
	}

	public function getEmail()
	{
		return $this->payload->sub;
	}

	public function getUser()
	{
		$signature = $this->generateSignature($this->getEmail(), $this->getClientSecret());
	    $client_id = $this->getClientId();

	    try {
		    $res = $this->request('POST', $this->getServerUrl().'/api/v3/secure/getUser', [
		        'signature' => $signature, 
		        'client_id' => $client_id, 
		        'email' => $this->getEmail()
		    ], ['Accept' => 'application/json']);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
        	$errM = json_decode($e->getResponse()->getBody()->getContents())->message;
            throw new \Exception("Server Response Error : ".$errM);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
        	$errM = json_decode($e->getResponse()->getBody()->getContents())->message;
            throw new \Exception("Server Response Error : ".$errM);
        }


	    $userRes = $res->getBody();
	    $userRes = json_decode($userRes, true)['data'];

	    return $userRes;
	}

	public function generateSignature($string, $secret)
	{
		return Token::generateSignature($string, $secret);
	}

	public function getClientId()
	{
		return config('ezlaravelssoclient.client_id');
	}

	public function getClientSecret()
	{
		return config('ezlaravelssoclient.client_secret');
	}

	public function getServerUrl()
	{
		return config('ezlaravelssoclient.sso_server_url');
	}

	public function getError()
	{
		return $this->error;
	}

	public function setError($msg=null)
	{
		$this->error = $msg;
	}

	private function request($method='GET', $url=null, $data=[], $headers=[])
    {
        $client = new HTTPClient();

        $response = $client->request($method, $url, [
                        'json' => $data,
                        'headers' => array_merge([
                            'User-Agent' => 'LekJukiApp/'.date('Y'),
                        ], $headers)
                    ]);

        return $response;
    }
}