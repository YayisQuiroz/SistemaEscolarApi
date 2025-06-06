namespace SistemaEscolarAPI.Models
{
    public class TipoUsuario
    {
        public int Id { get; set; }
        public string Descripcion { get; set; }
        public int IdUsuarioCreador { get; set; }
        public int IdUsuarioModificador { get; set; }
        public DateTime FechaCreacion { get; set; }
        public DateTime FechaModificacion { get; set; }
        public bool Estatus { get; set; }
    }
}
