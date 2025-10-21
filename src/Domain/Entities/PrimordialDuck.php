<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

use Exception;

class PrimordialDuck 
{
    public int $id;
    public int $mac_drone;
    public ?string $name;
    public float $height;
    public float $weight;
    public ?Location $location = null;
    public string $status;
    public ?float $bpm = null;
    public int $mutations_quantity;
    public ?SuperPower $super_power = null;

    public function set_id(int $id)
    {
        $this->id = $id;
    }

    public function set_mac_drone (int $mac_drone)
    {
        if ($mac_drone < 0) {
            throw new Exception("Mac invalido");
        }
        $this->mac_drone = $mac_drone;
    }
    public function set_name(string $name)
    {
        $this->name = $name;
    }

    public function set_height (float $height, string $height_type = 'm')
    {
        if ($height_type === 'ft'){
            $height = $height * 30.48;
        }
        $this->height = $height;

    }

    public function set_weight (float $weight, string $weight_type = 'g')
    {
        if ($weight_type === 'lb') {
            $weight = $weight * 453.592;
        }
        $this->weight = $weight;

    }

    public function set_location (Location $location)
    {
        $this->location = $location;
    }

    public function set_status (string $status)
    {
        if (
            strtolower($status) != "desperto" &&
            strtolower($status) != "em transe" &&
            strtolower($status) != "hibernação profunda"
        ) {
            throw new Exception("Status invalido");
        }

        $this->status = $status;
    }

    public function set_bpm (float $bpm) {
        if ($bpm <= 0) {
            throw new Exception("Bpm invalido");
        }
        $this->bpm = $bpm;
    } 

    public function set_mutation (int $mutations_quantity)
    {
        if ($mutations_quantity < 0) {
            throw new Exception("Invalido");
        }
        $this->mutations_quantity = $mutations_quantity;
    }

    public function set_super_power (SuperPower $super_power)
    {
        $this->super_power = $super_power;
    }

    public function to_array ()
    {
        return[
            "id" => $this->id,
            "mac_drone" => $this->mac_drone, 
            "name" => $this->name,
            "height_cm" => $this->height, // Já está em cm devido aos setters
            "weight_g" => $this->weight,     // Já está em g devido aos setters
            "location" => $this->location ? $this->location->to_array(): null,
            "status" => $this->status,
            "bpm" => $this->bpm ? $this->bpm : null,
            "mutations_quantity" => $this->mutations_quantity,
            "super_poder" => $this->super_power ? $this->super_power->to_array() : null,

        ];
        

    }

}