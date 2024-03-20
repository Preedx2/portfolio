using System;

namespace Programowanie2_Projekt1
{
    public class BookModel
    {
        public uint Id;             //Id zostanie przypisane przez bazę danych
        public String? ISBN;        //ISBN może składać się z cyfr, myślników i liter X
        public String? Title;       //Trytuł. nie można utworzyć dwóch książek o tym samym tytule
        public String? Description; //Opis książki
        public String? Image;       //Nazwa obrazka przypisanego do książki
        public uint Pages;          //Liczba Stron
        public bool Read;           //Czy książka została już przeczytana. Domyślna wartość to fałsz, nie dopuszczamy nulla
        public String? Author;      //Autor książki
    }
}
