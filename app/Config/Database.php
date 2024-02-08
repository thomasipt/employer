<?php
    namespace Config;

    use CodeIgniter\Database\Config;

    class Database extends Config{
        public string $filesPath    =   APPPATH . 'Database' . DIRECTORY_SEPARATOR;
        public string $defaultGroup;

        public array $default = [
            'DSN'          => '',
            'hostname'     => 'localhost',
            'username'     => 'root',
            'password'     => '',
            'database'     => 'employer_kubu_id',
            'DBDriver'     => 'MySQLi',
            'DBPrefix'     => '',
            'pConnect'     => false,
            'DBDebug'      => true,
            'charset'      => 'utf8',
            'DBCollat'     => 'utf8_general_ci',
            'swapPre'      => '',
            'encrypt'      => false,
            'compress'     => false,
            'strictOn'     => false,
            'failover'     => [],
            'port'         => 3306,
            'numberNative' => false,
        ];

        public function __construct(){
            parent::__construct();
            $this->defaultGroup =   'default';
        }
    }
