<?php
declare(strict_types=1);

use App\Calculator;
use App\Server;
use App\VirtualMachine;

class CalculatorTest extends \PHPUnit\Framework\TestCase
{
    public function testCanInitServerAndVM()
    {
        $calculator = new Calculator(new Server(2, 4, 6), [new VirtualMachine(1, 2, 3)]);
        $server = $calculator->getServer();
        $this->assertEquals(2, $server->getCpu());
        $this->assertEquals(4, $server->getRam());
        $this->assertEquals(6, $server->getHdd());

        $vm = $calculator->getVirtualMachines()[0];
        $this->assertEquals(1, $vm->getCpu());
        $this->assertEquals(2, $vm->getRam());
        $this->assertEquals(3, $vm->getHdd());

        // Empty virtualMachines array should throw an Exception.
        $this->expectException(\Exception::class);
        $calculator = new Calculator(new Server(2, 4, 6), []);
    }

    public function testCanSetAndGetServers()
    {
        $server = new Server(2, 4, 6);
        $virtualMachine = new VirtualMachine(1, 2, 3);
        $calculator = new Calculator($server, [$virtualMachine]);

        $this->assertEquals($server, $calculator->getServer());

        $newServer = new Server(1, 2, 3);
        $calculator->setServer($newServer);
        $this->assertEquals($newServer, $calculator->getServer());
    }

    public function testCanSetAndGetVirtualMachines()
    {
        $server = new Server(2, 4, 6);
        $virtualMachines = [new VirtualMachine(1, 2, 3), new VirtualMachine(1, 2, 3)];
        $calculator = new Calculator($server, $virtualMachines);
        $this->assertEquals($virtualMachines, $calculator->getVirtualMachines());

        $newVirtualMachines = [new VirtualMachine(1, 2, 3)];
        $calculator->setVirtualMachines($newVirtualMachines);
        $this->assertEquals($newVirtualMachines, $calculator->getVirtualMachines());

        // Should catch an exception.
        $this->expectException(\Exception::class);
        $newVirtualMachines = ['1',2,3];
        $calculator->setVirtualMachines($newVirtualMachines);
    }

    public function testCanAllocateAndResetResources()
    {
        $calculator = new Calculator(new Server(2, 4, 6), [new VirtualMachine(1, 2, 3)]);
        $calculator->allocateAvailableResources($calculator->getVirtualMachines()[0]);
        $serversAvailableRes = $calculator->getServersAvailableResources();

        $this->assertEquals(1, $serversAvailableRes->getCpu());
        $this->assertEquals(2, $serversAvailableRes->getRam());
        $this->assertEquals(3, $serversAvailableRes->getHdd());

        $calculator->resetServersAvailableResources();
        $serversAvailableRes = $calculator->getServersAvailableResources();
        $this->assertEquals(2, $serversAvailableRes->getCpu());
        $this->assertEquals(4, $serversAvailableRes->getRam());
        $this->assertEquals(6, $serversAvailableRes->getHdd());
    }

    public function testCanDetermineIfResourcesFits()
    {
        $virtualMachines = [
            new VirtualMachine(2, 2, 2),
            new VirtualMachine(3, 2, 2),
            new VirtualMachine(2, 3, 2),
            new VirtualMachine(2, 2, 3)
        ];
        $calculator = new Calculator(new Server(2, 2, 2), $virtualMachines);
        // Should fit.
        $fitStatus = $calculator->isVirtualMachineFits($calculator->getServer(), $calculator->getVirtualMachines()[0]);
        $this->assertTrue($fitStatus);

        // Should not fit. Not enough CPU
        $fitStatus = $calculator->isVirtualMachineFits($calculator->getServer(), $calculator->getVirtualMachines()[1]);
        $this->assertFalse($fitStatus);

        // Should not fit. Not enough RAM.
        $fitStatus = $calculator->isVirtualMachineFits($calculator->getServer(), $calculator->getVirtualMachines()[2]);
        $this->assertFalse($fitStatus);

        // Should not fit. Not enough HDD.
        $fitStatus = $calculator->isVirtualMachineFits($calculator->getServer(), $calculator->getVirtualMachines()[3]);
        $this->assertFalse($fitStatus);
    }

    public function testCanCalculateNeededServers()
    {
        // Should return 0.
        $server = new Server(1, 1, 1);
        $virtualMachines = [new VirtualMachine(1, 2, 3), new VirtualMachine(3, 4, 5)];
        $calculator = new Calculator($server, $virtualMachines);
        $this->assertEquals(0, $calculator->calculateNeededServers());
        // Should return 1.
        $server = new Server(1, 1, 1);
        $virtualMachines =  [new VirtualMachine(1, 1, 1), new VirtualMachine(3, 4, 5)];
        $calculator = new Calculator($server, $virtualMachines);
        $this->assertEquals(1, $calculator->calculateNeededServers());
        // Should return 3.
        $server =  new Server(3, 3, 3);
        $virtualMachines = [
            new VirtualMachine(4, 4, 5),
            new VirtualMachine(3, 3, 3),
            new VirtualMachine(3, 3, 3),
            new VirtualMachine(3, 3, 3)
        ];
        $calculator = new Calculator($server, $virtualMachines);
        $this->assertEquals(3, $calculator->calculateNeededServers());
    }
}
