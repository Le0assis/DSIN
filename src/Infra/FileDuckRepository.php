<?php

declare(strict_types=1);

namespace Src\Infra;


use Src\Domain\Entities\PrimordialDuck;
use Src\Domain\Entities\Location;
use Src\Domain\Entities\SuperPower;
use Src\Domain\Contracts\IPrimordialDuckRepository;

final class FileDuckRepository implements IPrimordialDuckRepository
{
    public function __construct(private string $filePath)
    {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($this->filePath)) {
            touch($this->filePath);
        }
    }

    public function save(PrimordialDuck $duck): void
    {
        $data = $duck->to_array();
        $json_line = json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
        file_put_contents($this->filePath, $json_line, FILE_APPEND);
    }

    public function find_all(): array
    {
        $ducks = [];

        foreach (file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $decoded = json_decode($line, true);
            if (is_array($decoded)) {
                $duck = new PrimordialDuck();
                $duck->set_id($decoded['id'] ?? 0);
                $duck->set_mac_drone($decoded['mac_drone'] ?? 0);
                $duck->set_name($decoded['name'] ?? '');
                $duck->set_height((float) ($decoded['height_cm'] ?? 0));
                $duck->set_weight((float) ($decoded['weight_g'] ?? 0));

                if (isset($decoded['location']) && is_array($decoded['location'])) {
                    $location = new Location();

                    $location->set_country_city($decoded['location']['country'], $decoded['location']['city'], );
                    $location->set_coord($decoded['location']['longitude'], $decoded['location']['latitude'], );
                    if ($decoded['location']['refer'] != null) {
                        $location->set_refer($decoded['location']['refer']);
                    }
                    $location->set_precision($decoded['location']['precision'], 'cm');

                    $duck->set_location($location);
                }

                $duck->set_status($decoded['status'] ?? 'desperto');
                if (isset($decoded['bpm'])) {
                    $duck->set_bpm((int) $decoded['bpm']);
                }
                $duck->set_mutation((int) ($decoded['mutations_quantity'] ?? 0));

                if (isset($decoded['super_poder'])) {
                    $super_power = new SuperPower(
                        $decoded['super_poder']['name'],
                        $decoded['super_poder']['description'],
                        $decoded['super_poder']['class']

                    );
                    $duck->set_super_power($super_power);
                }

                if (isset($decoded['classification'])) {
                    $duck->set_classify($decoded['classification']);
                }

                $ducks[] = $duck;
            }
        }

        return $ducks;
    }
    public function get_id(int $id)
    {
        $ducks = $this->find_all();

        foreach ($ducks as $duck) {
            if ($duck['id'] == $id) {
                return $duck;
            }
        }
    }

    public function set_id(): int
    {

        $ducks = $this->find_all();

        if (empty($ducks)) {
            return 1;
        }

        $last = end($ducks);
        return ($last['id'] ?? 0) + 1;
    }

}
