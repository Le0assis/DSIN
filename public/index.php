<!DOCTYPE html>
<html lang="pt-BR">
<body>
  <h2>Criar Pato Primordial</h2>

  <form id="duckForm">
    <input name="name" placeholder="Nome do pato" required><br>
    <input name="mac_drone" placeholder="Mac do drone" type="number"><br>
    <input name="height" placeholder="Altura" type="number"><br>
    <input name="height_type" placeholder="Tipo de altura (cm/ft)"><br>
    <input name="weight" placeholder="Peso" type="number"><br>
    <input name="weight_type" placeholder="Tipo de peso (kg/lb)"><br>
    <input name="country" placeholder="País"><br>
    <input name="city" placeholder="Cidade"><br>
    <input name="latitude" placeholder="Latitude" type="number"><br>
    <input name="longitude" placeholder="Longitude" type="number"><br>
    <input name="status" placeholder="Status"><br>
    <input name="bpm" placeholder="BPM" type="number"><br>
    <input name="mutations_quantity" placeholder="Mutations" type="number"><br>
    <button type="submit">Criar</button>
  </form>

  <pre id="response"></pre>

  <script>
    const data = {};

    document.querySelector('#duckForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const form = new FormData(e.target);

      const payload = {
        name: form.get('name'),
        mac_drone: form.get('mac_drone'),
        height: form.get('height'),
        height_type: form.get('height_type'),
        weight: form.get('weight'),
        weight_type: form.get('weight_type'),
        location: {
          country: form.get('country'),
          city: form.get('city'),
          latitude: form.get('latitude'),
          longitude: form.get('longitude')
        },
        status: form.get('status'),
        bpm: form.get('bpm'),
        mutations_quantity: form.get('mutations_quantity')
      };

      const res = await fetch('/api/primordial-duck', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      });

      const result = await res.json();
      document.querySelector('#response').textContent = JSON.stringify(result, null, 2);
    });
  </script>
</body>
</html>
