<?php
use PHPUnit\Framework\TestCase;

final class UserEntityTest extends TestCase
{
    /**
     * @var Tickets\Entity\User
     */
    private $userEntity = null;

    public function setUp()
    {
        $this->userEntity = new Tickets\Entity\User();
    }



    public function testWrongValidation()
    {
        $this->userEntity->login = null;
        $this->userEntity->password = null;
        $this->userEntity->rePassword = null;

        $this->expectException(\Tickets\Exception\Entity::class);
        $this->userEntity->validate();
    }
}

