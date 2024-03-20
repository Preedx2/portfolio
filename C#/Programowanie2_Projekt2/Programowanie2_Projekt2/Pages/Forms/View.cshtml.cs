using System;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;

using Programowanie2_Projekt2.Models;
using System.Collections.Generic;
using System.Linq;

namespace Programowanie2_Projekt2.Pages.Forms
{
    public class ViewModel : PageModel
    {
        private readonly AppDbContext _context; //kontekst
        [BindProperty]
        public List<Book> bookList { get; set; }    //lista ksi��ek

        public ViewModel(AppDbContext context)
        {
            _context = context; //pobieramy kontekst
        }

        public void OnGet()
        {
            bookList = _context.Books.ToList(); //pobieramy z kontekstu list� ksi��ek
        }

    }
}
