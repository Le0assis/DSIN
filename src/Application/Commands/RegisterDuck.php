<?php

declare(strict_types=1);

namespace Src\Application\Commands;

use Src\Domain\Entities\Location;
use Src\Domain\Entities\PrimordialDuck;
use Src\Domain\Entities\SuperPower;

final class RegisterDuck
{
    public Location $loc;
    public PrimordialDuck $duck;
    public SuperPower $super_power;

    public function __construct()
    {
    }

    public function create_loc (array $data)
    {
        $loc = new Location();
        $loc->set_country_city(
            $data['country'],
            $data['city']
        );
        $loc->set_coord(
            $data['longitude'],
            $data['latitude']
        );
        if ($data['refer']) {
            $loc->set_refer($data['refer']);
        }
        $loc->set_precision($data['precision'], $data['precision_type']);

        $this->loc = $loc;

        return $loc;
    }

    public function create_duck (array $data)
    {
        $duck = new PrimordialDuck();
        if (!empty($data['name'])) {
            $duck->set_name($data['name']);
        }
        $duck->set_mac_drone($data['mac_drone']);
        $duck->set_height($data['height'], $data['height_type']);
        $duck->set_weight($data['weight'], $data['weight_type']);
        $duck->set_status($data['status']);
        if ($data['bpm'] != null) {
            $$duck->set_bpm($data['bpm']);
        }
        $duck->set_mutation ($data['mutations_quantity']);

        $this->duck = $duck;
    }

    public function create_super_power(array $data)
    {
        $sp = new SuperPower(
            name: $data['name'],
            description: $data['description'],
            class: $data['class']
        );
        
        $this->duck->set_super_power($sp);
        $this->super_power = $sp;
        return $sp;
    }

}


