using Programowanie2_Projekt2.Resources;
using System.ComponentModel.DataAnnotations;

namespace Programowanie2_Projekt2.Models
{
    /// <summary>
    /// Model książki powiązany z bazą danych 
    /// </summary>
    public class Book
    {
        public int Id { get; set; }             //Id - klucz glowny

        [Display(Name = "ISBN")]
        [RegularExpression("^[Xx\\d\\-]*$", //przyjmuje litery 'x' i 'X', cyfry i myślniki
            ErrorMessageResourceType = typeof(ValidationMessages),
            ErrorMessageResourceName = "ISBN")]  //Wiadomości błędów zostały zlokalizowane w Resources/ValidationMessages.resx
        [StringLength(maximumLength: 17, MinimumLength = 10,
            ErrorMessageResourceType = typeof(ValidationMessages),
            ErrorMessageResourceName = "ISBN")]
        public string ISBN { get; set; }        //ISBN może składać się z cyfr, myślników i liter X

        [Required(
            ErrorMessageResourceType = typeof(ValidationMessages),
            ErrorMessageResourceName = "Required")]
        [StringLength(maximumLength:150, MinimumLength = 3, 
            ErrorMessageResourceType = typeof(ValidationMessages),
            ErrorMessageResourceName = "Title_Length")]
        [Display(Name = "Tytuł")]
        public string Title { get; set; }       //Tytuł. Musi mieć od 3 do 150 znaków, pole wymagane
        
        [Display(Name = "Opis")]
        public string Description { get; set; } //Opis książki
        
        [Display(Name = "Obrazek")]
        public string Image { get; set; }       //Nazwa obrazka przypisanego do książki znajdującego się w folderze Data
        
        [Display(Name = "Strony")]
        [Range(0, int.MaxValue, 
            ErrorMessageResourceType = typeof(ValidationMessages),
            ErrorMessageResourceName = "Nmbr_Pages")]
        public uint? Pages { get; set; }          //Liczba Stron. Musi być dodatnim integerem. Dopuszczamy Null
        
        [Display(Name = "Przeczytano")]
        public bool Read { get; set; }           //Czy książka została już przeczytana. Domyślna wartość to fałsz, nie dopuszczamy nulla
        
        [Display(Name = "Autor")]
        public string Author { get; set; }      //Autor książki
    }
}
