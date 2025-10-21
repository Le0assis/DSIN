<?php

declare(strict_types=1);

namespace Src\Domain\Contracts;
use Src\Domain\Entities\PrimordialDuck;
interface IPrimordialDuckRepository
{
    public function save(PrimordialDuck $duck): void;
    public function find_all (): array;
    public function set_id (): int;
}