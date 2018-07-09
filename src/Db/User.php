<?php
namespace Tickets\Db;

use Tickets\Exception\Db as DbException;
use Tickets\Entity;

class User
{
    private $db = null;

    public function __construct(){
        $dbConnector = Connector::getInstance();
        $this->db = $dbConnector->getConnection();
    }

    /**
     * @param $login
     * @return object|Entity\User
     * @throws DbException
     */
    public function getUser($login){
        $res = $this->db->query('select * from t_users where tu_username like \''.$this->db->real_escape_string($login).'\'');
        if($user = $res->fetch_object(Entity\User::class)){
            return $user;
//            die('<pre>' . print_r($user,1));
        }else{
            throw new DbException('User not found');
        }
    }

    /**
     * @param $login
     * @return object|Entity\User
     */
    public function getLoggedUser($loggedUserId){
        $res = $this->db->query('select * from t_logged_users left join t_users on tu_id=tlu_user_id where tlu_session_id like \''.$this->db->real_escape_string($loggedUserId).'\'');
        if($res){
            $user = $res->fetch_object(Entity\User::class);
        }else{
            $user = new Entity\User();
        }
        return $user;
    }

    public function login($userId, $sessionId){
        $this->db->query('INSERT INTO t_logged_users (tlu_user_id, tlu_date, tlu_session_id) values (
            \''.$this->db->real_escape_string($userId).'\',
            \''.$this->db->real_escape_string(time()).'\',
            \''.$this->db->real_escape_string($sessionId).'\')');
    }

    public function logout($sessionId){
        $this->db->query('delete from t_logged_users where tlu_session_id like \''.$this->db->real_escape_string($sessionId).'\'');
    }

    public function add($login, $password){
        $result = $this->db->query('select count(*) from t_users where tu_username like \''.$this->db->real_escape_string($login).'\'');
        if($result->fetch_row()[0]){
            throw new DbException('Such user already exists');
        }
        $ret = $this->db->query('INSERT INTO t_users (tu_username, tu_password) values (\''.$this->db->real_escape_string($login).'\',\''.$this->db->real_escape_string(md5($password)).'\')');
        if(!$ret){
            if($this->db->error) {
                throw new DbException("Db error: " . $this->db->error);
            }
            return false;
        }
        return true;
    }
}