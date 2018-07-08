<?
Namespace Tickets;
class Ticket{
    public function loadTickets(){
        $ticket = new Db\Ticket();
        return $ticket->getTicketsList();
    }

    public function save(\Tickets\Entity\Ticket $ticketEntity){
        $ticketEntity->filter();
        $ticketEntity->validate();
        $ticket = new \Tickets\Db\Ticket();
        if($ticketEntity->id){

        }else{
            $ticket->create($ticketEntity);
        }
    }
}