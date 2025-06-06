using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data;
using SistemaEscolarAPI.Models;

[Route("api/[controller]")]
[ApiController]
public class TiposUsuariosController : ControllerBase
{
    private readonly AppDbContext _context;

    public TiposUsuariosController(AppDbContext context)
    {
        _context = context;
    }

    [HttpGet]
    public async Task<ActionResult<IEnumerable<TipoUsuario>>> GetTiposUsuarios()
    {
        return await _context.TiposUsuarios.ToListAsync();
    }

    [HttpGet("{id}")]
    public async Task<ActionResult<TipoUsuario>> GetTipoUsuario(int id)
    {
        var tipo = await _context.TiposUsuarios.FindAsync(id);
        if (tipo == null) return NotFound();
        return tipo;
    }

    [HttpPost]
    public async Task<ActionResult<TipoUsuario>> CreateTipoUsuario(TipoUsuario tipo)
    {
        tipo.FechaCreacion = tipo.FechaModificacion = DateTime.Now;
        _context.TiposUsuarios.Add(tipo);
        await _context.SaveChangesAsync();
        return CreatedAtAction(nameof(GetTipoUsuario), new { id = tipo.Id }, tipo);
    }

    [HttpPut("{id}")]
    public async Task<IActionResult> UpdateTipoUsuario(int id, TipoUsuario tipo)
    {
        if (id != tipo.Id) return BadRequest();
        tipo.FechaModificacion = DateTime.Now;
        _context.Entry(tipo).State = EntityState.Modified;
        await _context.SaveChangesAsync();
        return NoContent();
    }

    [HttpDelete("{id}")]
    public async Task<IActionResult> DeleteTipoUsuario(int id)
    {
        var tipo = await _context.TiposUsuarios.FindAsync(id);
        if (tipo == null) return NotFound();
        _context.TiposUsuarios.Remove(tipo);
        await _context.SaveChangesAsync();
        return NoContent();
    }
}