<?

declare(strict_types=1);

namespace Src\Domain\Entities;

use Exception;

class PrimordialDuck 
{
    public int $id;
    public int $mac_drone;
    public ?string $name;
    public int $height;
    public string $height_type;
    public int $weight;
    public string $weight_type;
    public Location $location;
    public string $status;
    public ?int $bpm;
    public int $mutations_quantity;
    public ?array $super_power;

    public function set_id(int $id)
    {
        $this->id = $id;
    }

    public function set_mac_drone (int $mac_drone)
    {
        if ($mac_drone < 0) {
            throw new Exception("Mac invalido");
        }
        $this->$mac_drone = $mac_drone;
    }

    public function set_height (int $height, string $height_type = 'm')
    {
        if ($height_type === 'ft'){
            $height = $height * 0.3048;
        }
        $this->height = $height;
        $this->height_type = 'cm';
    }

    public function set_weight (int $weight, string $weight_type = 'g')
    {
        if ($weight_type === 'lb') {
            $weight = $weight *0.453592;
        }
        $this->weight = $weight;
        $this->weight_type = 'g';
    }

    public function set_location (Location $location)
    {
        $this->location = $location;
    }

    public function set_status (string $status)
    {
        if (
            strtolower($status) != "desperto" ||
            strtolower($status) != "em transe" ||
            strtolower($status) != "hibernação profunda"
        ) {
            throw new Exception("Status invalido");
        }

        $this->status = $status;
    }

    public function set_bpm (int $bpm) {
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
        $this->super_power;
    }

}