<?php
declare(strict_types=1);

namespace App;

interface ServerInterface {
    /**
     * @return int
     */
    public function getCpu(): int;

    /**
     * @return int
     */
    public function getRam(): int;

    /**
     * @return int
     */
    public function getHdd(): int;
}