<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations
     * and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to
     * use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     */
    public array $default = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => '',
        'password' => '',
        'database' => '',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    /**
     * This database connection is used when
     * running PHPUnit database tests.
     */
    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => 'utf8_general_ci',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
    ];

    public function __construct()
    {
        parent::__construct();

        // Ensure that we always set the database group to 'tests' if automated test suite
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
            return;
        }

        // ตรวจสอบว่ากำลังทำงานอยู่บน Localhost หรือ Server จริง (Production)
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
        $isLocal = false;

        if (
            in_array($host, ['localhost', 'localhost:8080', '127.0.0.1', '::1'], true) ||
            (is_string($host) && (substr($host, -6) === '.local' || substr($host, -5) === '.test')) ||
            (is_cli() && (DIRECTORY_SEPARATOR === '\\' || strpos(__DIR__, 'wamp64') !== false))
        ) {
            $isLocal = true;
        }

        // หากระบุใน environment ชัดเจนว่าเป็น development
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development' && $host === 'localhost') {
            $isLocal = true;
        }

        // กรณีรันบน Hosting จริง (Production) ให้สลับไปใช้ฐานข้อมูล Production อัตโนมัติ
        if (!$isLocal || (defined('ENVIRONMENT') && ENVIRONMENT === 'production')) {
            $this->default['hostname'] = env('database.default.hostname', 'localhost');
            $this->default['database'] = env('database.default.database', 'phatthalun_newdb2026');
            $this->default['username'] = env('database.default.username', 'phatthalun_newdb');
            $this->default['password'] = env('database.default.password', 'hYxuV8ypi4');
            $this->default['DBDebug']  = false;
        }
    }
}
