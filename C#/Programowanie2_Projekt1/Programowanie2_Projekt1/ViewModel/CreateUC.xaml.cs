using System;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Media.Imaging;
using System.IO;
using Microsoft.Win32;
using System.Text.RegularExpressions;

namespace Programowanie2_Projekt1
{
    /// <summary>
    /// Logika interakcji dla klasy CreateUC.xaml
    /// Widok ten służy tworzeniu nowego obiektu książki w oparciu o wpisane przez użytkownika dane
    /// </summary>
    public partial class CreateUC : UserControl
    {
        private String? _photoPath;

        public CreateUC()
        {
            InitializeComponent();
        }

        /// <summary>
        /// Metody GotFocus służą wyczyszczeniu kontrolki TextBox przy pierwszym kliknięciu przez użytkownika
        /// </summary>
        private void txtTitle_GotFocus(object sender, RoutedEventArgs e)
        {
            txtTitle.Text = String.Empty;
            txtTitle.GotFocus -= txtTitle_GotFocus;
        }

        private void txtISBN_GotFocus(object sender, RoutedEventArgs e)
        {
            txtISBN.Text = String.Empty;
            txtISBN.GotFocus -= txtISBN_GotFocus;
        }

        private void txtPages_GotFocus(object sender, RoutedEventArgs e)
        {
            txtPages.Text = String.Empty;
            txtPages.GotFocus -= txtPages_GotFocus;
        }
        public void txtAuthor_GotFocus(object sender, RoutedEventArgs e)
        {
            txtAuthor.Text = String.Empty;
            txtAuthor.GotFocus -= txtAuthor_GotFocus;
        }

        private void txtDsc_GotFocus(object sender, RoutedEventArgs e)
        {
            txtDsc.Text = String.Empty;
            txtDsc.GotFocus -= txtDsc_GotFocus;
        }

        /// <summary>
        /// Metoda dzięki której użytkownik może dodać swój plik obrazu do książki.
        /// Dozwolone formaty obrazków to *.jpg, *.jpeg, *.jpe, *.jfif, *.png, *.bmp
        /// </summary>
        private void btnUpload_Click(object sender, RoutedEventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            ofd.Multiselect = false;
            ofd.Filter = "Image files (*.jpg, *.jpeg, *.jpe, *.jfif, *.png, *.bmp) | *.jpg; *.jpeg; *.jpe; *.jfif; *.png; *.bmp";
            if(ofd.ShowDialog() == true)
            {
                _photoPath = ofd.FileName;
                imgCover.Source = new BitmapImage(new Uri(_photoPath));
            }
        }

        /// <summary>
        /// Metoda wysyłająca do bazy danych model książki
        /// </summary>
        private void btnConfirm_Click(object sender, RoutedEventArgs e)
        {
            BookModel item = new BookModel();
            if (!String.IsNullOrEmpty(txtTitle.Text) && txtTitle.Text != "Tytuł")   //Tytuł nie może być pusty, oraz musi zostać zmieniony
            {
                Regex reg = new Regex("^[Xx\\d\\-]*$");         //Wyrażenie pozwalające na użycie tylko cyfr, liter x i X, oraz myślników
                String? ISBN = txtISBN.Text.Replace(" ", String.Empty); //pozbycie się spacji
                if(!reg.IsMatch(ISBN))  //Sprawdzamy czy ISBN spełnia warunki wyrażenia regularnego
                {
                    if (String.IsNullOrEmpty(ISBN) || String.Equals("ISBN", ISBN))  //Jeżeli ISBN jest ciągiem pustym lub niezmienionym to przypisujemy mu wartość null
                    {
                        ISBN = null;    
                    }
                    else
                    {
                        MessageBox.Show("Należy wpisać poprawny numer ISBN.\nDozwolone są tylko cyfry, \"-\" i litera \"X\" ", "Warning", MessageBoxButton.OK);
                        return;
                    }
                }

                String tempPagesTxt = txtPages.Text.Replace(" ", String.Empty);
                uint pages;
                if(!UInt32.TryParse(tempPagesTxt,out pages))    //Próbujemy sparsować zadany tekst do liczby naturalnej
                {
                    if (String.IsNullOrEmpty(tempPagesTxt) || String.Equals("Strony", tempPagesTxt)) //Jeżeli liczby nie wpisano to przypisujemy 0
                    {
                        pages = 0;
                    }
                    else
                    {
                        MessageBox.Show("Nie udało się przekonwertować stron na liczbę.\nUpewnij się że wpisałeś liczbę naturalną!", "Warning", MessageBoxButton.OK);
                        return;
                    }
                }

                //Przypisujemy modelowi książki dane z aplikacji
                item.Title = txtTitle.Text;
                item.ISBN = ISBN;
                item.Pages = pages;
                item.Read = chkRead.IsChecked == null ? false : (bool)chkRead.IsChecked;    //Kompilator nie lubi gdy nie sprawdze czy chkRead jest nullem (chociaż chyba nigdy nie jest)
                item.Author = txtAuthor.Text == "Autor" || String.IsNullOrEmpty(txtAuthor.Text) ? null : txtAuthor.Text;
                item.Description = txtDsc.Text == "Opis" || String.IsNullOrEmpty(txtDsc.Text) ? null : txtDsc.Text;
                if (!String.IsNullOrEmpty(_photoPath))  //Sprawdzamy czy dodano obrazek
                {
                    item.Image = item.Title + ".bmp";
                    string filePath = Path.GetFullPath(Path.Combine(Environment.CurrentDirectory, @".\Data\", item.Image));
                    File.Copy(_photoPath, filePath, true);
                    //Obrazek kopiujemy do folderu Data w projekcie aplikacji
                }

                try{    //Przekazujemy model książki do klasy komunikacji z bazą
                    SQLiteAccess.Create(item);
                    this.Content = new MainUC();
                }catch(Exception ex)
                {   //W przypadku problemów przechwytujemy wyjątek i wyświetlamy go użytkownikowi
                    String msg = String.Format("Wystąpił Błąd!\n{0}",ex.Message);
                    MessageBox.Show(msg, "Error", MessageBoxButton.OK);
                }
            }
            else
            {
                MessageBox.Show("Należy podać tytuł książki.\nTytułem książki nie może być null ani \"Tytuł\"", "Warning", MessageBoxButton.OK);
            }
        }

        private void btnBack_Click(object sender, RoutedEventArgs e)
        {
            this.Content = new MainUC();
        }

    }
}
