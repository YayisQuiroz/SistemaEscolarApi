using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Models;
using SistemaEscolarAPI.Models;

namespace SistemaEscolarApi.Data
{
   
        public class AppDbContext : DbContext
        {
            public AppDbContext(DbContextOptions<AppDbContext> options)
                : base(options)
            {
            }

            // Aquí defines las tablas que vas a mapear con la base de datos
            public DbSet<Usuario2> Usuarios { get; set; }
            public DbSet<TipoUsuario> TiposUsuarios { get; set; }
            public DbSet<Actividad> Actividad { get; set; }
            public DbSet<Periodo> Periodo { get; set; }
            public DbSet<Registro> Registro { get; set; }
        }
    }

