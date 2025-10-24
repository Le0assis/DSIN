<?php
declare(strict_types=1);

use Src\Infra\FileDuckRepository;

require __DIR__ . '/../vendor/autoload.php';
$file = __DIR__ . '/../storage/PrimordialDuck.txt';

$duckService = new FileDuckRepository($file);

$ducksArray = $duckService->find_all();
$ducks_json = json_encode($ducksArray);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ducks IN</title>
    <link rel="stylesheet" href="css\style-card.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div id="cards-container">
    </div>

    <script>

        const ducksData = JSON.parse('<?php echo $ducks_json; ?>');
        const cardsContainer = document.getElementById('cards-container');

        document.addEventListener('DOMContentLoaded', () => {

            ducksData.forEach(duck => {
                const cardHTML = createDuckCardHTML(duck);
                cardsContainer.insertAdjacentHTML('beforeend', cardHTML);
            });
        });


        function createDuckCardHTML(duck) {
            //,"height_cm":100,"weight_g":4.5,"status":"Desperto","bpm":120,"mutations_quantity":5,
            // "location":{"city":"São Paulo","country":"Brasil","latitude":-23.5878,"longitude":-46.6631,"precision":10,"refer":"Área de Teste Seeder"},
            // "super_poder":{"name":"Voar Supersônico","description":"Atinge Mach 5 por 10 segundos.","class":"Ataque Rápido"}}


            const details = [
                { label: 'Numero de série Drone', value: duck.mac_drone },
                { label: 'Altura', value: duck.height_cm + ' cm' },
                { label: 'Peso', value: duck.weight_g + ' g' },
                { label: 'BPM', value: duck.bpm ?? "??" },
                { label: 'Coordenadas', value: duck.location.latitude + ', ' + duck.location.longitude },
                { label: 'Precisão', value: duck.location.precision + ' cm' },
                { label: 'Referência', value: duck.location.refer  ?? 'Sem referência'}

            ];

            const detailsHTML = details.map(item => `
                <div class="card-details-item">
                    <span class="detail-label">${item.label}</span>
                    <span class="detail-value">${item.value}</span>
                </div>
            `).join('');


            return `
                <div class="card">
                    <div class="card-header">
                        <img src="img/duck-image.png" alt="Pato" class="card-image">
                        <div class="card-title-group">
                            <h2 class="card-name">${duck.name ?? "Desconhecido"}</h2>
                            <span class="card-status">${duck.status}</span>
                            <div class="card-location"><i class="fa-solid fa-location-dot" ></i>${duck.location.country + ' ' + duck.location.city}</div>
                            <div class="card-mutations"><i class="fa-solid fa-dna"></i>  ${duck.mutations_quantity} mutações</div>
                        </div>
                    </div>

                    <div class="card-ability">
                        <div class="ability-icon">${duck.super_poder ? '<i class="fa-solid fa-bolt"></i>' : ''}</div> 
                        <div class="ability-name">${duck.super_poder?.name ?? "Desconhecido"}</div>
                        <p class="ability-description">${duck.super_poder?.description ?? ' '}</p>
                    </div>

                    <div class="card-details">
                        ${detailsHTML}
                    </div>

                    <button class="card-button" duck-data="${duckId}" type="submmit">Calcular risco</button>
                </div>
            `;
        }
    </script>
</body>

</html>