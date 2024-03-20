using System;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

using Programowanie2_Projekt2.Models;
using Microsoft.AspNetCore.Http;
using System.IO;
using System.Collections.Generic;
using System.Linq;

namespace Programowanie2_Projekt2.Pages.Forms
{
    public class UpdateModel : PageModel
    {
        private readonly AppDbContext _context; //kontekst

        [BindProperty]
        public bool TitleTaken { get; set; }    //flaga niedost�pno�ci tytu�u

        public List<Book> Books { get; set; }   //Lista wszystkich ksi��ek

        [BindProperty]
        public Book Book { get; set; }      //model ksi��ki powi�zany z polami input
        [BindProperty]
        public IFormFile PhotoFile { get; set; }
        public byte[] filebytes;

        public UpdateModel(AppDbContext context)
        {
            _context = context; //pobieramy kontekst
            TitleTaken = false; //zak�adamy �e tytu� jest dostepny
            Books = _context.Books.ToList();    //pobieramy list� ksi��ek
        }

        public void OnGet(int Id)
        {
            Book = _context.Books.Find(Id); //po dostarczeniu id przez "GET" pobieramy z kontekstu konkretny obiekt ksi��ki
            if (Book.Image != null)     //je�eli jest obrazek to przygotowujemy si� do wy�wietlenia go
            {   
                string path = Path.Combine(Environment.CurrentDirectory, @".\Data\", Book.Image);
                filebytes = System.IO.File.ReadAllBytes(path);
            }
        }

        public IActionResult OnPost(int Id)
        {
            string temp = Book.Title;

            foreach (Book b in Books)
            {
                if (b.Id != Id && b.Title.Equals(temp)) //sprawdzamy czy tytu� zaj�ty, z wyj�tkiem obiektu z tym samym Id -
                    TitleTaken = true;                          // poniewa� jest to obecnie wyswietlany obiekt
            }

            if (!TitleTaken && ModelState.IsValid)  //je�eli tytu� nie zaj�ty i nie ma innych b��d�w
            { 
                Book itemToUpdate = _context.Books.Find(Id);
                itemToUpdate.Title = temp;
                itemToUpdate.Author = Book.Author;
                itemToUpdate.Description = Book.Description;
                itemToUpdate.ISBN = Book.ISBN;
                itemToUpdate.Pages = Book.Pages;
                itemToUpdate.Read = Book.Read;

                if (PhotoFile != null)
                {
                    itemToUpdate.Image = Book.Title + ".bmp";
                    string filePath = Environment.CurrentDirectory + @".\Data\" + itemToUpdate.Image;   //Path.Combine psuje funkcje
                    FileStream file = new FileStream(filePath, FileMode.OpenOrCreate, FileAccess.Write);
                    PhotoFile.CopyTo(file);
                    file.Close();
                }
                _context.Update(itemToUpdate);
                _context.SaveChanges();
                return RedirectToPage("/Forms/View");   //Je�eli sukces to przekierowanie do widoku og�lnego
            }
            return Page();  //je�eli pora�ka to pozostajemy na tej samej stronie aby wy�wietli� b��dy
        }
    }
}
