<?
namespace Tickets\Db;

use Tickets\Exception\Db as DbException;

class User
{
    private $db = null;

    public function __construct(){
        $dbConnector = Connector::getInstance();
        $this->db = $dbConnector->getConnection();
    }

    public function authenticate($login, $password){

        $this->db->query('');
    }

    public function add($login, $password){
        $count = $this->db->query('select count() from t_users where tu_username ilike('.$this->db->real_escape_string($login).')');
        if($count){
            throw new DbException('Such user already exists');
        }
        

    }
}