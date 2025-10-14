<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Producto</title>
  <style>
    .error {
      color: red;
      font-size: 0.9em;
      display: none;
    }
  </style>
</head>
<body>
  <h2>Modificar producto</h2>
  <form id="productoForm" action="update_producto.php" method="post">
    
    <!-- Campo oculto para el ID -->
    <input type="hidden" id="id" name="id" value="<?= !empty($_POST['id']) ? $_POST['id'] : '' ?>">
    
    <label>Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" value="<?= !empty($_POST['nombre']) ? $_POST['nombre'] : '' ?>"><br>
    <span class="error" id="errorNombre">El nombre es requerido y debe tener 100 caracteres o menos.</span><br>

    <label>Marca:</label><br>
    <select id="marca" name="marca">
      <option value="">Selecciona una marca</option>
      <option value="Samsung" <?= (!empty($_POST['marca']) && $_POST['marca'] == 'Samsung') ? 'selected' : '' ?>>Samsung</option>
      <option value="Apple" <?= (!empty($_POST['marca']) && $_POST['marca'] == 'Apple') ? 'selected' : '' ?>>Apple</option>
      <option value="LG" <?= (!empty($_POST['marca']) && $_POST['marca'] == 'LG') ? 'selected' : '' ?>>LG</option>
      <option value="Sony" <?= (!empty($_POST['marca']) && $_POST['marca'] == 'Sony') ? 'selected' : '' ?>>Sony</option>
      <option value="Xiaomi" <?= (!empty($_POST['marca']) && $_POST['marca'] == 'Xiaomi') ? 'selected' : '' ?>>Xiaomi</option>
      <option value="Huawei" <?= (!empty($_POST['marca']) && $_POST['marca'] == 'Huawei') ? 'selected' : '' ?>>Huawei</option>
      <option value="Motorola" <?= (!empty($_POST['marca']) && $_POST['marca'] == 'Motorola') ? 'selected' : '' ?>>Motorola</option>
    </select><br>
    <span class="error" id="errorMarca">Debes seleccionar una marca.</span><br>

    <label>Modelo:</label><br>
    <input type="text" id="modelo" name="modelo" value="<?= !empty($_POST['modelo']) ? $_POST['modelo'] : '' ?>"><br>
    <span class="error" id="errorModelo">El modelo es requerido, debe ser alfanumérico y tener 25 caracteres o menos.</span><br>

    <label>Precio:</label><br>
    <input type="number" step="0.01" id="precio" name="precio" value="<?= !empty($_POST['precio']) ? $_POST['precio'] : '' ?>"><br>
    <span class="error" id="errorPrecio">El precio es requerido y debe ser mayor a 99.99</span><br>

    <label>Detalles:</label><br>
    <input type="text" id="detalles" name="detalles" value="<?= !empty($_POST['detalles']) ? $_POST['detalles'] : '' ?>"><br>
    <span class="error" id="errorDetalles">Los detalles deben tener 250 caracteres o menos.</span><br>

    <label>Unidades:</label><br>
    <input type="number" id="unidades" name="unidades" value="<?= !empty($_POST['unidades']) ? $_POST['unidades'] : '' ?>"><br>
    <span class="error" id="errorUnidades">Las unidades son requeridas y deben ser mayor o igual a 0.</span><br>

    <label>Ruta de la imagen:</label><br>
    <input type="text" id="imagen" name="imagen" value="<?= !empty($_POST['imagen']) ? $_POST['imagen'] : 'img/default.png' ?>"><br><br>

    <input type="submit" value="Actualizar Producto">
  </form>

  <script>
    document.getElementById('productoForm').addEventListener('submit', function(e) {
      // Prevenir el envío por defecto
      e.preventDefault();
      
      // Limpiar errores previos
      var errores = document.querySelectorAll('.error');
      errores.forEach(function(error) {
        error.style.display = 'none';
      });
      
      var valido = true;
      
      // Validar NOMBRE: requerido y <= 100 caracteres
      var nombre = document.getElementById('nombre').value.trim();
      if (nombre === '' || nombre.length > 100) {
        document.getElementById('errorNombre').style.display = 'block';
        valido = false;
      }
      
      // Validar MARCA: requerida (debe seleccionarse)
      var marca = document.getElementById('marca').value;
      if (marca === '') {
        document.getElementById('errorMarca').style.display = 'block';
        valido = false;
      }
      
      // Validar MODELO: requerido, alfanumérico y <= 25 caracteres
      var modelo = document.getElementById('modelo').value.trim();
      var alfanumerico = /^[a-zA-Z0-9]+$/;
      if (modelo === '' || !alfanumerico.test(modelo) || modelo.length > 25) {
        document.getElementById('errorModelo').style.display = 'block';
        valido = false;
      }
      
      // Validar PRECIO: requerido y > 99.99
      var precio = parseFloat(document.getElementById('precio').value);
      if (isNaN(precio) || precio <= 99.99) {
        document.getElementById('errorPrecio').style.display = 'block';
        valido = false;
      }
      
      // Validar DETALLES: opcional, pero si existe <= 250 caracteres
      var detalles = document.getElementById('detalles').value.trim();
      if (detalles.length > 250) {
        document.getElementById('errorDetalles').style.display = 'block';
        valido = false;
      }
      
      // Validar UNIDADES: requeridas y >= 0
      var unidades = parseInt(document.getElementById('unidades').value);
      if (isNaN(unidades) || unidades < 0) {
        document.getElementById('errorUnidades').style.display = 'block';
        valido = false;
      }
      
      // Validar IMAGEN: opcional, si está vacía usar imagen por defecto
      var imagen = document.getElementById('imagen').value.trim();
      if (imagen === '') {
        document.getElementById('imagen').value = 'img/default.png';
      }
      
      // Si todo es válido, enviar el formulario
      if (valido) {
        this.submit();
      }
    });
  </script>
</body>
</html>