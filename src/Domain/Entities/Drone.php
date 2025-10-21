<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

class Drone
{
    public int $id;
    public int $serial_number;
    public string $marca;
    public string $faber;
    public string $origin_country;

}
