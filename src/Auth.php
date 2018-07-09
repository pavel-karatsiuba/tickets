<?php
namespace Tickets;
use Tickets\Entity;
use \Tickets\Exception\Auth as AuthException;
class Auth{
    public function __construct(){

    }

    public function login(Entity\User $userEntity){
        $user = new Db\User();
        $userEntityFromDb = $user->getUser($userEntity->login);
        $userEntityFromDb->testUserPassword($userEntity->password);
        $user->login($userEntityFromDb->id, session_id());
    }

    public function signUp(Entity\User $userEntity){
        $user = new Db\User();
        if(!$user->add($userEntity->login, $userEntity->password)){
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