<?
namespace Tickets\Db;

class Connector
{
    private $_connection;
    private static $_instance;
    private $_host = "127.0.0.1";
    private $_username = "root";
    private $_password = "password";
    private $_database = "tickets";

    private function __construct() {
        $this->_connection = new \mysqli($this->_host, $this->_username, $this->_password, $this->_database);
        if(mysqli_connect_error()) {
            throw new \Tickets\Exception\Db("Failed to conencto to MySQL: " . mysql_connect_error());
        }
    }

    private function __clone() { }

    public static function getInstance() {
        if(!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getConnection() {
        return $this->_connection;
    }
}