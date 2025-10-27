<?php

declare(strict_types=1);

namespace Src\Domain\Service;

use Src\Domain\Entities\PrimordialDuck;

final class CaptureClassifier
{
    private PrimordialDuck $primordialDuck;

    private int $risk = 0;
    private float $scientifc_gain = 0.0;
    private int $operacional_risk = 0;

    // Base DSIN (constante)
    private const DSIN_LAT = -22.23354373725337;
    private const DSIN_LON = -49.93411930338776;

    public array $operacional = [];

    public function __construct(PrimordialDuck $primordialDuck)
    {
        $this->primordialDuck = $primordialDuck;
    }

    // --------------------------
    // CÁLCULOS AUXILIARES
    // --------------------------
    private function calculate_status(): int
    {
        $status = strtolower($this->primordialDuck->status ?? '');

        if ($status === 'desperto') {
            return 10;
        } elseif ($status === 'em transe') {
            return 5;
        } elseif ($status === 'hibernação profunda') {
            return 1;
        }

        return 0;
    }

    private function calculate_bpm(): int
    {
        $bpm = (int)($this->primordialDuck->bpm ?? 0);
        $status = strtolower($this->primordialDuck->status ?? '');

        if ($status === 'em transe') {
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

    private function calculate_risk(): void
    {
        $super_power_risk = 0;
        $status_risk = $this->calculate_status();
        $bpm_risk = $this->calculate_bpm();

        if (!empty($this->primordialDuck->super_power)) {
            $super_power_risk += 2;
        }

        $result = $super_power_risk + $status_risk + $bpm_risk;

        if ($result > 10) {
            $result = 10;
        }

        $this->risk = (int) $result;
    }

    private function calculate_mutations(): int
    {
        $mutations = (int)($this->primordialDuck->mutations_quantity ?? 0);

        if ($mutations > 0 && $mutations <= 3) {
            return 2;
        } elseif ($mutations > 3 && $mutations <= 5) {
            return 6;
        } elseif ($mutations > 5) {
            return 10;
        }

        return 0;
    }

    private function get_distance(): float
    {
        // Se não existir location ou lat/lon, retorna um valor grande (para indicar distância desconhecida)
        if (empty($this->primordialDuck->location)
            || !isset($this->primordialDuck->location->latitude)
            || !isset($this->primordialDuck->location->longitude)
        ) {
            return 99999.0;
        }

        $earthRadius = 6371.0;

        $duckLatitude = (float)$this->primordialDuck->location->latitude;
        $duckLongitude = (float)$this->primordialDuck->location->longitude;

        $baseLatRad = deg2rad(self::DSIN_LAT);
        $duckLatRad = deg2rad($duckLatitude);

        $dLatRad = deg2rad($duckLatitude - self::DSIN_LAT);
        $dLonRad = deg2rad($duckLongitude - self::DSIN_LON);

        // Haversine
        $a = sin($dLatRad / 2) * sin($dLatRad / 2) +
            cos($baseLatRad) * cos($duckLatRad) * sin($dLonRad / 2) * sin($dLonRad / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return (float) $distance;
    }

    private function calculate_distance_score(): float
    {
        $distanceKm = $this->get_distance();

        if ($distanceKm < 500) {
            return 1.0;
        } elseif ($distanceKm < 2000) {
            return 4.0;
        } elseif ($distanceKm < 5000) {
            return 7.0;
        } elseif ($distanceKm >= 5000) {
            return 10.0;
        }

        return 0.0;
    }

    private function calculate_scientific_gain(): void
    {
        $mutations = $this->calculate_mutations();
        $distance = $this->calculate_distance_score();
        $super_power = !empty($this->primordialDuck->super_power) ? 10.0 : 0.0;

        $result = ($mutations * 0.6) + ($distance * 0.25) + ($super_power * 0.15);
        $this->scientifc_gain = (float) $result;
    }

    private function calculate_capture_risk(): ?array
    {

        $this->calculate_risk();
        $risk = $this->risk;

        if ($risk >= 0 && $risk <= 3) {
            $this->operacional_risk = 3;
            return [
                'necessity' => 'Minima',
                'squad' => "1 Drone de suporte\n2 Agente com equipamento não letal"
            ];
        } elseif ($risk > 3 && $risk <= 7) {
            $this->operacional_risk = 6; // ajuste plausível (antes estava 3 em todos)
            return [
                'necessity' => 'Moderada',
                'squad' => "2 Drones de captura\n1 Veiculo de suporte terrestre"
            ];
        } elseif ($risk > 7 && $risk <= 10) {
            $this->operacional_risk = 9; // ajuste plausível (nível maior)
            return [
                'necessity' => 'Maxima',
                'squad' => "1 Esquadrão de contenção especial\n2 Veiculos blindados\n1 Drone de ataque especial"
            ];
        }

        return null;
    }

    // --------------------------
    // CLASSIFICAÇÃO PÚBLICA
    // --------------------------
    public function classify(): array
    {
        // garante cálculos prévios na ordem correta
        $this->calculate_risk();
        $this->calculate_scientific_gain();
        $mission = $this->calculate_capture_risk();

        $capture_risk = $this->operacional_risk;
        $risk = $this->risk;
        $value = $this->scientifc_gain;

        // evita divisão por zero
        $denominator = $capture_risk * $risk;
        if ($denominator <= 0.0) {
            $analysis = 0.0;
        } else {
            $analysis = $value / $denominator;
        }

        return [
            'success' => true,
            'analysis' => (float) $analysis,
            'mission' => $mission,
            'raw' => [
                'risk' => $risk,
                'operacional_risk' => $capture_risk,
                'scientific_gain' => $value
            ]
        ];
    }
}
