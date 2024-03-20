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
        public Book Book { get; set; }  //Model ksi��ki
        public byte[] filebytes;        //strumie� bit�w do wy�wietlenia obrazka

        public ReadModel(AppDbContext context)
        {
            _context = context;     //pobranie kontekstu
        }

        public void OnGet(int Id)
        {
            Book = _context.Books.Find(Id); //po dostarczeniu id przez "GET" pobieramy z kontekstu konkretny obiekt ksi��ki
            if (Book.Image != null)     //je�eli ma obrazek, to przygotowujemy si� do wy�wietlenia go
            {   
                string path = Path.Combine(Environment.CurrentDirectory, @".\Data\", Book.Image);
                filebytes = System.IO.File.ReadAllBytes(path);
            }
        }

        public IActionResult OnPostDelete(int Id)
        {
            Book itemToDelete = _context.Books.Find(Id);  //przeszukujemy kontekst pod k�tem usuwanego obiektu
            if (itemToDelete == null)
            {
                return NotFound();
            }
            _context.Books.Remove(itemToDelete);    //usuwanie wpisu
            _context.SaveChanges();
            if (itemToDelete.Image != null) //usuwanie pliku ok�adki je�li istnieje
            {
                string filepath = System.IO.Path.Combine(Environment.CurrentDirectory, @".\Data\", itemToDelete.Image);
                System.IO.File.Delete(filepath);
            }
            return RedirectToPage("/Forms/View");   //po usuni�ciu wr�� do widoku g��wnego
        }
    }
}
