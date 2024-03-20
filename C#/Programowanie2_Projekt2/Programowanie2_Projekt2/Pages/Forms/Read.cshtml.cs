using System;
using System.IO;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

using Programowanie2_Projekt2.Models;

namespace Programowanie2_Projekt2.Pages.Forms
{
    public class ReadModel : PageModel
    {
        private AppDbContext _context;   //kontekst

        [BindProperty]
        public Book Book { get; set; }  //Model ksi¹¿ki
        public byte[] filebytes;        //strumieñ bitów do wyœwietlenia obrazka

        public ReadModel(AppDbContext context)
        {
            _context = context;     //pobranie kontekstu
        }

        public void OnGet(int Id)
        {
            Book = _context.Books.Find(Id); //po dostarczeniu id przez "GET" pobieramy z kontekstu konkretny obiekt ksi¹¿ki
            if (Book.Image != null)     //je¿eli ma obrazek, to przygotowujemy siê do wyœwietlenia go
            {   
                string path = Path.Combine(Environment.CurrentDirectory, @".\Data\", Book.Image);
                filebytes = System.IO.File.ReadAllBytes(path);
            }
        }

        public IActionResult OnPostDelete(int Id)
        {
            Book itemToDelete = _context.Books.Find(Id);  //przeszukujemy kontekst pod k¹tem usuwanego obiektu
            if (itemToDelete == null)
            {
                return NotFound();
            }
            _context.Books.Remove(itemToDelete);    //usuwanie wpisu
            _context.SaveChanges();
            if (itemToDelete.Image != null) //usuwanie pliku ok³adki jeœli istnieje
            {
                string filepath = System.IO.Path.Combine(Environment.CurrentDirectory, @".\Data\", itemToDelete.Image);
                System.IO.File.Delete(filepath);
            }
            return RedirectToPage("/Forms/View");   //po usuniêciu wróæ do widoku g³ównego
        }
    }
}
