using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data;
using SistemaEscolarApi.Models;

namespace SistemaEscolarApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class UsuariosController : ControllerBase
    {
        private readonly AppDbContext _context;

        public UsuariosController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/Usuarios - CON PAGINACIÓN
        [HttpGet]
        public async Task<ActionResult<PaginatedResult<Usuario2>>> GetUsuarios(
            [FromQuery] int page = 1,
            [FromQuery] int pageSize = 10,
            [FromQuery] string? search = null,
            [FromQuery] int? tipoUsuario = null,
            [FromQuery] bool? estatus = null)
        {
            if (page < 1) page = 1;
            if (pageSize < 1 || pageSize > 100) pageSize = 10; // Máximo 100 registros por página

            var query = _context.Usuarios.AsQueryable();

            // Filtros opcionales
            if (!string.IsNullOrEmpty(search))
            {
                query = query.Where(u => u.Nombre.Contains(search) ||
                                        u.Matricula.Contains(search) ||
                                        u.Correo.Contains(search));
            }

            if (tipoUsuario.HasValue)
            {
                query = query.Where(u => u.IdTipoUsuario == tipoUsuario.Value);
            }

            if (estatus.HasValue)
            {
                query = query.Where(u => u.Estatus == estatus.Value);
            }

            // Contar total de registros
            var totalRecords = await query.CountAsync();
            var totalPages = (int)Math.Ceiling((double)totalRecords / pageSize);

            // Aplicar paginación
            var usuarios = await query
                .OrderBy(u => u.Id)
                .Skip((page - 1) * pageSize)
                .Take(pageSize)
                .ToListAsync();

            var result = new PaginatedResult<Usuario2>
            {
                Data = usuarios,
                CurrentPage = page,
                PageSize = pageSize,
                TotalRecords = totalRecords,
                TotalPages = totalPages,
                HasNextPage = page < totalPages,
                HasPreviousPage = page > 1
            };

            return Ok(result);
        }

        // GET: api/Usuarios/maestros - MAESTROS CON PAGINACIÓN
        [HttpGet("maestros")]
        public async Task<ActionResult<PaginatedResult<Usuario2>>> GetMaestros(
            [FromQuery] int page = 1,
            [FromQuery] int pageSize = 10,
            [FromQuery] string? search = null)
        {
            if (page < 1) page = 1;
            if (pageSize < 1 || pageSize > 100) pageSize = 10;

            var query = _context.Usuarios
                .Where(u => u.IdTipoUsuario == 2 && u.Estatus == true);

            // Filtro de búsqueda
            if (!string.IsNullOrEmpty(search))
            {
                query = query.Where(u => u.Nombre.Contains(search) ||
                                        u.Matricula.Contains(search) ||
                                        u.Correo.Contains(search));
            }

            var totalRecords = await query.CountAsync();
            var totalPages = (int)Math.Ceiling((double)totalRecords / pageSize);

            var maestros = await query
                .OrderBy(u => u.Nombre)
                .Skip((page - 1) * pageSize)
                .Take(pageSize)
                .ToListAsync();

            var result = new PaginatedResult<Usuario2>
            {
                Data = maestros,
                CurrentPage = page,
                PageSize = pageSize,
                TotalRecords = totalRecords,
                TotalPages = totalPages,
                HasNextPage = page < totalPages,
                HasPreviousPage = page > 1
            };

            return Ok(result);
        }

        // GET: api/Usuarios/alumnos - ALUMNOS CON PAGINACIÓN
        [HttpGet("alumnos")]
        public async Task<ActionResult<PaginatedResult<Usuario2>>> GetAlumnos(
            [FromQuery] int page = 1,
            [FromQuery] int pageSize = 10,
            [FromQuery] string? search = null,
            [FromQuery] string? especialidad = null,
            [FromQuery] string? semestre = null)
        {
            if (page < 1) page = 1;
            if (pageSize < 1 || pageSize > 100) pageSize = 10;

            var query = _context.Usuarios
                .Where(u => u.IdTipoUsuario == 3 && u.Estatus == true);

            // Filtros específicos para alumnos
            if (!string.IsNullOrEmpty(search))
            {
                query = query.Where(u => u.Nombre.Contains(search) ||
                                        u.Matricula.Contains(search) ||
                                        u.Correo.Contains(search));
            }

            if (!string.IsNullOrEmpty(especialidad))
            {
                query = query.Where(u => u.Especialidad.Contains(especialidad));
            }

            if (!string.IsNullOrEmpty(semestre))
            {
                query = query.Where(u => u.Semestre == semestre);
            }

            var totalRecords = await query.CountAsync();
            var totalPages = (int)Math.Ceiling((double)totalRecords / pageSize);

            var alumnos = await query
                .OrderBy(u => u.Nombre)
                .Skip((page - 1) * pageSize)
                .Take(pageSize)
                .ToListAsync();

            var result = new PaginatedResult<Usuario2>
            {
                Data = alumnos,
                CurrentPage = page,
                PageSize = pageSize,
                TotalRecords = totalRecords,
                TotalPages = totalPages,
                HasNextPage = page < totalPages,
                HasPreviousPage = page > 1
            };

            return Ok(result);
        }

        // GET: api/Usuarios/5
        [HttpGet("{id}")]
        public async Task<ActionResult<Usuario2>> GetUsuario(int id)
        {
            var usuario = await _context.Usuarios.FindAsync(id);

            if (usuario == null)
            {
                return NotFound(new { message = "Usuario no encontrado" });
            }

            return usuario;
        }

        // POST: api/Usuarios
        [HttpPost]
        public async Task<ActionResult<Usuario2>> CreateUsuario(Usuario2 usuario)
        {
            try
            {
                // Validar que no exista la matrícula
                if (await _context.Usuarios.AnyAsync(u => u.Matricula == usuario.Matricula))
                    return BadRequest(new { message = "La matrícula ya existe" });

                // Validar que no exista el correo
                if (await _context.Usuarios.AnyAsync(u => u.Correo == usuario.Correo))
                    return BadRequest(new { message = "El correo ya está registrado" });

                // Hashear contraseña
                usuario.Contraseña = HashearContraseña(usuario.Contraseña);

                usuario.FechaCreacion = DateTime.Now;
                usuario.FechaModificacion = DateTime.Now;
                usuario.Estatus = true;
                usuario.IdUsuarioCreador = 1;
                usuario.IdUsuarioModificador = 1;

                _context.Usuarios.Add(usuario);
                await _context.SaveChangesAsync();

                return CreatedAtAction(nameof(GetUsuario), new { id = usuario.Id }, usuario);
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }

        // POST: api/Usuarios/maestro
        [HttpPost("maestro")]
        public async Task<ActionResult<Usuario2>> CreateMaestro(Usuario2 maestro)
        {
            try
            {
                // Validar que no exista la matrícula
                if (await _context.Usuarios.AnyAsync(u => u.Matricula == maestro.Matricula))
                    return BadRequest(new { message = "La matrícula ya existe" });

                // Validar que no exista el correo
                if (await _context.Usuarios.AnyAsync(u => u.Correo == maestro.Correo))
                    return BadRequest(new { message = "El correo ya está registrado" });

                // Hashear contraseña
                maestro.Contraseña = HashearContraseña(maestro.Contraseña);

                // Configurar campos automáticos
                maestro.IdTipoUsuario = 2; // Forzar que sea maestro
                maestro.FechaCreacion = DateTime.Now;
                maestro.FechaModificacion = DateTime.Now;
                maestro.Estatus = true;
                maestro.IdUsuarioCreador = 1;
                maestro.IdUsuarioModificador = 1;

                _context.Usuarios.Add(maestro);
                await _context.SaveChangesAsync();

                return CreatedAtAction(nameof(GetUsuario), new { id = maestro.Id }, maestro);
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }

        // POST: api/Usuarios/alumno
        [HttpPost("alumno")]
        public async Task<ActionResult<Usuario2>> CreateAlumno(Usuario2 alumno)
        {
            try
            {
                // Validar que no exista la matrícula
                if (await _context.Usuarios.AnyAsync(u => u.Matricula == alumno.Matricula))
                    return BadRequest(new { message = "La matrícula ya existe" });

                // Validar que no exista el correo
                if (await _context.Usuarios.AnyAsync(u => u.Correo == alumno.Correo))
                    return BadRequest(new { message = "El correo ya está registrado" });

                // Hashear contraseña
                alumno.Contraseña = HashearContraseña(alumno.Contraseña);

                // Configurar campos automáticos
                alumno.IdTipoUsuario = 3; // 3 = Alumno
                alumno.FechaCreacion = DateTime.Now;
                alumno.FechaModificacion = DateTime.Now;
                alumno.Estatus = true;
                alumno.IdUsuarioCreador = 1;
                alumno.IdUsuarioModificador = 1;

                _context.Usuarios.Add(alumno);
                await _context.SaveChangesAsync();

                return CreatedAtAction(nameof(GetUsuario), new { id = alumno.Id }, alumno);
            }
            catch (Exception ex)
            {
                return StatusCode(500, new { message = "Error interno", error = ex.Message });
            }
        }

        // PUT: api/Usuarios/5
        [HttpPut("{id}")]
        public async Task<IActionResult> UpdateUsuario(int id, UpdateUsuarioRequest request)
        {
            var usuario = await _context.Usuarios.FindAsync(id);
            if (usuario == null)
                return NotFound(new { message = "Usuario no encontrado" });

            // Validar matrícula única (solo si se está cambiando)
            if (!string.IsNullOrEmpty(request.Matricula) && request.Matricula != usuario.Matricula)
            {
                if (await _context.Usuarios.AnyAsync(u => u.Matricula == request.Matricula))
                    return BadRequest(new { message = "La matrícula ya existe" });
            }

            // Validar correo único (solo si se está cambiando)
            if (!string.IsNullOrEmpty(request.Correo) && request.Correo != usuario.Correo)
            {
                if (await _context.Usuarios.AnyAsync(u => u.Correo == request.Correo))
                    return BadRequest(new { message = "El correo ya está registrado" });
            }

            // Actualizar campos solo si vienen en el request
            if (!string.IsNullOrEmpty(request.Nombre))
                usuario.Nombre = request.Nombre;
            if (!string.IsNullOrEmpty(request.Matricula))
                usuario.Matricula = request.Matricula;
            if (!string.IsNullOrEmpty(request.Correo))
                usuario.Correo = request.Correo;
            if (!string.IsNullOrEmpty(request.Especialidad))
                usuario.Especialidad = request.Especialidad;
            if (!string.IsNullOrEmpty(request.Semestre))
                usuario.Semestre = request.Semestre;

            // Actualizar contraseña si se proporciona
            if (!string.IsNullOrEmpty(request.Contraseña))
                usuario.Contraseña = HashearContraseña(request.Contraseña);

            usuario.FechaModificacion = DateTime.Now;
            usuario.IdUsuarioModificador = 1;

            try
            {
                await _context.SaveChangesAsync();
                return Ok(new { message = "Usuario actualizado correctamente" });
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!UsuarioExists(id))
                    return NotFound(new { message = "Usuario no encontrado" });
                throw;
            }
        }

        // DELETE: api/Usuarios/5
        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteUsuario(int id)
        {
            var usuario = await _context.Usuarios.FindAsync(id);
            if (usuario == null)
                return NotFound(new { message = "Usuario no encontrado" });

            if (!usuario.Estatus)
                return BadRequest(new { message = "El usuario ya está desactivado" });

            // Eliminación lógica
            usuario.Estatus = false;
            usuario.FechaModificacion = DateTime.Now;
            usuario.IdUsuarioModificador = 1;

            await _context.SaveChangesAsync();
            return Ok(new { message = "Usuario desactivado correctamente" });
        }

        // PATCH: api/Usuarios/5/reactivar
        [HttpPatch("{id}/reactivar")]
        public async Task<IActionResult> ReactivarUsuario(int id)
        {
            var usuario = await _context.Usuarios.FindAsync(id);
            if (usuario == null)
                return NotFound(new { message = "Usuario no encontrado" });

            if (usuario.Estatus)
                return BadRequest(new { message = "El usuario ya está activo" });

            usuario.Estatus = true;
            usuario.FechaModificacion = DateTime.Now;
            usuario.IdUsuarioModificador = 1;

            await _context.SaveChangesAsync();
            return Ok(new { message = "Usuario reactivado correctamente" });
        }

        // Método para hashear contraseña
        private string HashearContraseña(string contraseña)
        {
            using (var sha256 = System.Security.Cryptography.SHA256.Create())
            {
                byte[] bytes = sha256.ComputeHash(System.Text.Encoding.UTF8.GetBytes(contraseña));
                return Convert.ToHexString(bytes).ToLower();
            }
        }

        private bool UsuarioExists(int id)
        {
            return _context.Usuarios.Any(e => e.Id == id);
        }
    }

    // DTO para la actualización
    public class UpdateUsuarioRequest
    {
        public string? Nombre { get; set; }
        public string? Matricula { get; set; }
        public string? Correo { get; set; }
        public string? Especialidad { get; set; }
        public string? Semestre { get; set; }
        public string? Contraseña { get; set; }
    }

    // CLASE PARA PAGINACIÓN
    public class PaginatedResult<T>
    {
        public List<T> Data { get; set; } = new List<T>();
        public int CurrentPage { get; set; }
        public int PageSize { get; set; }
        public int TotalRecords { get; set; }
        public int TotalPages { get; set; }
        public bool HasNextPage { get; set; }
        public bool HasPreviousPage { get; set; }
    }
}

