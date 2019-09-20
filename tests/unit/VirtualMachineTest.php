<?php

use App\VirtualMachine;

class VirtualMachineTest extends \PHPUnit\Framework\TestCase
{
    public function testCanSetServerVariables()
    {
        $cpu = 4;
        $ram = 16;
        $hdd = 100;
        $vm = new VirtualMachine($cpu, $ram, $hdd);
        $this->assertEquals($cpu, $vm->getCpu());
        $this->assertEquals($ram, $vm->getRam());
        $this->assertEquals($hdd, $vm->getHdd());
    }
}
