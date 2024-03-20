using System;
using System.Collections.Generic;
using System.Linq;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Media.Imaging;
using System.IO;

namespace Programowanie2_Projekt1
{
    /// <summary>
    /// Logika interakcji dla klasy MainUC.xaml
    /// Główny widok aplikacji na którym wyświetlamy dane z bazy, 
    /// oraz możemy nawigować do innych widoków służących do operacji na bazie danych.
    /// </summary>
    public partial class MainUC : UserControl
    {
        List<BookModel> bookList = new List<BookModel>();

        public MainUC()
        {
            InitializeComponent();
            RefreshListView();
        }

        public void RefreshListView()
        {
            bookList = SQLiteAccess.Read();
            cmbList.ItemsSource = bookList.Select(n => n.Title);
        }

        /// <summary>
        /// Przyciski btnNext i btnPrev służą do przemieszczania się po liście książek.
        /// btnPrev przechodzi po liście do mniejszych indeksów
        /// </summary>
        private void btnPrev_Click(object sender, RoutedEventArgs e)
        {
            int currentIndex = cmbList.SelectedIndex;
            if(currentIndex > 0)
            {
                cmbList.SelectedIndex = currentIndex - 1;
            }
        }

        /// <summary>
        /// Przyciski btnNext i btnPrev służą do przemieszczania się po liście książek.
        /// btnNext przechodzi po liście do większych indeksów
        /// </summary>
        private void btnNext_Click(object sender, RoutedEventArgs e)
        {
            int currentIndex = cmbList.SelectedIndex;
            if (currentIndex < cmbList.Items.Count)
            {
                if (currentIndex == -1)
                    RefreshListView();
                cmbList.SelectedIndex = currentIndex + 1;
            }
        }

        /// <summary>
        /// Przejście do widoku tworzenia nowej książki
        /// </summary>
        private void btnCreate_Click(object sender, RoutedEventArgs e)
        {
            this.Content = new CreateUC();
            cmbList.SelectedIndex = -1;
        }

        /// <summary>
        /// Przejście do widoku edytowania istniejącej książki
        /// Edytowana jest książka aktualnie wyświetlona na cmbList
        /// </summary>
        private void btnEdit_Click(object sender, RoutedEventArgs e)
        {
            if (cmbList.SelectedItem != null)
            {
                int current = cmbList.SelectedIndex;
                this.Content = new UpdateUC(bookList[cmbList.SelectedIndex]);
                cmbList.SelectedIndex = -1;
            }
            else
            {
                MessageBox.Show("Wybierz książkę do zaktualizowania", "Warning", MessageBoxButton.OK);
            }
        }

        /// <summary>
        /// Usuwanie książki z bazy
        /// Usuwana jest książka aktualnie wyświetlona na cmbList
        /// </summary>
        private void btnDelete_Click(object sender, RoutedEventArgs e)
        {
            if(cmbList.SelectedItem != null)
            {
                if (MessageBox.Show("Jesteś Pewien?", "Confirmation", MessageBoxButton.YesNo) == MessageBoxResult.Yes)
                {
                    String? Image = bookList[cmbList.SelectedIndex].Image;
                    if (SQLiteAccess.Delete(bookList[cmbList.SelectedIndex]))
                    {
                        if (!String.IsNullOrEmpty(Image))
                        {
                            String _path = Path.GetFullPath(Path.Combine(Environment.CurrentDirectory, @".\Data\", Image));
                            File.Delete(_path);
                        }
                        btnPrev_Click(sender, e);
                        RefreshListView();
                    }
                    else
                    {
                        MessageBox.Show("Wystąpił błąd", "Error", MessageBoxButton.OK);
                    }
                }
            }
            else
            {
                MessageBox.Show("Wybierz książkę do usunięcia", "Warning", MessageBoxButton.OK);
            }
        }

        private void btnClose_Click(object sender, RoutedEventArgs e)
        {
            Window.GetWindow(this).Close();
        }

        private void cmbList_DropDownOpened(object sender, EventArgs e)
        {
            RefreshListView();
        }

        /// <summary>
        /// Zmienianie danych wyświetalncyh w widoku w zależności od wybranego z listy obiektu
        /// </summary>
        private void cmbList_SelectionChanged(object sender, EventArgs e)
        {
            RefreshListView();
            if (cmbList.SelectedIndex != -1)
            {
                String? Title = bookList[cmbList.SelectedIndex].Title;
                String? ISBN = bookList[cmbList.SelectedIndex].ISBN;
                uint Pages = bookList[cmbList.SelectedIndex].Pages;
                bool Read = bookList[cmbList.SelectedIndex].Read;
                String? Author = bookList[cmbList.SelectedIndex].Author;
                String? Dsc = bookList[cmbList.SelectedIndex].Description;
                String? Image= bookList[cmbList.SelectedIndex].Image;

                txtTitle.Text = Title;
                txtISBN.Text = String.IsNullOrEmpty(ISBN) ? "Brak Danych" : ISBN;
                txtPages.Text = Pages == 0 ? "Brak" : String.Format("{0} Stron", Pages);
                txtRead.Text = Read ? "Przeczytano" : "Nieprzeczytano";
                txtAuthor.Text = String.IsNullOrEmpty(Author) ? "Autor" : Author;
                txtDsc.Text = String.IsNullOrEmpty(Dsc) ? "Brak Opisu" : Dsc;

                if(!String.IsNullOrEmpty(Image))
                {
                    String _path = Path.GetFullPath(Path.Combine(Environment.CurrentDirectory, @".\Data\", Image));
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
            else
            {
                txtTitle.Text = "Tytuł";
                txtISBN.Text = "ISBN";
                txtPages.Text = "Strony";
                txtRead.Text = "Przeczytano?";
                txtAuthor.Text = "Autor";
                txtDsc.Text = "Opis";
                imgCover.Source = null;
            }
        }
    }
}
