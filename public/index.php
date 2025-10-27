<?php

declare(strict_types=1);

use Src\Application\UseCases\PrimordialDuckService;

require __DIR__ . '/../vendor/autoload.php';
$file = __DIR__ . '/../storage/PrimordialDuck.txt';

$duck_data = [
  'mac_drone' => isset($_POST['mac_drone']) ? (int) $_POST['mac_drone'] : 0,
  'height' => isset($_POST['height']) ? (float) $_POST['height'] : 0.0,
  'height_type' => $_POST['height_type'] ?? '',
  'weight' => isset($_POST['weight']) ? (float) $_POST['weight'] : 0.0,
  'weight_type' => $_POST['weight_type'] ?? '',
  'status' => $_POST['status'] ?? '',
  'bpm' => isset($_POST['bpm']) ? (int) $_POST['bpm'] : 0.0,
  'mutations_quantity' => isset($_POST['mutations_quantity']) ? (int) $_POST['mutations_quantity'] : 0,
  'name' => isset($_POST['name']) ? (string) $_POST['name'] : null,
];

$loc_data = [
  'country' => $_POST['country'] ?? '',
  'city' => $_POST['city'] ?? '',
  'refer' => $_POST['refer'] ?? '',
  'latitude' => isset($_POST['latitude']) ? (float) $_POST['latitude'] : 0.0,
  'longitude' => isset($_POST['longitude']) ? (float) $_POST['longitude'] : 0.0,
  'precision' => isset($_POST['precision']) ? (int) $_POST['precision'] : 0,
  'precision_type' => $_POST['precision_type'] ?? 'cm'
];

$sp_data = [];
if (!empty($_POST['sp_name'])) {
  $sp_data = [
    'name' => $_POST['sp_name'],
    'description' => $_POST['sp_description'] ?? '',
    'class' => $_POST['sp_class'] ?? ''
  ];
}
  try {
    $service = new PrimordialDuckService($file);
    $result = $service->register($loc_data, $duck_data, $sp_data);

    $message = $result['message'];
    $createdDuck = $result['data'] ?? null;
    

  } catch (Exception $e) {
    $message = "Erro: " . $e->getMessage();
  }


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Criar Pato Primordial</title>
  <link rel="stylesheet" href="css\style-index.css">

</head>
<body>
  <form class="container" method="POST">
    <!-- Grupo LOCALIZAÇÃO -->
    <fieldset class="card">
      <legend>Localização</legend>
      <div class="input-group">
        <input name="country" placeholder="País" type="text">
      </div>
      <div class="input-group">
        <input name="city" placeholder="Cidade" type="text" required>
      </div>
      <div class="input-group">
        <input name="refer" placeholder="Referencia" type="text">
      </div>
      <div class="input-group">
        <input name="longitude" placeholder="Longitude" type="number" step="any" required>
      </div>
      <div class="input-group">
        <input name="altitude" placeholder="Altitude" type="number" step="any">
      </div>

       <div class="input-group">
        <input name="precision" placeholder="Precisão" type="number">
      </div>
            <div class="input-group">
        <select name="precision_type" required>
          <option value="">Precisão</option>
          <option value="cm">Centimetros</option>
          <option value="m">Metros</option>
          <option value="yd">Jardas</option>
        </select>
      </div>
    </fieldset>

    <fieldset class="card">
      <legend>Dados do Pato</legend>
      <div class="input-group">
        <input name="mac_drone" placeholder="MAC do drone" type="number" required>
      </div>
      <div class="input-group">
        <input name="name" placeholder="Nome do pato" type="text">
      </div>
      <div class="input-group">
        <input name="height" placeholder="Altura" type="number" required>
        <select name="height_type" required>
          <option value="">Tipo de altura</option>
          <option value="cm">Centímetros (cm)</option>
          <option value="ft">Pés (ft)</option>
        </select>
      </div>
      <div class="input-group">
        <input name="bpm" placeholder="Batimentos cardiacos" type="text">
      </div>
      <div class="input-group">
        <input name="mutations_quantity" placeholder="Quantidade de mutações" type="text">
      </div>
      <div class="input-group">
        <input name="weight" placeholder="Peso" type="number" required>
        <select name="weight_type" required>
          <option value="">Tipo de peso</option>
          <option value="kg">Quilos (kg)</option>
          <option value="lb">Libras (lb)</option>
        </select>
      </div>
      <div class="input-group">
        <select name="status" required>
          <option value="">Status</option>
          <option value="desperto">Desperto</option>
          <option value="em transe">Em transe</option>
          <option value="hibernação profunda">Hibernação profunda</option>
        </select>
      </div>
      <div class="input-group">
        <button type="submit">Salvar Pato</button>
        <a href="./card.php" class="btn-view">Ver Patos</a>
      </div>




        
      
    </fieldset>

    <!-- Grupo SUPERPODER -->
    <fieldset class="card">
      <legend>Superpoder</legend>
      <div class="input-group">
        <input name="sp_name" placeholder="Nome do poder" type="text">
      </div>
      <div class="input-group">
        <input name="sp_description" placeholder="Descrição" type="text">
      </div>
      <div class="input-group">
        <input name="sp_class" placeholder="Classe" type="text">
      </div>
    </fieldset>

  </form>
     
</body>

</html>