<?php

use Entity\User;
use Connection\DatabaseSingleton;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testMockCreateNewUser()
    {
        $faker = Faker\Factory::create();

        $user = new User();
        $user->setName($faker->name);
        $user->setEmail($faker->email);
        $user->setLogin($faker->userName);

        $mock = $this->getMockBuilder("Facade\UserFacade", ["save"])
                     ->setConstructorArgs([
                         DatabaseSingleton::getInstance()
                     ])
                     ->getMock();

        $mock->expects($this->any())
             ->method("save")
             ->will($this->returnValue($user))->getMatcher();
    }
}