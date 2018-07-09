<?php
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
        $res = $this->db->query('select * from t_tickets order by tt_date asc');
        if($obj = $res->fetch_object(Entity\Ticket::class)){
            $tickets[] = $obj;
            while ($obj = $res->fetch_object(Entity\Ticket::class)) {
                $tickets[] = $obj;
            }
            return $tickets;
        }
        return null;
    }

    /**
     * @param $id
     * @return null|object|Entity\Ticket
     */
    public function getTicketById($id){
        $res = $this->db->query('select * from t_tickets where tt_id like \''. $this->db->escape_string($id).'\'');
        if($ticket = $res->fetch_object(Entity\Ticket::class)){
            return $ticket;
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

    public function edit(\Tickets\Entity\Ticket $ticketEntity){
        $ret = $this->db->query('UPDATE t_tickets SET 
                                tt_title = \''.$this->db->real_escape_string($ticketEntity->title).'\',
                                tt_text = \''.$this->db->real_escape_string($ticketEntity->text).'\',
                                tt_date = \''.$this->db->real_escape_string($ticketEntity->date).'\',
                                tt_status = \''.$this->db->real_escape_string($ticketEntity->status).'\'
                                where tt_id like \''. $this->db->escape_string($ticketEntity->id).'\''
        );
        if(!$ret){
            if($this->db->error) {
                throw new DbException("Db error: " . $this->db->error);
            }
            return false;
        }
        return true;
    }

    public function delete(\Tickets\Entity\Ticket $ticketEntity){
        $ret = $this->db->query('DELETE from t_tickets
                                where tt_id like \''. $this->db->escape_string($ticketEntity->id).'\''
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