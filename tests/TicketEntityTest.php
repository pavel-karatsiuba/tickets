<?php
use PHPUnit\Framework\TestCase;

final class TicketEntityTest extends TestCase
{
    /**
     * @var Tickets\Entity\Ticket
     */
    private $ticketEntity = null;

    public function setUp()
    {
        $this->ticketEntity = new Tickets\Entity\Ticket();
    }

    public function testFilterData()
    {
        $title = '1this is the test for more then 64 characters 2this is the test for more then 64 characters 3this is the test for more then 64 characters 4this is the test for more then 64 characters 5this is the test for more then 64 characters 6this is the test for more then 64 characters ';
        $this->ticketEntity->date = '01.01.2015';
        $this->ticketEntity->title = $title;
        $this->ticketEntity->text = '';
        $this->ticketEntity->status = 'wrong status';

        $this->ticketEntity->filter();
        $this->assertEquals('1420070400', $this->ticketEntity->date, 'Date was converted successfully');
        $this->assertEquals(127, strlen($this->ticketEntity->title), 'Title was filtered successfully');
        $this->assertEquals('new', $this->ticketEntity->status, 'Status was filtered successfully');
        $this->expectException(\Tickets\Exception\Entity::class);
        $this->ticketEntity->validate();
    }
}

