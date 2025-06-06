using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data;
using SistemaEscolarAPI.Models;

[Route("api/[controller]")]
[ApiController]
public class ActividadController : ControllerBase
{
    private readonly AppDbContext _context;

    public ActividadController(AppDbContext context)
    {
        _context = context;
    }

    [HttpGet]
    public async Task<ActionResult<IEnumerable<Actividad>>> GetActividades()
    {
        return await _context.Actividad.ToListAsync();
    }

    [HttpGet("{id}")]
    public async Task<ActionResult<Actividad>> GetActividad(int id)
    {
        var actividad = await _context.Actividad.FindAsync(id);
        if (actividad == null) return NotFound();
        return actividad;
    }

    [HttpPost]
    public async Task<ActionResult<Actividad>> CreateActividad(Actividad actividad)
    {
        actividad.IdUsuarioCreador = 1; 
        actividad.IdUsuarioModificador = 1;
        actividad.FechaCreacion = DateTime.Now;
        actividad.FechaModificacion = DateTime.Now;

        _context.Actividad.Add(actividad);
        await _context.SaveChangesAsync();
        return CreatedAtAction(nameof(GetActividad), new { id = actividad.Id }, actividad);
    }

    [HttpPut("{id}")]
    public async Task<IActionResult> UpdateActividad(int id, Actividad actividad)
    {
        if (id != actividad.Id) return BadRequest();
        actividad.FechaModificacion = DateTime.Now;
        _context.Entry(actividad).State = EntityState.Modified;
        await _context.SaveChangesAsync();
        return NoContent();
    }

    [HttpDelete("{id}")]
    public async Task<IActionResult> DeleteActividad(int id)
    {
        var actividad = await _context.Actividad.FindAsync(id);
        if (actividad == null) return NotFound();
        _context.Actividad.Remove(actividad);
        await _context.SaveChangesAsync();
        return NoContent();
    }
}