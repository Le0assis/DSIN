<?php
declare(strict_types=1);

use Src\Infra\FileDuckRepository;
use Src\Domain\Service\CaptureClassifier;

require __DIR__ . '/../vendor/autoload.php';

$file = __DIR__ . '/../storage/PrimordialDuck.txt';
$duckRepo = new FileDuckRepository($file);
$ducks = $duckRepo->find_all();

foreach ($ducks as $duck) {
    $classifier = new CaptureClassifier($duck);
    $classification = $classifier->classify();
    $duck->set_classify($classification);
}

$ducks_json = json_encode($ducks);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ducks IN</title>
  <link rel="stylesheet" href="css/style-card.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <style>
    .modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; }
    .modal.show { display:flex; }
    .modal-content { background:#fff; padding:25px 35px; border-radius:10px; max-width:420px; text-align:left; box-shadow:0 0 20px rgba(0,0,0,0.3); position:relative; animation:fadeIn .18s ease; }
    @keyframes fadeIn { from{transform:scale(.98);opacity:0} to{transform:scale(1);opacity:1} }
    .close-btn { position:absolute; top:10px; right:12px; font-size:20px; cursor:pointer; color:#555; }
    .modal-content .actions { margin-top:18px; text-align:right; }
    .modal-content button { background:#0066cc; color:#fff; padding:8px 14px; border:none; border-radius:6px; cursor:pointer; }
    .modal-content button:hover { background:#004d99; }
  </style>
</head>

<body>
  <div id="cards-container"></div>

  <!-- Modal -->
  <div id="duck-modal" class="modal" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true">
      <span class="close-btn" id="modal-x">&times;</span>
      <h2>Análise do Pato</h2>
      <div id="modal-text">Carregando...</div>
      <div class="actions">
        <button id="close-modal">Fechar</button>
      </div>
    </div>
  </div>

  <script>
  const ducksData = JSON.parse('<?php echo addslashes($ducks_json); ?>');
  const cardsContainer = document.getElementById('cards-container');
  const modal = document.getElementById('duck-modal');
  const modalText = document.getElementById('modal-text');

  document.addEventListener('DOMContentLoaded', () => {
    ducksData.forEach(duck => {
      const cardHTML = createDuckCardHTML(duck);
      cardsContainer.insertAdjacentHTML('beforeend', cardHTML);
    });

    cardsContainer.addEventListener('click', (e) => {
      const btn = e.target.closest('button.card-button');
      if (!btn) return;

      const id = btn.dataset.duckId;
      const duck = ducksData.find(d => d.id == id);

      if (!duck) {
        modalText.innerHTML = `<strong>Erro:</strong> Pato com ID ${id} não encontrado.`;
        showModal();
        return;
      }

      modalText.innerHTML = `
        <p><strong>Nome:</strong> ${duck.name ?? 'Desconhecido'}</p>
        <p><strong>Status:</strong> ${duck.status ?? '—'}</p>
        <p><strong>Mutações:</strong> ${duck.mutations_quantity ?? 0}</p>
        <hr>
  <p><strong>Análise:</strong> ${duck.classification['analysis'].toFixed(2) ?? 'N/A'}</p>
  <p><strong>Nível de Necessidade:</strong> ${duck.classification['mission']['necessity'] ?? 'N/A'}</p>
  <p><strong>Nível de Necessidade:</strong> ${duck.classification['mission']['squad'] ?? 'N/A'}</p>
 
      `;
      showModal();
    });
  });

  function showModal() { modal.classList.add('show'); }
  function hideModal() { modal.classList.remove('show'); }

  document.getElementById('modal-x').addEventListener('click', hideModal);
  document.getElementById('close-modal').addEventListener('click', hideModal);
  window.addEventListener('click', (e) => { if (e.target === modal) hideModal(); });

  function createDuckCardHTML(duck) {

    const details = [
      { label: 'Número de série Drone', value: duck.mac_drone ?? '—' },
      { label: 'Altura', value: (duck.height_cm ?? duck.height ?? '—') + (duck.height_cm ? ' cm' : '') },
      { label: 'Peso', value: (duck.weight_g ?? duck.weight ?? '—') + (duck.weight_g ? ' g' : '') },
      { label: 'BPM', value: duck.bpm ?? '??' },
      { label: 'Coordenadas', value: (duck.location?.latitude ?? '—') + ', ' + (duck.location?.longitude ?? '—') },
      { label: 'Precisão', value: (duck.location?.precision ?? '—') + (duck.location?.precision ? ' cm' : '') },
      { label: 'Referência', value: duck.location?.refer ?? 'Sem referência' }
    ];

    const detailsHTML = details.map(item => `
      <div class="card-details-item">
        <span class="detail-label">${item.label}</span>
        <span class="detail-value">${item.value}</span>
      </div>
    `).join('');

    return `
      <div class="card" data-duck-wrapper="${duck.id}">
        <div class="card-header">
          <img src="img/duck-image.png" alt="Pato" class="card-image" onerror="this.style.opacity=.2"/>
          <div class="card-title-group">
            <h2 class="card-name">${duck.name ?? "Desconhecido"}</h2>
            <span class="card-status">${duck.status ?? ''}</span>
            <div class="card-location"><i class="fa-solid fa-location-dot"></i> ${duck.location?.country ?? ''} ${duck.location?.city ?? ''}</div>
            <div class="card-mutations"><i class="fa-solid fa-dna"></i> ${duck.mutations_quantity ?? 0} mutações</div>
          </div>
        </div>

        <div class="card-ability">
          <div class="ability-icon">${duck.super_power ? '<i class="fa-solid fa-bolt"></i>' : ''}</div>
          <div class="ability-name">${duck.super_power?.name ?? "Desconhecido"}</div>
          <p class="ability-description">${duck.super_power?.description ?? '&nbsp;'}</p>
        </div>

        <div class="card-details">${detailsHTML}</div>

        <div style="text-align:right;margin-top:10px;">
          <button class="card-button" data-duck-id="${duck.id}" type="button">Calcular risco</button>
        </div>
      </div>
    `;
  }
  </script>
</body>
</html>
