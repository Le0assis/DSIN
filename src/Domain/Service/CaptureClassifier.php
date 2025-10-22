<?php

declare (strict_types=1);

namespace Src\Domain\Service;

use Src\Domain\Entities\PrimordialDuck;
final class CaptureClassifier
{
    private PrimordialDuck $primordialDuck;

    private int $risk = 0;
    private int $scientifc_gain = 0;
    private int $operacional_risk = 0;
    static float $dsinLatitude = -22.23354373725337;
    static float $dsinLongitude = -49.93411930338776;
    public array $operacional;
    public function __construct(PrimordialDuck $primordialDuck)
    {
        $this->primordialDuck = $primordialDuck;
    }
    //Operacional
    private function calculate_status()
    {
        $status = $this->primordialDuck->status;

        if ($status === 'desperto') {
            return 10;
        } elseif($status === 'em transe') {
            return 5;
        } elseif ($status === 'hibernação profunda') {
            return 1;
        }
        return 0;
    }

    private function calculate_bpm ()
    {
        $bpm = $this->primordialDuck->bpm;
        $status = $this->primordialDuck->status;
        
        if ($status === 'em transe'){
            if ($bpm > 80) {
                return 3;
            } elseif ($bpm > 50 && $bpm < 80) {
                return 2;
            } elseif ($bpm <= 50) {
                return 1;
            }
        }
        return 0;
    }

    private function calculate_risk ()
    {   
        $super_power_risk = 0;
        $status_risk = $this->calculate_status();
        $bpm_risk = $this->calculate_bpm();

        if ($this->primordialDuck->super_power != null) {
            $super_power_risk += 2;
        }

        $result = $super_power_risk + $status_risk + $bpm_risk;

        if ($result > 10) {
            $result = 10;
        }

        $this->risk = $result;
    }

    private function calculate_mutations ()
    {
        $mutations = $this->primordialDuck->mutations_quantity;

        if ($mutations > 0 && $mutations <= 3) {
            return 2;
        } elseif ($mutations > 3 && $mutations <= 5) {
            return 6;
        } elseif ($mutations > 5) {
            return 10;
        }
        return 0;
    }

    private function calculate_distance(): float
    {
        $distanceKm = $this->get_distance();

        if ($distanceKm < 500) {
            return 1.0; 
        } elseif ($distanceKm < 2000) {
            return 4.0;
        } elseif ($distanceKm < 5000) {
            return 7.0;
        } elseif ($distanceKm > 5000) {
            return 10.0; 
        }

        return 0;
    }

    private function get_distance(): float
    {
        $earthRadius = 6371; 
        $duckLatitude = $this->primordialDuck->location->latitude;
        $duckLongitude = $this->primordialDuck->location->longitude;

        $baseLatRad = deg2rad(self::$dsinLatitude);
        $duckLatRad = deg2rad($duckLatitude);

        $dLatRad = deg2rad($duckLatitude - self::$dsinLatitude);
        $dLonRad = deg2rad($duckLongitude - self::$dsinLongitude);

        //Fórmula de Haversine 
        $a = sin($dLatRad / 2) * sin($dLatRad / 2) +
            cos($baseLatRad) * cos($duckLatRad) * sin($dLonRad / 2) * sin($dLonRad / 2);
            
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    private function calculate_scientific_gain () {

        $mutations = $this->calculate_mutations();
        $distance = $this->calculate_distance();
        $super_power = 0;

        if ($this->primordialDuck->super_power != null){
            $super_power = 10;
        }
        
        $result = ($mutations * 0.6) + ($distance * 0.25) + ($super_power * 0.15);

        $this->scientifc_gain = $result;
    }

    private function calculate_capture_risk ()
    {
        $risk = $this->risk;
        
        if ($risk >= 0 && $risk <= 3) {
            $this->operacional_risk = 3;
            return [
                'necessity' => 'Minima',
                'squad' => '1 Drone de suporte
                             2 Agente com equipamento não letal'
            ];
        } elseif ($risk > 3 && $risk <= 7) {
            $this->operacional_risk = 3;
            return [
                'necessity' => 'moderada',
                'squad' => '2 Drones de captura
                            1 Veiculo de suporte terrrestre'
            ];
        } elseif ($risk > 7 && $risk <= 10) {
            $this->operacional_risk = 3;
            return [
                'necessity' => 'Maxima',
                'squad' => '1 Esquadrão de contenção especial
                            2 Veiculos blindados
                            1 Drone de ataque especial'
            ];
        }
        
        return null;
    }

    public function classify () {
    
    $mission = $this->calculate_capture_risk();

    $capture_risk = $this->operacional_risk; 
    $risk = $this->risk ;
    $value = $this->scientifc_gain;


    $analysis = $value / ($capture_risk * $risk);

    return [
        'success' => true,
        'analysis' => $analysis, 
        'mission' => $mission
    ];   
    }
}