using Microsoft.EntityFrameworkCore;

namespace Programowanie2_Projekt2.Models
{
    public class AppDbContext : DbContext
    {
        public AppDbContext(DbContextOptions<AppDbContext> options) : base(options) { } //konstruktor    
        public DbSet<Book> Books { get; set; }  //lista książek
    }
}
