using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data;
using SistemaEscolarAPI.Models;

[Route("api/[controller]")]
[ApiController]
public class RegistroController : ControllerBase
{
    private readonly AppDbContext _context;

    public RegistroController(AppDbContext context)
    {
        _context = context;
    }

    [HttpGet]
    public async Task<ActionResult<IEnumerable<Registro>>> GetRegistros()
    {
        return await _context.Registro.ToListAsync();
    }

    [HttpGet("{id}")]
    public async Task<ActionResult<Registro>> GetRegistro(int id)
    {
        var registro = await _context.Registro.FindAsync(id);
        if (registro == null) return NotFound();
        return registro;
    }

    [HttpGet("por-actividad/{actividadId}")]
    public async Task<ActionResult<IEnumerable<Registro>>> GetRegistrosPorActividad(int actividadId)
    {
        return await _context.Registro
            .Where(r => r.IdActividad == actividadId)
            .ToListAsync();
    }


    [HttpPost]
    public async Task<ActionResult<Registro>> CreateRegistro(Registro registro)
    {
        registro.FechaCreacion = registro.FechaModificacion = DateTime.Now;
        _context.Registro.Add(registro);
        await _context.SaveChangesAsync();
        return CreatedAtAction(nameof(GetRegistro), new { id = registro.Id }, registro);
    }

    [HttpPut("{id}")]
    public async Task<IActionResult> UpdateRegistro(int id, Registro registro)
    {
        if (id != registro.Id) return BadRequest();
        registro.FechaModificacion = DateTime.Now;
        _context.Entry(registro).State = EntityState.Modified;
        await _context.SaveChangesAsync();
        return NoContent();
    }

    [HttpDelete("{id}")]
    public async Task<IActionResult> DeleteRegistro(int id)
    {
        var registro = await _context.Registro.FindAsync(id);
        if (registro == null) return NotFound();
        _context.Registro.Remove(registro);
        await _context.SaveChangesAsync();
        return NoContent();
    }
}