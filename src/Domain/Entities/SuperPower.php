<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

class SuperPower
{
    public function __construct(
        public string $name, 
        public string $description, 
        public string $class 
        )
    {
        $this->name = $name;
        $this->$description = $description;
        $this->class = $class;
    }

   public function to_array(): array
    {
        return [
            "name" => $this->name,
            "description" => $this->description,
            "class" => $this->class,
        ];
    } 
}

