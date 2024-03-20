using System;
using System.Collections.Generic;
using System.Configuration;
using System.Data;
using System.Linq;
using System.Threading.Tasks;
using System.Windows;
using System.IO;

namespace Programowanie2_Projekt1
{
    /// <summary>
    /// Interaction logic for App.xaml
    /// </summary>
    public partial class App : Application
    {
        private void Application_Startup(object sender, StartupEventArgs e)
        {
            if (!File.Exists(SQLiteAccess.dbFilePath))
            {
                if (SQLiteAccess.CreateTableBooks())
                {
                    MessageBox.Show("Utworzono tabelę", "Info", MessageBoxButton.OK);
                }
                
            }
        }
    }
}
