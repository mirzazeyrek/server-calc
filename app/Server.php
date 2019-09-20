<?php
declare(strict_types=1);

namespace App;

class Server implements ServerInterface
{

    use ServerTrait;

    /**
     * @return int
     */
    public function getCpu(): int
    {
        return $this->cpu;
    }

    /**
     * @param int $cpu
     */
    public function setCpu(int $cpu): void
    {
        $this->cpu = $cpu;
    }

    /**
     * @return int
     */
    public function getRam(): int
    {
        return $this->ram;
    }

    /**
     * @param int $ram
     */
    public function setRam(int $ram): void
    {
        $this->ram = $ram;
    }

    /**
     * @return int
     */
    public function getHdd(): int
    {
        return $this->hdd;
    }

    /**
     * @param int $hdd
     */
    public function setHdd(int $hdd): void
    {
        $this->hdd = $hdd;
    }
}
