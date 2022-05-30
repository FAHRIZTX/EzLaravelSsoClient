<?php

namespace Fahriztx\EzLaravelSsoClient\Facade;

use Illuminate\Support\Facades\Facade;

class SsoClient extends Facade
{
	protected static function getFacadeAccessor()
    {
        return 'ez_laravel_sso_client';
    }
}