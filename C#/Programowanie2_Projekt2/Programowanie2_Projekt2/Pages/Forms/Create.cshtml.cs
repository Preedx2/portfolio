using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;
using System;
using Microsoft.AspNetCore.Http;
using Programowanie2_Projekt2.Models;
using System.IO;
using System.Collections.Generic;
using System.Linq;

namespace Programowanie2_Projekt2.Pages.Forms
{
    public class CreateModel : PageModel
    {
        private readonly AppDbContext _context; //kontekst

        [BindProperty]
        public bool TitleTaken { get; set; }    //flaga zaj�to�ci tytu�u

        public List<Book> Books { get; set; }   //lista wszystkich ksi��ek

        [BindProperty]
        public Book Book { get; set; }          //Model tworzonej ksi��ki

        [BindProperty]
        public IFormFile PhotoFile { get; set; }   //plik wys�any przez u�ytkownika

        public CreateModel(AppDbContext context)
        {
            _context = context;     //pobieramy kontekst
            TitleTaken = false;     //zak�adamy �e tytu� jest "wolny"
            Books = _context.Books.ToList();    //pobieramy list� ksi��ek z kontekstu
        }

        public void OnGet() { }

        public IActionResult OnPost()
        {
            string temp = Book.Title;

            foreach( Book b in Books)   //zweryfikowanie dost�pno�ci tytu�u
            {
                if (b.Title.Equals(temp))
                    TitleTaken = true;  //je�eli jest ju� ksi��ka o takim tytule, to ustaw flag� zaj�to�ci
            }

            if (!TitleTaken && ModelState.IsValid)  //je�eli tytu� nie zaj�ty i pomy�lna walidacja pozosta�ych p�l
            {
                if (PhotoFile != null)  //je�eli wys�ano obrazek ok�adki
                {
                    Book.Image = Book.Title + ".bmp";   //tworz� nazw� obrazka
                    //Ok�adki przechowuje w podfolderze Data g��wnego folderu aplikacji
                    string filePath = Environment.CurrentDirectory + @".\Data\" + Book.Image;   //Path.Combine psuje t� funkcje
                    FileStream file = new FileStream(filePath, FileMode.OpenOrCreate, FileAccess.Write); //operacje na plikach
                    PhotoFile.CopyTo(file);
                    file.Close();
                }
                _context.Add(Book);        
                _context.SaveChanges();
                return RedirectToPage("/Forms/View");   //przejd� do przegl�dania wszystkich ksi��ek je�li sukces
            }
            return Page();  //je�li pora�ka to pozosta� na tej samej stronie �eby wy�wietli� komunikaty b��d�w
        }
    }
}
