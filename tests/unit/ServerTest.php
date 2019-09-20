<?php

use App\Server;

class ServerTest extends \PHPUnit\Framework\TestCase
{
    public function testCanInitServerVariables()
    {
        $cpu = 4;
        $ram = 16;
        $hdd = 100;
        $server = new Server($cpu, $ram, $hdd);
        $this->assertEquals($cpu, $server->getCpu());
        $this->assertEquals($ram, $server->getRam());
        $this->assertEquals($hdd, $server->getHdd());
    }

    public function testCanThrowInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);
        $server = new Server(0, 0, 0);
    }

    public function testCanSetServerVariables()
    {
        $server = new Server(1, 1, 1);
        $cpu = 4;
        $server->setCpu($cpu);
        $ram = 16;
        $server->setRam($ram);
        $hdd = 100;
        $server->setHdd($hdd);
        $this->assertEquals($cpu, $server->getCpu());
        $this->assertEquals($ram, $server->getRam());
        $this->assertEquals($hdd, $server->getHdd());
    }
}
