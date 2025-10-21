<?

declare(strict_types=1);

namespace Src\Domain\Entities;

use Exception;

class Location
{
    public string $country;
    public string $city;
    public ?string $refer;
    public float $longitude;
    public float $latitude;
    public float $precision;
    public string $type_precision;

    public function set_country_city ($country, $city)
    {
        if ($country == null || $city == null) {
            throw new Exception("Dado obrigatorio");
        }
        $this->country = $country;
        $this->city = $city;
    }

    public function set_coord (float $longitude, float $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;   
    }

    public function set_refer (string $refer)
    {
        if (strlen($refer) < 2) {
            throw new Exception("Nome curto demais");
        }
        $this->refer = $refer;
    }

    public function set_precision (int $precision, string $type_precision)
    {
        if (strtolower($type_precision) == 'm') {
            $precision = $precision/100;
        }

        $this->precision = $precision;
    }
    public function to_array(): array
    {
        return [
            "city" => $this->city,
            "country" => $this->country,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "precision" => $this->precision,
            "refer" => $this->refer ? $this->refer : null,
        ];
    }

}

