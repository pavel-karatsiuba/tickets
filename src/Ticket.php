<?php
Namespace Tickets;
use Tickets\Exception\Entity;

class Ticket{
    public function loadTickets(){
        $ticket = new Db\Ticket();
        return $ticket->getTicketsList();
    }

    public function loadTicket($id){
        $ticket = new Db\Ticket();
        $ticketObj = $ticket->getTicketById($id);
        if(is_null($ticketObj)){
            throw new Entity('Ticket with such ID is not exists');
        }
        return $ticketObj;
    }

    public function save(\Tickets\Entity\Ticket $ticketEntity){
        $ticket = new \Tickets\Db\Ticket();
        if($ticketEntity->id){
            $ret = $ticket->edit($ticketEntity);
            if(!$ret){
                throw new Entity('Can\'t edit the ticket!');
            }
        }else{
            $ticket->create($ticketEntity);
        }
    }

    public function delete(\Tickets\Entity\Ticket $ticketEntity){
        $ticket = new \Tickets\Db\Ticket();
        $ret = $ticket->delete($ticketEntity);
        if(!$ret){
            throw new Entity('Can\'t delete the ticket!');
        }
    }
}