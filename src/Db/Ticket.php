<?
namespace Tickets\Db;

use Tickets\Exception\Db as DbException;
use Tickets\Entity;

class Ticket
{
    private $db = null;

    public function __construct(){
        $dbConnector = Connector::getInstance();
        $this->db = $dbConnector->getConnection();
    }

    public function getTicketsList(){
        $res = $this->db->query('select * from t_tickets');
        if($obj = $res->fetch_object(Entity\Ticket::class)){
            $tickets[] = $obj;
            while ($obj = $res->fetch_object(Entity\Ticket::class)) {
                $tickets[] = $obj;
            }
            return $tickets;
//            die('<pre>' . print_r($tickets,1));
        }
        return null;
    }

    public function create(\Tickets\Entity\Ticket $ticketEntity){
        $ret = $this->db->query('INSERT INTO t_tickets (tt_title, tt_text, tt_date, tt_status) values (
                                \''.$this->db->real_escape_string($ticketEntity->title).'\',
                                \''.$this->db->real_escape_string($ticketEntity->text).'\',
                                \''.$this->db->real_escape_string($ticketEntity->date).'\',
                                \''.$this->db->real_escape_string($ticketEntity->status).'\')'
        );
        if(!$ret){
            if($this->db->error) {
                throw new DbException("Db error: " . $this->db->error);
            }
            return false;
        }
        return true;
    }
}