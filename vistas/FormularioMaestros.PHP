<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Maestros</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right,rgb(255, 255, 255),rgb(255, 255, 255));
    }
    .encabezado {
      display: flex;
      align-items: center;
      justify-content: center;
      background-image: url('https://img.freepik.com/fotos-gratis/fundo-digital-futurista-gradiente-azul-escuro_53876-160646.jpg');
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
      background-color: #f9b400;
      color: white;
      padding: 8px 20px;
      font-weight: bold;
      border-radius: 5px;
      display: inline-block;
      margin-top: 10px;
      box-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .formulario {
      max-width: 800px;
      margin: 30px auto;
      background-color:rgb(33, 85, 161);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px hsla(224, 85.20%, 29.20%, 0.62);
    }
    .formulario h2 {
      text-align: center;
      margin-bottom: 20px;
      color: white;
    }
    .fila {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 15px;
    }
    .campo {
      flex: 1;
      min-width: 200px;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
      color: white;
    }
    input {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      display: block;
      margin: 20px auto 0;
      padding: 12px 40px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #0056b3;
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

<div class="encabezado">
  <img class="logo" src="https://th.bing.com/th/id/R.418aad279098b8cee2a075ed29b48011?rik=q23X1HjmoWFcgQ&riu=http%3a%2f%2fwww.veracruz.tecnm.mx%2ftemplates%2frt_manticore%2fcustom%2fimages%2fImagenes%2fA2020%2fwebmay2020%2fwhite+eagle.png&ehk=X%2bhUhCScz6ur03iD%2fn1TgEYVXjltC%2blyuan6qdGnJr4%3d&risl=&pid=ImgRaw&r=0" alt="Logo TecNM">
  <div class="textos">
    <h1>REGISTRO DE MAESTROS</h1>
    <div class="subtitulo">Formulario Oficial</div>
  </div>
</div>

<div class="formulario">
  <h2>Ingrese los datos del maestro</h2>

  <div id="mensaje" class="mensaje" style="display: none;"></div>

  <form id="formMaestro">
    <div class="fila">
      <div class="campo">
        <label>Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
      </div>
      <div class="campo">
        <label>Apellido Paterno:</label>
        <input type="text" id="apellido_paterno" name="apellido_paterno" required>
      </div>
      <div class="campo">
        <label>Apellido Materno:</label>
        <input type="text" id="apellido_materno" name="apellido_materno" required>
      </div>
    </div>

    <div class="fila">
      <div class="campo">
        <label>Correo electrónico:</label>
        <input type="email" id="correo" name="correo" required>
      </div>
      <div class="campo">
        <label>Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" required>
      </div>
    </div>

    <div class="fila">
      <div class="campo">
        <label>Contraseña:</label>
        <input type="password" id="password" name="password" required minlength="6">
      </div>
      <div class="campo">
        <label>Confirmar contraseña:</label>
        <input type="password" id="confirmar_password" name="confirmar_password" required minlength="6">
      </div>
    </div>

    <button type="submit" id="btnRegistrar">Registrar</button>
  </form>
</div>

<script>
document.getElementById('formMaestro').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btnRegistrar = document.getElementById('btnRegistrar');
    const mensaje = document.getElementById('mensaje');
    
    // Obtener datos del formulario
    const nombre = document.getElementById('nombre').value.trim();
    const apellidoPaterno = document.getElementById('apellido_paterno').value.trim();
    const apellidoMaterno = document.getElementById('apellido_materno').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const password = document.getElementById('password').value;
    const confirmarPassword = document.getElementById('confirmar_password').value;
    
    // Validar contraseñas
    if (password !== confirmarPassword) {
        mostrarMensaje('❌ Las contraseñas no coinciden.', 'error');
        return;
    }
    
    // Deshabilitar botón y mostrar loading
    btnRegistrar.disabled = true;
    btnRegistrar.textContent = 'Registrando...';
    mostrarMensaje('⏳ Registrando maestro...', 'loading');
    
    try {
        // Preparar datos para enviar a la API
        const nombreCompleto = `${nombre} ${apellidoPaterno} ${apellidoMaterno}`;
        const matricula = 'MAE' + Math.floor(Math.random() * 900 + 100); // Generar matrícula
        
        const datosmaestro = {
            nombre: nombreCompleto,
            matricula: matricula,
            correo: correo,
            telefono: telefono,
            contraseña: password, // Se hasheará en el backend
            especialidad: 'General',
            semestre: '00',
            creditos: '1',
            idTipoUsuario: 2, // 2 = Maestro
            estatus: true
        };
        
        // Enviar a la API
        const response = await fetch('http://localhost:5111/api/usuarios/maestro', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datosmaestro)
        });
        
        if (response.ok) {
            const resultado = await response.json();
            mostrarMensaje(`✅ Maestro registrado exitosamente. Matrícula: ${matricula}`, 'exito');
            document.getElementById('formMaestro').reset();
        } else {
            const error = await response.text();
            mostrarMensaje('❌ Error al registrar maestro: ' + error, 'error');
        }
        
    } catch (error) {
        console.error('Error:', error);
        mostrarMensaje('❌ Error de conexión con el servidor.', 'error');
    } finally {
        // Rehabilitar botón
        btnRegistrar.disabled = false;
        btnRegistrar.textContent = 'Registrar';
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
