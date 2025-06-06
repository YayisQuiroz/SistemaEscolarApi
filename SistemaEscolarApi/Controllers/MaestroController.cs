using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data;

[ApiController]
[Route("api/[controller]")]
public class MaestroController : ControllerBase
{
    private readonly AppDbContext _context;

    public MaestroController(AppDbContext context)
    {
        _context = context;
    }

    [HttpGet("datos/{maestroId}")]
    public async Task<IActionResult> GetDatosMaestro(int maestroId)
    {
        try
        {
            // 1. Obtener información del maestro
            var maestro = await _context.Usuarios
                .Where(u => u.Id == maestroId && u.IdTipoUsuario == 2) // 2 = Maestro
                .Select(u => new
                {
                    u.Id,
                    u.Nombre,
                    u.Especialidad
                })
                .FirstOrDefaultAsync();

            if (maestro == null)
                return NotFound(new { message = "Maestro no encontrado" });

            // 2. Obtener la actividad del maestro
            var actividad = await _context.Actividad
                .Where(a => a.IdUsuarioMaestro == maestroId)
                .Select(a => new
                {
                    a.Id,
                    a.Nombre,
                    Estado = a.Estatus ? "Activo" : "Inactivo"
                })
                .FirstOrDefaultAsync();

            if (actividad == null)
                return NotFound(new { message = "No se encontró actividad para este maestro" });

            // 3. Obtener alumnos inscritos en la actividad
            var alumnos = await _context.Registro
                .Where(r => r.IdActividad == actividad.Id && r.Estatus)
                .Join(_context.Usuarios,
                    registro => registro.IdUsuario,
                    alumno => alumno.Id,
                    (registro, alumno) => new
                    {
                        alumno.Id,
                        alumno.Matricula,
                        alumno.Nombre,
                        alumno.Semestre,
                        alumno.Correo,
                        alumno.Telefono
                    })
                .ToListAsync();

            return Ok(new
            {
                Maestro = maestro,
                Actividad = actividad,
                TotalAlumnos = alumnos.Count,
                Alumnos = alumnos
            });
        }
        catch (Exception ex)
        {
            return StatusCode(500, new { message = "Error interno", error = ex.Message });
        }
    }
}