using Microsoft.EntityFrameworkCore;
using SistemaEscolarApi.Data; // Asegúrate que este namespace apunta donde está tu AppDbContext

var builder = WebApplication.CreateBuilder(args);

// 👇 Agregamos el DbContext y lo conectamos a MySQL
builder.Services.AddDbContext<AppDbContext>(options =>
    options.UseMySql(
        builder.Configuration.GetConnectionString("DefaultConnection"),
        new MySqlServerVersion(new Version(8, 0, 25)) // cambia a la versión de tu MySQL si es diferente
    )
);

// Otros servicios del contenedor
builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

// 👇 AGREGAR CORS AQUÍ
builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowAll", builder =>
    {
        builder
            .AllowAnyOrigin()
            .AllowAnyMethod()
            .AllowAnyHeader();
    });
});

var app = builder.Build();

// Configurar el pipeline HTTP
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

// Comentar esta línea para desarrollo
// app.UseHttpsRedirection();

// 👇 USAR CORS AQUÍ (ANTES DE UseAuthorization)
app.UseCors("AllowAll");

app.UseAuthorization();
app.MapControllers();

app.Run();