using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data;
using SistemaEscolarAPI.Models;
using System.Security.Cryptography;
using System.Text;

namespace SistemaEscolarAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class LoginController : ControllerBase
    {
        private readonly AppDbContext _context;

        public LoginController(AppDbContext context)
        {
            _context = context;
        }

        [HttpPost]
        public async Task<IActionResult> Login([FromBody] LoginRequest request)
        {
            try
            {
                Console.WriteLine($"🟡 Login attempt - Usuario: {request.Usuario}, Contraseña: {request.Contraseña}");

                if (string.IsNullOrEmpty(request.Usuario) || string.IsNullOrEmpty(request.Contraseña))
                {
                    Console.WriteLine("❌ Datos vacíos");
                    return BadRequest(new { message = "Usuario y contraseña son requeridos" });
                }

                // Buscar usuario por NOMBRE, Matrícula o Correo
                var usuario = await _context.Usuarios
                    .FirstOrDefaultAsync(u =>
                        (u.Nombre == request.Usuario ||
                         u.Matricula == request.Usuario ||
                         u.Correo == request.Usuario)
                        && u.Estatus == true);

                if (usuario == null)
                {
                    Console.WriteLine("❌ Usuario no encontrado");
                    return Unauthorized(new { message = "Credenciales incorrectas" });
                }

                // Verificar contraseña (asumiendo que está hasheada en la BD)
                if (!VerificarContraseña(request.Contraseña, usuario.Contraseña))
                {
                    Console.WriteLine("❌ Contraseña incorrecta");
                    return Unauthorized(new { message = "Credenciales incorrectas" });
                }

                Console.WriteLine("🟢 Contraseña correcta");

                // Determinar rol basado en IdTipoUsuario
                string rol = DeterminarRol(usuario.IdTipoUsuario);

                // RESPUESTA COMPATIBLE CON EL FRONTEND
                var response = new
                {
                    // Datos para el frontend (formato plano)
                    id = usuario.Id,
                    nombre = usuario.Nombre,
                    tipoUsuario = usuario.IdTipoUsuario,
                    especialidad = usuario.Especialidad,
                    matricula = usuario.Matricula,
                    correo = usuario.Correo,
                    semestre = usuario.Semestre,
                    message = "Login exitoso",

                    // Datos adicionales (formato original si los necesitas)
                    rol = rol,
                    usuario = new UsuarioInfo
                    {
                        Id = usuario.Id,
                        Nombre = usuario.Nombre,
                        Matricula = usuario.Matricula,
                        Correo = usuario.Correo,
                        Especialidad = usuario.Especialidad,
                        Semestre = usuario.Semestre
                    }
                };

                Console.WriteLine($"🟢 Login exitoso - Rol: {rol}, ID: {usuario.Id}");
                return Ok(response);
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error: {ex.Message}");
                return StatusCode(500, new { message = "Error interno del servidor", error = ex.Message });
            }
        }

        // Agregar endpoint para logout (opcional)
        [HttpPost("logout")]
        public IActionResult Logout()
        {
            try
            {
                Console.WriteLine("🟡 Logout request");
                return Ok(new { message = "Logout exitoso" });
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error en logout: {ex.Message}");
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }

        private bool VerificarContraseña(string contraseñaIngresada, string contraseñaHash)
        {
            // Primero intentar comparación directa (texto plano)
            if (contraseñaIngresada == contraseñaHash)
            {
                return true;
            }

            // Si están hasheadas con SHA256
            string hashIngresada = HashearContraseña(contraseñaIngresada);
            Console.WriteLine($"🟡 Hash generado: {hashIngresada}");

            if (hashIngresada == contraseñaHash)
            {
                return true;
            }

            Console.WriteLine("❌ Contraseña no válida");
            return false;
        }

        private string HashearContraseña(string contraseña)
        {
            using (SHA256 sha256Hash = SHA256.Create())
            {
                byte[] bytes = sha256Hash.ComputeHash(Encoding.UTF8.GetBytes(contraseña));
                StringBuilder builder = new StringBuilder();
                for (int i = 0; i < bytes.Length; i++)
                {
                    builder.Append(bytes[i].ToString("x2"));
                }
                return builder.ToString();
            }
        }

        private string DeterminarRol(int? idTipoUsuario)
        {
            return idTipoUsuario switch
            {
                1 => "Admin",
                2 => "Maestro",
                3 => "Alumno",
                _ => "Alumno"
            };
        }
    }

    // Modelos para las requests y responses (sin cambios)
    public class LoginRequest
    {
        public string Usuario { get; set; }
        public string Contraseña { get; set; }
    }

    public class LoginResponse
    {
        public string Rol { get; set; }
        public UsuarioInfo Usuario { get; set; }
    }

    public class UsuarioInfo
    {
        public int Id { get; set; }
        public string Nombre { get; set; }
        public string Matricula { get; set; }
        public string Correo { get; set; }
        public string Especialidad { get; set; }
        public string Semestre { get; set; }
    }
}
