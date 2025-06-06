<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Actividades</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #4facfe, #00f2fe);
    }
    .encabezado {
      display: flex;
      align-items: center;
      justify-content: center;
      background-image: url('https://img.freepik.com/foto-gratis/fondo-desenfocado-gimnasio_23-2149270289.jpg');
      background-size: cover;
      background-position: center;
      padding: 20px;
      flex-wrap: wrap;
    }
    .encabezado img.logo {
      width: 80px;
      margin-right: 20px;
    }
    .encabezado .textos {
      text-align: center;
    }
    .encabezado h1 {
      font-size: 40px;
      color: white;
      margin: 0;
      font-weight: 900;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.4);
    }
    .subtitulo {
      background-color: #ff5e62;
      color: white;
      padding: 8px 20px;
      font-weight: bold;
      border-radius: 5px;
      display: inline-block;
      margin-top: 10px;
      box-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .formulario {
      max-width: 700px;
      margin: 30px auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .formulario h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    .campo {
      margin-bottom: 20px;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 6px;
    }
    input, select {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    button {
      display: block;
      margin: 30px auto 0;
      padding: 12px 40px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #218838;
    }
    button:disabled {
      background-color: #6c757d;
      cursor: not-allowed;
    }
    .mensaje {
      text-align: center;
      font-weight: bold;
      margin-top: 15px;
      padding: 10px;
      border-radius: 5px;
    }
    .exito {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    .loading {
      background-color: #d1ecf1;
      color: #0c5460;
      border: 1px solid #bee5eb;
    }
  </style>
</head>
<body>

  <!-- ENCABEZADO -->
  <div class="encabezado">
    <img class="logo" src="https://th.bing.com/th/id/OIP.ymH0HaEC9zH-WTBajW9fbwHaHa?pid=ImgDet&rs=1" alt="Logo Actividad">
    <div class="textos">
      <h1>REGISTRO DE ACTIVIDAD</h1>
      <div class="subtitulo">Formulario de Alta</div>
    </div>
  </div>

  <!-- FORMULARIO -->
  <div class="formulario">
    <h2>Datos de la Actividad</h2>
    
    <div id="mensaje" class="mensaje" style="display: none;"></div>
    
    <form id="formActividad">
      <div class="campo">
        <label>Nombre de la Actividad:</label>
        <input type="text" id="nombre_actividad" name="nombre_actividad" required>
      </div>

      <div class="campo">
        <label>Maestro Asignado:</label>
        <select id="maestro_asignado" name="maestro_asignado" required>
          <option value="">-- Selecciona un maestro --</option>
          <!-- Los maestros se cargarán dinámicamente -->
        </select>
      </div>

      <button type="submit" id="btnRegistrar">Registrar Actividad</button>
    </form>
  </div>

<script>
// Cargar maestros al iniciar la página
document.addEventListener('DOMContentLoaded', cargarMaestros);

async function cargarMaestros() {
    try {
        console.log('🟡 Iniciando carga de maestros...');
        
        const response = await fetch('http://localhost:5111/api/admin/maestros');
        console.log('🟡 Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('🟢 Datos recibidos:', data);
        
        // Acceder a los maestros desde data.Data
        const maestros = data.Data || data.data || [];
        console.log('🟢 Maestros extraídos:', maestros);
        
        const select = document.getElementById('maestro_asignado');
        
        if (maestros.length === 0) {
            select.innerHTML = '<option value="">-- No hay maestros disponibles --</option>';
            mostrarMensaje('⚠️ No se encontraron maestros registrados', 'error');
            return;
        }
        
        select.innerHTML = '<option value="">-- Selecciona un maestro --</option>' +
            maestros.map(maestro => `
                <option value="${maestro.id}">${maestro.nombre}</option>
            `).join('');
            
        console.log('✅ Dropdown de maestros actualizado');
        
    } catch (error) {
        console.error('❌ Error cargando maestros:', error);
        mostrarMensaje('❌ Error al cargar la lista de maestros: ' + error.message, 'error');
        
        // Fallback en caso de error
        const select = document.getElementById('maestro_asignado');
        select.innerHTML = '<option value="">-- Error al cargar maestros --</option>';
    }
}

/// Manejar envío del formulario
document.getElementById('formActividad').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btnRegistrar = document.getElementById('btnRegistrar');
    
    // Obtener datos del formulario
    const nombreActividad = document.getElementById('nombre_actividad').value.trim();
    const maestroId = document.getElementById('maestro_asignado').value;
    
    // Validaciones
    if (!nombreActividad) {
        mostrarMensaje('❌ El nombre de la actividad es requerido', 'error');
        return;
    }
    
    if (!maestroId) {
        mostrarMensaje('❌ Debe seleccionar un maestro', 'error');
        return;
    }
    
    // Deshabilitar botón y mostrar loading
    btnRegistrar.disabled = true;
    btnRegistrar.textContent = 'Registrando...';
    mostrarMensaje('⏳ Registrando actividad...', 'loading');
    
    try {
 
const datosActividad = {
    nombre: nombreActividad,
    idUsuarioMaestro: parseInt(maestroId), // ← Nombre correcto del campo
    estatus: true
};

        
        console.log('🟡 Enviando datos:', datosActividad);
        console.log('🟡 ID del maestro seleccionado:', maestroId);
        
        // Enviar a la API
        const response = await fetch('http://localhost:5111/api/actividad', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datosActividad)
        });
        
        if (response.ok) {
            const resultado = await response.json();
            console.log('✅ Actividad creada:', resultado);
            mostrarMensaje('✅ Actividad registrada exitosamente', 'exito');
            document.getElementById('formActividad').reset();
        } else {
            const error = await response.text();
            console.error('❌ Error del servidor:', error);
            mostrarMensaje('❌ Error al registrar actividad: ' + error, 'error');
        }
        
    } catch (error) {
        console.error('❌ Error de conexión:', error);
        mostrarMensaje('❌ Error de conexión con el servidor', 'error');
    } finally {
        // Rehabilitar botón
        btnRegistrar.disabled = false;
        btnRegistrar.textContent = 'Registrar Actividad';
    }
});


function mostrarMensaje(texto, tipo) {
    const mensaje = document.getElementById('mensaje');
    mensaje.textContent = texto;
    mensaje.className = `mensaje ${tipo}`;
    mensaje.style.display = 'block';
    
    // Ocultar mensaje después de 5 segundos si es de éxito
    if (tipo === 'exito') {
        setTimeout(() => {
            mensaje.style.display = 'none';
        }, 5000);
    }
}
</script>

</body>
</html>
