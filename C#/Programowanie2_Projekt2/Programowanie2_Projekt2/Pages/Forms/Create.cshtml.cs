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
        public bool TitleTaken { get; set; }    //flaga zajêtoœci tytu³u

        public List<Book> Books { get; set; }   //lista wszystkich ksi¹¿ek

        [BindProperty]
        public Book Book { get; set; }          //Model tworzonej ksi¹¿ki

        [BindProperty]
        public IFormFile PhotoFile { get; set; }   //plik wys³any przez u¿ytkownika

        public CreateModel(AppDbContext context)
        {
            _context = context;     //pobieramy kontekst
            TitleTaken = false;     //zak³adamy ¿e tytu³ jest "wolny"
            Books = _context.Books.ToList();    //pobieramy listê ksi¹¿ek z kontekstu
        }

        public void OnGet() { }

        public IActionResult OnPost()
        {
            string temp = Book.Title;

            foreach( Book b in Books)   //zweryfikowanie dostêpnoœci tytu³u
            {
                if (b.Title.Equals(temp))
                    TitleTaken = true;  //je¿eli jest ju¿ ksi¹¿ka o takim tytule, to ustaw flagê zajêtoœci
            }

            if (!TitleTaken && ModelState.IsValid)  //je¿eli tytu³ nie zajêty i pomyœlna walidacja pozosta³ych pól
            {
                if (PhotoFile != null)  //je¿eli wys³ano obrazek ok³adki
                {
                    Book.Image = Book.Title + ".bmp";   //tworzê nazwê obrazka
                    //Ok³adki przechowuje w podfolderze Data g³ównego folderu aplikacji
                    string filePath = Environment.CurrentDirectory + @".\Data\" + Book.Image;   //Path.Combine psuje t¹ funkcje
                    FileStream file = new FileStream(filePath, FileMode.OpenOrCreate, FileAccess.Write); //operacje na plikach
                    PhotoFile.CopyTo(file);
                    file.Close();
                }
                _context.Add(Book);        
                _context.SaveChanges();
                return RedirectToPage("/Forms/View");   //przejdŸ do przegl¹dania wszystkich ksi¹¿ek jeœli sukces
            }
            return Page();  //jeœli pora¿ka to pozostañ na tej samej stronie ¿eby wyœwietliæ komunikaty b³êdów
        }
    }
}
