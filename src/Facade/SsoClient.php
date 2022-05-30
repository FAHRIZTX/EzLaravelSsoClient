<?php

use Illuminate\Support\Facades\Facade;

class SsoClient
{
	protected static function getFacadeAccessor()
    {
        return 'ez_laravel_sso_client';
    }
}