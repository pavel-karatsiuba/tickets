<?php
namespace Tickets\Entity;

abstract class BaseEntity{
    public $dbPrefix;
    public function __set($name, $value){
        if(strpos($name, $this->dbPrefix) === 0){
            $name = substr($name, strlen($this->dbPrefix . '_'));
        }
        $this->$name = $value;
    }
    abstract public function validate();
    abstract public function filter();

}