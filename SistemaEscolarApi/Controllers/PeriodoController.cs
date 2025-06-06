using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data;
using SistemaEscolarAPI.Models;

[Route("api/[controller]")]
[ApiController]
public class PeriodoController : ControllerBase
{
    private readonly AppDbContext _context;

    public PeriodoController(AppDbContext context)
    {
        _context = context;
    }

    [HttpGet]
    public async Task<ActionResult<IEnumerable<Periodo>>> GetPeriodos()
    {
        return await _context.Periodo.ToListAsync();
    }

    [HttpGet("{id}")]
    public async Task<ActionResult<Periodo>> GetPeriodo(int id)
    {
        var periodo = await _context.Periodo.FindAsync(id);
        if (periodo == null) return NotFound();
        return periodo;
    }

    [HttpPost]
    public async Task<ActionResult<Periodo>> CreatePeriodo(Periodo periodo)
    {
        periodo.FechaCreacion = periodo.FechaModificacion = DateTime.Now;
        _context.Periodo.Add(periodo);
        await _context.SaveChangesAsync();
        return CreatedAtAction(nameof(GetPeriodo), new { id = periodo.Id }, periodo);
    }

    [HttpPut("{id}")]
    public async Task<IActionResult> UpdatePeriodo(int id, Periodo periodo)
    {
        if (id != periodo.Id) return BadRequest();
        periodo.FechaModificacion = DateTime.Now;
        _context.Entry(periodo).State = EntityState.Modified;
        await _context.SaveChangesAsync();
        return NoContent();
    }

    [HttpDelete("{id}")]
    public async Task<IActionResult> DeletePeriodo(int id)
    {
        var periodo = await _context.Periodo.FindAsync(id);
        if (periodo == null) return NotFound();
        _context.Periodo.Remove(periodo);
        await _context.SaveChangesAsync();
        return NoContent();
    }
}