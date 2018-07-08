<?
namespace Tickets;
use \Tickets\Exception\Auth as AuthException;
class Auth{
    public function __construct(){

    }

    private function isValidEmail($email){
        if (!is_string($email)) {
            throw new AuthException('Email should be string');
        }

        $matches = array();

        if ((strpos($email, '..') !== false) or
            (!preg_match('/^(.+)@([^@]+)$/', $email, $matches))) {
            throw new AuthException('Invalid email format');
        }

        $localPart = $matches[1];
        $hostname  = $matches[2];

        if ((strlen($localPart) > 64) || (strlen($hostname) > 255)) {
            throw new AuthException('Email address is too long');
        }

        return true;
    }

    private function isValidPassword($password){
        if ((strlen($password) < 6) || (strlen($password) > 64)) {
            throw new AuthException('Password length should be from 6 to 64 characters');
        }
        return true;
    }

    private function isValidRePassword($password, $rePassword){
        if (!$rePassword || $password != $rePassword){
            throw new AuthException('Passwordis not fit to repeat password');
        }
        return true;
    }

    public function login($email, $password){
        $this->isValidEmail($email);
        $this->isValidPassword($password);
        $user = new Db\User();
        $userEntity = $user->getUser($email);
        $userEntity->testUserPassword($password);
        $user->login($userEntity->id, session_id());
    }

    public function signUp($email, $password, $rePassword){
        $this->isValidEmail($email);
        $this->isValidPassword($password);
        $this->isValidRePassword($password, $rePassword);

        $user = new Db\User();
        if(!$user->add($email, $password)){
            throw new AuthException('User can\'t be added');
        }
        return true;
    }

    public function getCurrentLoggedUser(){
        $user = new Db\User();
        return $user->getLoggedUser(session_id());
    }

    public function logout(){
        $user = new Db\User();
        $user->logout(session_id());
    }
}