<?
Namespace Tickets\Entity;
use Tickets\Exception;
class Ticket{
    private $dbPrefix = 'tt';
    const statuses = ['new', 'in action', 'done'];
    const defaultStatus = 'new';
    public $id;
    public $title;
    public $text;
    public $date;
    public $status;

    public function __set($name, $value){
        if(strpos($name, $this->dbPrefix) === 0){
            $name = substr($name, strlen($this->dbPrefix . '_'));
        }
        $this->$name = $value;
    }

    public function filter(){
        $this->title = mb_substr($this->title, 0, 127);
        $this->date = @strtotime($this->date);
        $this->status = in_array($this->status, self::statuses)?$this->status:self::defaultStatus;
    }

    public function validateDate(){
        if(strtotime('today midnight') > $this->date){
            throw new Exception\Entity('Ticket cannot be created with past date ' . $this->date);
        }
    }

    public function validateTitle(){
        if(empty($this->title)){
            throw new Exception\Entity('Please fill in title');
        }
    }

    public function validateText(){
        if(empty($this->title)){
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