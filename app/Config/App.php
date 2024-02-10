<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    public $appName         =   'Employer Kubu ID';
    public $appDescription  =   'Website Lowongan Pekerjaan';

    public $appNickName     =   'employer';
    public $emailAccountUsername; #email address
    public $emailAccountPassword; #sandi aplikasi (bukan sandi email)
    public string $baseURL = 'https://employer.kubu.id/';
    public array $allowedHostnames = [];
    public string $indexPage = '';
    public string $uriProtocol = 'REQUEST_URI';
    public string $defaultLocale = 'en';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['en'];
    public string $appTimezone = 'UTC';
    public string $charset = 'UTF-8';
    public bool $forceGlobalSecureRequests = false;
    public array $proxyIPs = [];
    public bool $CSPEnabled = false;
}
