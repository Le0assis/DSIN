<?php

declare(strict_types=1);

use Src\Application\UseCases\PrimordialDuckService;

// Caminho para o autoloader do Composer e o arquivo de repositório
require __DIR__ . '/../vendor/autoload.php';
$file = __DIR__ . '/../storage/PrimordialDuck.txt';

echo "--- Iniciando o Seeder de Patos Primordiais ---\n";

// =========================================================================
// FUNÇÃO AUXILIAR PARA REGISTRAR E DAR FEEDBACK NO TERMINAL
// =========================================================================
function registerDuck(
    string $status,
    array $duckData,
    array $locData,
    array $spData,
    PrimordialDuckService $service
): void {
    try {
        // Altera o nome e status para o caso atual
        $duckData['name'] = "Pato - {$status}";
        $duckData['status'] = $status;
        $locData['city'] = ($status === 'Desperto') ? 'São Paulo' : ($status === 'Em transe' ? 'Curitiba' : 'Manaus');

        echo "\n[INFO] Registrando Pato: {$duckData['name']}...\n";

        // Chama o serviço para registrar
        $result = $service->register($locData, $duckData, $spData);

        $message = $result['message'] ?? 'SUCESSO: Pato registrado.';
        $id = $result['data']->id ?? 'N/A';
        
        echo "   [SUCESSO] ID: {$id}. Mensagem: {$message}\n";

    } catch (\Exception $e) {
        echo "   [ERRO FATAL] Falha ao registrar 'Pato - {$status}'. Mensagem: " . $e->getMessage() . "\n";
    }
}


// =========================================================================
// 1. DADOS BASE (MOCK DATA)
// =========================================================================

// Dados base de Pato
$base_duck_data = [
    'mac_drone' => 99990000,
    'height' => 100.0,
    'height_type' => 'cm',
    'weight' => 4.5,
    'weight_type' => 'kg',
    'bpm' => 60,
    'mutations_quantity' => 0,
];

// Dados base de Localização
$base_loc_data = [
    'country' => 'Brasil',
    'refer' => 'Área de Teste Seeder',
    'latitude' => 0.0,
    'longitude' => 0.0,
    'precision' => 10,
    'precision_type' => 'cm',
];

// Dados de Super Poder (Usaremos para o Desperto)
$super_power_data = [
    'name' => 'Voar Supersônico',
    'description' => 'Atinge Mach 5 por 10 segundos.',
    'class' => 'Ataque Rápido',
];


// =========================================================================
// 2. INÍCIO DO REGISTRO
// =========================================================================

$service = new PrimordialDuckService($file);

// --- CASO 1: DESPERTO (Com Super Poder) ---
registerDuck(
    'Desperto',
    array_merge($base_duck_data, ['bpm' => 120, 'mutations_quantity' => 5]),
    array_merge($base_loc_data, ['latitude' => -23.5878, 'longitude' => -46.6631]),
    $super_power_data, // Com Super Poder
    $service
);

// --- CASO 2: EM TRANSE (Sem Super Poder) ---
registerDuck(
    'Em transe',
    array_merge($base_duck_data, ['bpm' => 75]),
    array_merge($base_loc_data, ['latitude' => -25.4326, 'longitude' => -49.2731]),
    [], // Sem Super Poder
    $service
);

// --- CASO 3: HIBERNAÇÃO PROFUNDA (Sem Super Poder) ---
registerDuck(
    'Hibernação profunda',
    array_merge($base_duck_data, ['bpm' => 30]),
    array_merge($base_loc_data, ['latitude' => -3.1190, 'longitude' => -60.0217]),
    [], // Sem Super Poder
    $service
);


echo "\n--- Seeder finalizado. Verifique o arquivo PrimordialDuck.txt ---\n";