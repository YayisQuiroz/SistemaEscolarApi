using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data;
using SistemaEscolarAPI.Models;

namespace SistemaEscolarAPI.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AlumnoController : ControllerBase
    {
        private readonly AppDbContext _context;

        public AlumnoController(AppDbContext context)
        {
            _context = context;
        }

        [HttpGet("datos/{alumnoId}")]
        public async Task<IActionResult> GetDatosAlumno(int alumnoId)
        {
            try
            {
                Console.WriteLine($"🟡 Obteniendo datos del alumno ID: {alumnoId}");

                // 1. Obtener información del alumno
                var alumno = await _context.Usuarios
                    .Where(u => u.Id == alumnoId && u.IdTipoUsuario == 3) // 3 = Alumno
                    .Select(u => new
                    {
                        u.Id,
                        u.Nombre,
                        u.Matricula,
                        u.Correo,
                        u.Especialidad,
                        u.Semestre
                    })
                    .FirstOrDefaultAsync();

                if (alumno == null)
                {
                    Console.WriteLine("❌ Alumno no encontrado");
                    return NotFound(new { message = "Alumno no encontrado" });
                }

                Console.WriteLine($"🟢 Alumno encontrado: {alumno.Nombre}");
                return Ok(new
                {
                    alumno = alumno,
                    message = "Datos obtenidos correctamente"
                });
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error: {ex.Message}");
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }

        [HttpGet("mis-actividades/{alumnoId}")]
        public async Task<IActionResult> GetMisActividades(int alumnoId)
        {
            try
            {
                Console.WriteLine($"🟡 Obteniendo actividades del alumno ID: {alumnoId}");

                // Obtener actividades donde el alumno está inscrito
                var misActividades = await _context.Registro
                    .Where(r => r.IdUsuario == alumnoId && r.Estatus)
                    .Join(_context.Actividad,
                        registro => registro.IdActividad,
                        actividad => actividad.Id,
                        (registro, actividad) => new
                        {
                            actividadId = actividad.Id,
                            nombre = actividad.Nombre,
                            estado = actividad.Estatus ? "Activo" : "Inactivo",
                            fechaInscripcion = registro.FechaCreacion,
                            maestroId = actividad.IdUsuarioMaestro
                        })
                    .Join(_context.Usuarios,
                        act => act.maestroId,
                        maestro => maestro.Id,
                        (act, maestro) => new
                        {
                            act.actividadId,
                            act.nombre,
                            act.estado,
                            act.fechaInscripcion,
                            maestro = maestro.Nombre,
                            maestroEspecialidad = maestro.Especialidad
                        })
                    .ToListAsync();

                Console.WriteLine($"🟢 Encontradas {misActividades.Count} actividades");
                return Ok(misActividades);
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error: {ex.Message}");
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }

        [HttpGet("actividades-disponibles/{alumnoId}")]
        public async Task<IActionResult> GetActividadesDisponibles(int alumnoId)
        {
            try
            {
                Console.WriteLine($"🟡 Obteniendo actividades disponibles para alumno ID: {alumnoId}");

                // Obtener IDs de actividades donde ya está inscrito
                var actividadesInscritas = await _context.Registro
                    .Where(r => r.IdUsuario == alumnoId && r.Estatus)
                    .Select(r => r.IdActividad)
                    .ToListAsync();

                // Obtener actividades disponibles (activas y no inscritas)
                var actividadesDisponibles = await _context.Actividad
                    .Where(a => a.Estatus && !actividadesInscritas.Contains(a.Id))
                    .Join(_context.Usuarios,
                        actividad => actividad.IdUsuarioMaestro,
                        maestro => maestro.Id,
                        (actividad, maestro) => new
                        {
                            id = actividad.Id,
                            nombre = actividad.Nombre,
                            maestro = maestro.Nombre,
                            maestroEspecialidad = maestro.Especialidad,
                            fechaCreacion = actividad.FechaCreacion,
                            // Calcular cupos (simulado por ahora)
                            cuposDisponibles = 25 - _context.Registro.Count(r => r.IdActividad == actividad.Id && r.Estatus),
                            cuposTotal = 25
                        })
                    .ToListAsync();

                Console.WriteLine($"🟢 Encontradas {actividadesDisponibles.Count} actividades disponibles");
                return Ok(actividadesDisponibles);
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error: {ex.Message}");
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }

        [HttpPost("inscribirse")]
        public async Task<IActionResult> InscribirseActividad([FromBody] InscripcionRequest request)
        {
            try
            {
                Console.WriteLine($"🟡 Inscribiendo alumno {request.AlumnoId} a actividad {request.ActividadId}");

                // Verificar que el alumno existe
                var alumno = await _context.Usuarios
                    .Where(u => u.Id == request.AlumnoId && u.IdTipoUsuario == 3)
                    .FirstOrDefaultAsync();

                if (alumno == null)
                    return NotFound(new { message = "Alumno no encontrado" });

                // Verificar que la actividad existe y está activa
                var actividad = await _context.Actividad
                    .Where(a => a.Id == request.ActividadId && a.Estatus)
                    .FirstOrDefaultAsync();

                if (actividad == null)
                    return NotFound(new { message = "Actividad no encontrada o inactiva" });

                // Verificar que no esté ya inscrito
                var yaInscrito = await _context.Registro
                    .AnyAsync(r => r.IdUsuario == request.AlumnoId &&
                                  r.IdActividad == request.ActividadId &&
                                  r.Estatus);

                if (yaInscrito)
                    return BadRequest(new { message = "Ya estás inscrito en esta actividad" });

                // Verificar cupos disponibles
                var inscritosActuales = await _context.Registro
                    .CountAsync(r => r.IdActividad == request.ActividadId && r.Estatus);

                if (inscritosActuales >= 25) // Límite de 25 por actividad
                    return BadRequest(new { message = "No hay cupos disponibles" });

                // Crear nuevo registro CON DESCRIPCIÓN
                var nuevoRegistro = new Registro
                {
                    IdUsuario = request.AlumnoId,
                    IdActividad = request.ActividadId,
                    Descripcion = "Inscripción automática", // ✅ AGREGAR ESTA LÍNEA
                    Estatus = true,
                    FechaCreacion = DateTime.Now,
                    FechaModificacion = DateTime.Now,
                    IdPeriodo = 1, // ✅ Si también es requerido
                    IdUsuarioCreador = request.AlumnoId,
                    IdUsuarioModificador = request.AlumnoId
                };

                _context.Registro.Add(nuevoRegistro);
                await _context.SaveChangesAsync();

                Console.WriteLine("🟢 Inscripción exitosa");
                return Ok(new { message = "Inscripción exitosa" });
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error: {ex.Message}");
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }


        [HttpDelete("desinscribirse/{alumnoId}/{actividadId}")]
        public async Task<IActionResult> DesinscribirseActividad(int alumnoId, int actividadId)
        {
            try
            {
                Console.WriteLine($"🟡 Desinscribiendo alumno {alumnoId} de actividad {actividadId}");

                var registro = await _context.Registro
                    .Where(r => r.IdUsuario == alumnoId &&
                               r.IdActividad == actividadId &&
                               r.Estatus)
                    .FirstOrDefaultAsync();

                if (registro == null)
                    return NotFound(new { message = "No estás inscrito en esta actividad" });

                // Marcar como inactivo en lugar de eliminar
                registro.Estatus = false;
                registro.FechaModificacion = DateTime.Now;

                await _context.SaveChangesAsync();

                Console.WriteLine("🟢 Desinscripción exitosa");
                return Ok(new { message = "Te has desinscrito exitosamente" });
            }
            catch (Exception ex)
            {
                Console.WriteLine($"❌ Error: {ex.Message}");
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }
    }

    // Modelo para la request de inscripción
    public class InscripcionRequest
    {
        public int AlumnoId { get; set; }
        public int ActividadId { get; set; }
    }
}
