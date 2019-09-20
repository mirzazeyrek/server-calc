<?php
declare(strict_types=1);

namespace App;

use App\Server;
use App\VirtualMachine;

class Calculator
{

    /* @var Server */
    private $server;

    /* @var Server */
    private $serverAvailableResources;

    // An interesting and more memory efficient solution could be using Iterators but now keeping it simple.
    /* @var virtualMachine[] */
    private $virtualMachines = [];

    public function __construct(Server $server, array $virtualMachines)
    {
        $this->setServer($server);
        if (!count($virtualMachines)) {
            throw new \InvalidArgumentException('Virtual machine(s) must be provided.');
        }
        $this->setVirtualMachines($virtualMachines);
    }

    /**
     * @return \App\Server
     */
    public function getServer(): \App\Server
    {
        return $this->server;
    }

    public function setServer(Server $server): void
    {
        $this->server = $server;
        // Whenever we set a new server we should set available resources as the same.
        $this->resetServersAvailableResources(clone $server);
    }

    /**
     * @return \App\Server
     */
    public function getServersAvailableResources(): \App\Server
    {
        return $this->serverAvailableResources;
    }

    public function resetServersAvailableResources(): void
    {
        $this->serverAvailableResources = clone $this->getServer();
    }

    /**
     * @return array[virtualMachine]
     */
    public function getVirtualMachines(): array
    {
        return $this->virtualMachines;
    }

    public function setVirtualMachines(array $virtualMachines): void
    {
        $this->virtualMachines = [];
        /* @var $virtualMachines virtualMachine[] */
        foreach ($virtualMachines as $virtualMachine) {
            if (!$virtualMachine instanceof VirtualMachine) {
                throw new \Exception("Array of virtualMachines must contain only virtualMachine objects.");
            }
            $this->addVirtualMachine($virtualMachine);
        }
    }

    public function addVirtualMachine(VirtualMachine $virtualMachine): void
    {
        $this->virtualMachines[] = $virtualMachine;
    }

    public function calculateNeededServers(): int
    {
        $neededServerCount = 0;
        $virtualMachines = $this->getVirtualMachines();
        /* @var $virtualMachines virtualMachine[] */
        foreach ($virtualMachines as $virtualMachine) {
            if ($this->isVirtualMachineFits($this->getServer(), $virtualMachine)) {
                $neededServerCount = (0 === $neededServerCount) ? 1 : $neededServerCount;
                if (!$this->isVirtualMachineFits($this->getServersAvailableResources(), $virtualMachine)) {
                    $neededServerCount++;
                    $this->resetServersAvailableResources();
                }
                $this->allocateAvailableResources($virtualMachine);
            }
        }

        return $neededServerCount;
    }

    public function allocateAvailableResources(VirtualMachine $virtualMachine): void
    {
        $availableResources = $this->getServersAvailableResources();
        $availableResources->setCpu($availableResources->getCpu() - $virtualMachine->getCpu());
        $availableResources->setRam($availableResources->getRam() - $virtualMachine->getRam());
        $availableResources->setHdd($availableResources->getHdd() - $virtualMachine->getHdd());
    }

    public function isVirtualMachineFits(Server $server, VirtualMachine $virtualMachine): bool
    {
        if ($server->getRam() < $virtualMachine->getRam()) {
            return false;
        }
        if ($server->getCpu() < $virtualMachine->getCpu()) {
            return false;
        }
        if ($server->getHdd() < $virtualMachine->getHdd()) {
            return false;
        }

        return true;
    }
}
