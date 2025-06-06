namespace SistemaEscolarApi.Models
{
    public class Usuario2
    {
        public int Id { get; set; }
        public string Nombre { get; set; }
        public string Matricula { get; set; }
        public string Especialidad { get; set; }
        public string Correo { get; set; }
        public string Telefono { get; set; }
        public string Semestre { get; set; }
        public string Contraseña { get; set; }
        public string Creditos { get; set; }
        public int? IdTipoUsuario { get; set; }
        public int IdUsuarioCreador { get; set; }
        public int IdUsuarioModificador { get; set; }
        public DateTime FechaCreacion { get; set; }
        public DateTime FechaModificacion { get; set; }
        public bool Estatus { get; set; }
    }
}
