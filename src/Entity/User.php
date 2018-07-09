<?php
Namespace Tickets\Entity;
use Tickets\Exception;

class User extends BaseEntity{
    public $dbPrefix = 'tu';
    public $id;
    public $login;
    public $password;
    public $rePassword;

    public function generateUserPassword($password){
        return md5($password);
    }

    public function testUserPassword($password){
        if($this->generateUserPassword($password) == $this->password){
            return true;
        }
        throw new Exception\Entity('Wrong password');
    }

    private function isValidEmail(){
        if (!is_string($this->login)) {
            throw new Exception\Entity('Email should be string');
        }

        $matches = array();

        if ((strpos($this->login, '..') !== false) or
            (!preg_match('/^(.+)@([^@]+)$/', $this->login, $matches))) {
            throw new Exception\Entity('Invalid email format');
        }

        $localPart = $matches[1];
        $hostname  = $matches[2];

        if ((strlen($localPart) > 64) || (strlen($hostname) > 255)) {
            throw new Exception\Entity('Email address is too long');
        }

        return true;
    }

    private function isValidPassword(){
        if ((strlen($this->password) < 6) || (strlen($this->password) > 64)) {
            throw new Exception\Entity('Password length should be from 6 to 64 characters');
        }
        return true;
    }

    private function isValidRePassword(){
        if (!$this->rePassword || $this->password != $this->rePassword){
            throw new Exception\Entity('Passwordis not fit to repeat password');
        }
        return true;
    }

    public function validate()
    {
        $this->isValidEmail();
        $this->isValidPassword();
        $this->isValidRePassword();
    }

    public function filter()
    {
        // TODO: Implement filter() method.
    }
}
