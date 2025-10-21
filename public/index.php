<?php

declare(strict_types=1);

use Src\Application\UseCases\PrimordialDuckService;

require __DIR__ . '/../vendor/autoload.php';
$file = __DIR__ . '/../storage/PrimordialDuck.txt';

$duck_data = [
  'mac_drone' => isset($_POST['mac_drone']) ? (int) $_POST['mac_drone'] : null,
  'height' => isset($_POST['height']) ? (float) $_POST['height'] : null,
  'height_type' => $_POST['height_type'] ?? '',
  'weight' => isset($_POST['weight']) ? (float) $_POST['weight'] : null,
  'weight_type' => $_POST['weight_type'] ?? '',
  'status' => $_POST['status'] ?? '',
  'bpm' => isset($_POST['bpm']) ? (int) $_POST['bpm'] : null,
  'mutations_quantity' => isset($_POST['mutations_quantity']) ? (int) $_POST['mutations_quantity'] : 0,
  'name' => $_POST['name'] ?? '',
];

$loc_data = [
  'country' => $_POST['country'] ?? '',
  'city' => $_POST['city'] ?? '',
  'refer' => $POST['refer'] ?? '',
  'latitude' => isset($_POST['latitude']) ? (float) $_POST['latitude'] : 0.0,
  'longitude' => isset($_POST['longitude']) ? (float) $_POST['longitude'] : 0.0,
  'precision' => isset($_POST['precision']) ? (string) $_POST['precision'] : 0,
  'precision_type' => $_POST['precision_type'] ?? 'cm'
];

$sp_data = [];
if (!empty($_POST['sp_name'])) {
  $sp_data = [
    'name' => $_POST['sp_name'],
    'description' => $_POST['sp_description'] ?? '',
    'class' => $_POST['sp_class'] ?? ''
  ];

  try {
    $service = new PrimordialDuckService($file);
    $result = $service->register($loc_data, $duck_data, $sp_data);

    $message = $result['message'] ?? 'Pato criado com sucesso';
    $createdDuck = $result['data'] ?? null;
    

  } catch (Exception $e) {
    $message = "Erro: " . $e->getMessage();
  }

}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Criar Pato Primordial</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 2rem;
    }

    fieldset {
      margin-bottom: 1rem;
    }

    #message {
      margin-top: 1rem;
      padding: 0.75rem;
      border-radius: 6px;
      display: none;
    }

    #message.success {
      background-color: #d4edda;
      color: #155724;
    }

    #message.error {
      background-color: #f8d7da;
      color: #721c24;
    }
  </style>
</head>

<body>
  <h2>🦆 Criar Pato Primordial</h2>

  <form id="duckForm" method="POST">
    <fieldset>
      <legend>Dados do Pato</legend>
      <input type="text" name="name" placeholder="name"><br>
      <input name="mac_drone" placeholder="MAC do drone" type="number" required><br>
      <input name="height" placeholder="Altura" type="number" required><br>
      <input name="height_type" placeholder="Tipo de altura (cm/ft)" required><br>
      <input name="weight" placeholder="Peso" type="number" required><br>
      <input name="weight_type" placeholder="Tipo de peso (kg/lb)" required><br>

      <<label for="status_pato">Selecione o Status:</label>
        <select id="status_pato" name="status">
          <option value="" disabled selected>Escolha o Status</option>

          <option value="Desperto">Desperto</option>
          <option value="Em transe">Em transe</option>
          <option value="hibernação profunda">Hibernação profunda</option>
        </select>

        <input name="bpm" placeholder="BPM" type="number"><br>
        <input name="mutations_quantity" placeholder="Quantidade de mutações" type="number" required><br>
    </fieldset>

    <fieldset>
      <legend>Localização</legend>
      <input name="country" placeholder="País" required><br>
      <input name="city" placeholder="Cidade" required><br>
      <input type="text" name="refer" placeholder="Referencia"><br>
      <input name="latitude" placeholder="Latitude" type="number" step="any" required><br>
      <input name="longitude" placeholder="Longitude" type="number" step="any" required><br>
      <input type="number" placeholder="precision" name="precision"><br>
    </fieldset>

    <fieldset>
      <legend>Super Poder (opcional)</legend>
      <input name="sp_name" placeholder="Nome do poder"><br>
      <input name="sp_description" placeholder="Descrição"><br>
      <input name="sp_class" placeholder="Classe"><br>
    </fieldset>

    <button type="submit">Criar</button>
  </form>


</body>

</html>