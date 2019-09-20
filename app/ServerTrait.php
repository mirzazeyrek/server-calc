<?php

namespace App;

trait ServerTrait
{
    /* @var integer */
    private $cpu;

    /* @var integer */
    private $ram;

    /* @var integer */
    private $hdd;

    public function __construct(int $cpu, int $ram, int $hdd)
    {
        if (1>$cpu || 1>$ram || 1>$hdd) {
            throw new \InvalidArgumentException('All parameters must be bigger than 0.');
        }
        $this->cpu = $cpu;
        $this->ram = $ram;
        $this->hdd = $hdd;
    }

    /**
     * @return int
     */
    public function getCpu(): int
    {
        return $this->cpu;
    }

    /**
     * @return int
     */
    public function getRam(): int
    {
        return $this->ram;
    }

    /**
     * @return int
     */
    public function getHdd(): int
    {
        return $this->hdd;
    }
}
