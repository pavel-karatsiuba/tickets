<?php

Namespace Tickets\Entity;
use Tickets\Exception;

class Ticket extends BaseEntity{
    public $dbPrefix = 'tt';
    const statuses = ['new', 'in action', 'done'];
    const defaultStatus = 'new';
    public $id;
    public $title;
    public $text;
    public $date;
    public $status;

    public function filterStatus(){
        $this->status = in_array($this->status, self::statuses)?$this->status:self::defaultStatus;
    }

    public function filter(){
        $this->title = htmlspecialchars($this->title);
        $this->text = htmlspecialchars($this->text);
        $this->title = mb_substr($this->title, 0, 127);
        $this->date = @strtotime($this->date);
        $this->filterStatus();
    }

    public function validateDate(){
        if(strtotime('today midnight') > $this->date){
            throw new Exception\Entity('Ticket cannot be created with past date');
        }
    }

    public function validateTitle(){
        if(empty($this->title)){
            throw new Exception\Entity('Please fill in title');
        }
    }

    public function validateText(){
        if(empty($this->text)){
            throw new Exception\Entity('Please fill in text');
        }
    }

    public function validate(){
        $this->validateDate();
        $this->validateTitle();
        $this->validateText();
        return true;
    }
}