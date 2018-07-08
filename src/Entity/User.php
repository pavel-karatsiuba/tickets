<?
Namespace Tickets\Entity;
use Tickets\Exception;
class User{
    public $id;
    public $login;
    public $password;

    public function __set($name, $value){
        switch($name){
            case 'tu_id':
                $this->id = $value;
                break;
            case 'tu_username':
                $this->login = $value;
                break;
            case 'tu_password':
                $this->password = $value;
                break;
            default:
                $this->$name = $value;
                break;
        }
    }

    public function generateUserPassword($password){
        return md5($password);
    }

    public function testUserPassword($password){
        if($this->generateUserPassword($password) == $this->password){
            return true;
        }
        throw new Exception\Entity('Password mismatch');
    }
}