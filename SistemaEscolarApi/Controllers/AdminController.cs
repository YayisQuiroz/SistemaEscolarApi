using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data;
using SistemaEscolarApi.Models;
using SistemaEscolarAPI.Models;
using System;
using System.Linq;
using System.Text;
using System.Security.Cryptography;

using System.Threading.Tasks;

namespace SistemaEscolarAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AdminController : ControllerBase
    {
        private readonly AppDbContext _context;

        public AdminController(AppDbContext context)
        {
            _context = context;
        }

        // Obtener todos los alumnos (paginado)
        [HttpGet("alumnos")]
        public async Task<IActionResult> GetAlumnos(int page = 1, int pageSize = 10)
        {
            try
            {
                var query = _context.Usuarios
                    .Where(u => u.IdTipoUsuario == 3 && u.Estatus)
                    .OrderBy(u => u.Matricula);

                var totalRecords = await query.CountAsync();
                var alumnos = await query
                    .Skip((page - 1) * pageSize)
                    .Take(pageSize)
                    .Select(u => new
                    {
                        u.Matricula,
                        u.Nombre,
                        u.Semestre,
                        Especialidad = u.Especialidad,
                        u.Correo,
                        u.FechaCreacion
                    })
                    .ToListAsync();

                return Ok(new
                {
                    Total = totalRecords,
                    Page = page,
                    PageSize = pageSize,
                    Data = alumnos
                });
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }
        [HttpGet("alumnos/count")]
        public async Task<IActionResult> GetAlumnosCount()
        {
            try
            {
                var totalAlumnos = await _context.Usuarios
                    .Where(u => u.IdTipoUsuario == 3 && u.Estatus)
                    .CountAsync();

                return Ok(new { Total = totalAlumnos });
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }



        [HttpGet("maestros")]
        public async Task<IActionResult> GetMaestros(int page = 1, int pageSize = 10)
        {
            try
            {
                var query = _context.Usuarios
                    .Where(u => u.IdTipoUsuario == 2 && u.Estatus) // 2 = Maestro
                    .OrderBy(u => u.Nombre);

                var totalRecords = await query.CountAsync();
                var maestros = await query
                    .Skip((page - 1) * pageSize)
                    .Take(pageSize)
                    .Select(u => new
                    {
                        u.Id,
                        u.Matricula,
                        u.Nombre,
                        u.Correo,
                        u.Especialidad,
                        u.FechaCreacion
                    })
                    .ToListAsync();

                return Ok(new
                {
                    Total = totalRecords,
                    Page = page,
                    PageSize = pageSize,
                    Data = maestros
                });
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }


        [HttpGet("alumno/{id}")]
        public async Task<IActionResult> GetAlumno(int id)
        {
            var alumno = await _context.Usuarios.FindAsync(id);
            if (alumno == null) return NotFound();
            return Ok(alumno);
        }


        // Crear nuevo alumno
        [HttpPost("alumno")]
        public async Task<IActionResult> CrearAlumno([FromBody] NuevoAlumnoRequest request)
        {
            try
            {
                // Validar matrícula única
                if (await _context.Usuarios.AnyAsync(u => u.Matricula == request.Matricula))
                    return Conflict("La matrícula ya existe");

                var nuevoAlumno = new Usuario2
                {
                    Nombre = request.Nombre,
                    Matricula = request.Matricula,
                    Correo = request.Correo,
                    Contraseña = HashearContraseña(request.Contraseña),
                    Especialidad = request.Especialidad,
                    Semestre = request.Semestre,
                    IdTipoUsuario = 3, // Alumno
                    Estatus = true,
                    FechaCreacion = DateTime.Now,
                    FechaModificacion = DateTime.Now
                };

                _context.Usuarios.Add(nuevoAlumno);
                await _context.SaveChangesAsync();

                return CreatedAtAction(nameof(GetAlumno), new { id = nuevoAlumno.Id }, nuevoAlumno);
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Error al crear alumno", error = ex.Message });
            }
        }

        // Actualizar alumno
        [HttpPut("alumno/{id}")]
        public async Task<IActionResult> ActualizarAlumno(int id, [FromBody] ActualizarAlumnoRequest request)
        {
            try
            {
                var alumno = await _context.Usuarios.FindAsync(id);
                if (alumno == null) return NotFound();

                alumno.Nombre = request.Nombre ?? alumno.Nombre;
                alumno.Correo = request.Correo ?? alumno.Correo;
                alumno.Especialidad = request.Especialidad ?? alumno.Especialidad;
                alumno.Semestre = request.Semestre ?? alumno.Semestre;
                alumno.FechaModificacion = DateTime.Now;

                if (!string.IsNullOrEmpty(request.Contraseña))
                    alumno.Contraseña = HashearContraseña(request.Contraseña);

                await _context.SaveChangesAsync();
                return NoContent();
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Error al actualizar", error = ex.Message });
            }
        }

        // Desactivar alumno
        [HttpDelete("alumno/{id}")]
        public async Task<IActionResult> DesactivarAlumno(int id)
        {
            try
            {
                var alumno = await _context.Usuarios.FindAsync(id);
                if (alumno == null) return NotFound();

                alumno.Estatus = false;
                alumno.FechaModificacion = DateTime.Now;

                await _context.SaveChangesAsync();
                return NoContent();
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Error al desactivar", error = ex.Message });
            }
        }

        private string HashearContraseña(string contraseña)
        {
            // Usar el mismo método de hasheo que en LoginController
            using var sha256 = SHA256.Create();
            var bytes = sha256.ComputeHash(Encoding.UTF8.GetBytes(contraseña));
            return BitConverter.ToString(bytes).Replace("-", "").ToLower();
        }
    }

    // Modelos de request
    public class NuevoAlumnoRequest
    {
        public string Nombre { get; set; }
        public string Matricula { get; set; }
        public string Correo { get; set; }
        public string Contraseña { get; set; }
        public string Especialidad { get; set; }
        public string Semestre { get; set; }
    }

    public class ActualizarAlumnoRequest
    {
        public string Nombre { get; set; }
        public string Correo { get; set; }
        public string Contraseña { get; set; }
        public string Especialidad { get; set; }
        public string Semestre { get; set; }
    }
}
