<?php

namespace Fahriztx\EzLaravelSsoClient\Core;

use Fahriztx\EzLaravelSsoClient\Utils\Token;

class EzLaravelSsoClient
{
	private $error = "";

	public function login($token)
	{
		$token = Token::checkToken($token);

		if (!$token['status']) {
			$this->setError($token['message']);
			return $token['status'];
		}

		return $token['data'];
	}

	public function getUser($email=null)
	{
		$signature = $this->generateSignature($email, $this->getClientSecret());
	    $client_id = $this->getClientId();

	    $res = $this->request('POST', $this->getServerUrl().'/api/v3/secure/getUser', [
	        'signature' => $signature, 
	        'client_id' => $client_id, 
	        'email' => $email
	    ]);

	    $userRes = $res->getBody();
	    $userRes = json_decode($userRes, true)['data'];
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
}