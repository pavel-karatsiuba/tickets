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

    public function login($email, $password){
        $this->isValidEmail($email);
        $this->isValidPassword($password);

    }
}