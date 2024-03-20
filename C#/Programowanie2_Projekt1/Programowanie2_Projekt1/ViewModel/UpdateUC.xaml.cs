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
    /// Logika interakcji dla klasy UpdateUC.xaml
    /// Widok służy modyfikowaniu już istniejącego wpisu
    /// </summary>
    public partial class UpdateUC : UserControl
    {
        BookModel _book;
        string? _photoPath;

        /// <summary>
        /// Konstruktor widoku.
        /// Na podstawie przekazanego obiektu wypełnia aplikację danymi modyfikowanej książki
        /// </summary>
        /// <param name="book">modyfikowana książka</param>
        public UpdateUC(BookModel book)
        {
            InitializeComponent();
            _book = book;
            txtTitle.Text = _book.Title;    
            txtISBN.Text = _book.ISBN;      
            txtPages.Text = (_book.Pages).ToString();
            chkRead.IsChecked = _book.Read;
            txtAuthor.Text = _book.Author;
            txtDsc.Text = _book.Description;

            if (!String.IsNullOrEmpty(_book.Image))
            {
                String _path = Path.GetFullPath(Path.Combine(Environment.CurrentDirectory, @".\Data\", _book.Image));
                BitmapImage picture = new BitmapImage();
                picture.BeginInit();
                picture.CacheOption = BitmapCacheOption.OnLoad;
                picture.UriSource = new Uri(_path);
                picture.EndInit();

                imgCover.Source = picture;
            }
            else
            {
                imgCover.Source = null;
            }
        }

        /// <summary>
        /// Umożliwienie zmiany powiązanego z książką obrazka
        /// Dopuszczalne fromaty: *.jpg, *.jpeg, *.jpe, *.jfif, *.png, *.bmp
        /// </summary>
        private void btnUpload_Click(object sender, RoutedEventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            ofd.Multiselect = false;
            ofd.Filter = "Image files (*.jpg, *.jpeg, *.jpe, *.jfif, *.png, *.bmp) | *.jpg; *.jpeg; *.jpe; *.jfif; *.png; *.bmp";
            if (ofd.ShowDialog() == true)
            {
                _photoPath = ofd.FileName;
                imgCover.Source = new BitmapImage(new Uri(_photoPath));
            }
        }

        /// <summary>
        /// Metoda przekazująca wczytane z aplikacji dane i oparty na nich model książki do bazy danych
        /// Logika jest niemal identyczna z metodą btn_Confirm_Click z klasy CreateUC.xaml.cs, gdzie jest dokładnie opisana.
        /// </summary>
        private void btnConfirm_Click(object sender, RoutedEventArgs e)
        {
            if (txtTitle.Text != "Tytuł" && !String.IsNullOrEmpty(txtTitle.Text))   
            {
                Regex reg = new Regex("^[Xx\\d\\-]*$");
                String? ISBN = txtISBN.Text.Replace(" ", String.Empty);
                if (!reg.IsMatch(ISBN))
                {
                    if (String.IsNullOrEmpty(ISBN) || String.Equals("ISBN", ISBN))
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
                if (!UInt32.TryParse(tempPagesTxt, out pages))
                {
                    if (String.IsNullOrEmpty(tempPagesTxt) || String.Equals("Strony", tempPagesTxt))
                    {
                        pages = 0;
                    }
                    else
                    {
                        MessageBox.Show("Nie udało się przekonwertować stron na liczbę.\nUpewnij się że wpisałeś liczbę naturalną!", "Warning", MessageBoxButton.OK);
                        return;
                    }
                }

                _book.Title = txtTitle.Text;
                _book.ISBN = ISBN;
                _book.Pages = pages;
                _book.Read = chkRead.IsChecked == null ? false : (bool)chkRead.IsChecked;    //Kompilator nie lubi gdy nie sprawdze czy chkRead jest nullem
                _book.Author = txtAuthor.Text == "Autor" || String.IsNullOrEmpty(txtAuthor.Text) ? null : txtAuthor.Text;
                _book.Description = txtDsc.Text == "Opis" || String.IsNullOrEmpty(txtDsc.Text) ? null : txtDsc.Text;
                if (!String.IsNullOrEmpty(_photoPath))
                {
                    _book.Image = _book.Title + ".bmp";
                    string filePath = Path.GetFullPath(Path.Combine(Environment.CurrentDirectory, @".\Data\", _book.Image));
                    File.Copy(_photoPath, filePath, true);
                }

                try
                {
                    SQLiteAccess.Update(_book);
                    this.Content = new MainUC();
                }
                catch(Exception ex)
                {
                    String msg = String.Format("Wystąpił Błąd!\n{0}", ex.Message);
                    MessageBox.Show(msg, "Error", MessageBoxButton.OK);
                }
            }
            else
            {
                MessageBox.Show("Tytuł książki nie może być pusty, ani brzmieć \"Tytuł\"", "Error", MessageBoxButton.OK);
            }
        }

        private void btnBack_Click(object sender, RoutedEventArgs e)
        {
            this.Content = new MainUC();
        }
    }
}
