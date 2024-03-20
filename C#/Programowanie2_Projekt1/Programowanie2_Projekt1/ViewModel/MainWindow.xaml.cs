using System.Windows;


namespace Programowanie2_Projekt1
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// MainWindow to w praktyce tylko pusty kontener. Aplikacja jest zbudowana w oparciu o UserControls
    /// "Główne okno" aplikacji jest opisane w MainUC.xaml.cs
    /// </summary>
    public partial class MainWindow : Window
    {
        public MainWindow()
        {
            InitializeComponent();
            this.ContentHolder.Content = new MainUC();
        }
    }
}
