using System;
using System.Collections.Generic;
using System.IO;
using System.Data;
using Microsoft.Data.Sqlite; //Microsoft SQLite plugin

namespace Programowanie2_Projekt1
{
    /// <summary>
    /// Klasa służąca komunikacji aplikacji z bazą danych
    /// </summary>
    class SQLiteAccess
    {
        //Składamy ścieżkę do pliku database.db z absolutnej ścieżki programu i 2 relatywnych ścieżek do nawigacji w strukturze folderów
        public static string dbFilePath = Path.GetFullPath(Path.Combine(Environment.CurrentDirectory, @".\Data\database.db")); 
        static string _ConnectionString = string.Format("Data Source = {0}", dbFilePath);

        /// <summary>
        /// Metoda tworząca bazę z pustą tabelą Books, której pola odpowiadają polom klasy BookModel
        /// </summary>
        /// <returns>true w przypadku pomyślnego utworzenia bazy danych</returns>
        public static bool CreateTableBooks()
        {
            bool isCreated = false;
            //Musimy ręcznie stworzyć folder \Data\, w przeciwnym wypadku nie chce działać (Samo SqliteConnection nie wystarcza)
            Directory.CreateDirectory(Path.GetFullPath(Path.Combine(Environment.CurrentDirectory, @".\Data\")));
            SqliteConnection dbConnection = new SqliteConnection(_ConnectionString);
            dbConnection.Open();

            if (dbConnection.State == ConnectionState.Open)
            {
                string dbQuerry = string.Format("CREATE TABLE IF NOT EXISTS Books(Id INTEGER PRIMARY KEY AUTOINCREMENT, ISBN TEXT, Title TEXT, " +
                                                "Description TEXT, Image TEXT, Pages INTEGER, Read BOOLEAN, Author TEXT)");
                SqliteCommand dbCommand = new SqliteCommand(dbQuerry, dbConnection);
                int result = dbCommand.ExecuteNonQuery();
                if(result == 0)
                {
                    isCreated = true;
                }
            }
            dbConnection.Close();
            return isCreated;
        }

        /// <summary>
        /// Metoda wczytująca wszystkie wpisy z tabeli Books do listy 
        /// </summary>
        /// <returns>Listę książek wczytanych z bazy danych. Może to być lista pusta</returns>
        public static List<BookModel> Read()
        {
            List<BookModel> bookList = new List<BookModel>();
            SqliteConnection dbConnection = new SqliteConnection(_ConnectionString);
            dbConnection.Open();
            
            if(dbConnection.State == ConnectionState.Open)
            {
                string dbQuerry = "SELECT * FROM Books";
                SqliteCommand dbCommand = new SqliteCommand(dbQuerry, dbConnection);

                SqliteDataReader dbDataReader = dbCommand.ExecuteReader();

                while(dbDataReader.Read())  //Wczytywanie kolejnych wpisów z bazy
                {
                    BookModel book = new BookModel() {
                        Id = Convert.ToUInt32(dbDataReader["Id"]),
                        ISBN = dbDataReader["ISBN"].ToString(),    
                        Title = dbDataReader["Title"].ToString(),
                        Description = dbDataReader["Description"].ToString(),
                        Image = dbDataReader["Image"].ToString(),
                        Pages = Convert.ToUInt32(dbDataReader["Pages"]),
                        Read = Convert.ToBoolean(dbDataReader["Read"]),
                        Author = dbDataReader["Author"].ToString(),
                    }; 
                    bookList.Add(book);
                }
                dbDataReader.Close();
            }
            dbConnection.Close();
            return bookList;
        }

        /// <summary>
        /// Metoda tworząca nowy wpis w bazie danych
        /// </summary>
        /// <param name="item">model książki</param>
        /// <returns>true w przypadku powodzenia operacji</returns>
        /// <exception cref="Exception">Wyrzuca wyjątek z informacją dla użytkownika w przypadku  powtórzenia się tytułu lub błedu w komunikacji</exception>
        public static bool Create(BookModel item)
        {
            bool isCreated = false;
            SqliteConnection dbConnection = new SqliteConnection(_ConnectionString);
            dbConnection.Open();
            if(dbConnection.State == ConnectionState.Open)
            {
                string dbQuerry = string.Format("SELECT COUNT(*) FROM Books WHERE Title = '{0}'", item.Title);  //unikalność tytułu
                SqliteCommand dbCommand = new SqliteCommand(dbQuerry, dbConnection);
                int result = Convert.ToInt32(dbCommand.ExecuteScalar());

                if (result==0)
                {
                    dbQuerry = string.Format("INSERT INTO BOOKS VALUES(null,'{0}','{1}','{2}','{3}','{4}','{5}','{6}')", 
                                        item.ISBN, item.Title, item.Description, item.Image, item.Pages, item.Read, item.Author);
                    dbCommand.CommandText = dbQuerry;
                    if (dbCommand.ExecuteNonQuery() == 1)
                    {
                        isCreated = true;
                    }
                    else
                    {
                        throw new Exception("Błąd podczas komunikacji z bazą przy próbie dodania książki do Bazy.");
                    }
                }
                else
                {
                    throw new Exception("Książka o podanym tytule już istnieje. Spróbuj podać inny tytuł");
                }
            }
            dbConnection.Close();
            return isCreated;
        }

        /// <summary>
        /// Metoda służąca usuwaniu wybranej książki z Bazy. Porównania z bazą dokonuje na podstawie wewnętrznego Id
        /// </summary>
        /// <param name="item">Model książki</param>
        /// <returns>true w przypadku udanego usunięcia</returns>
        public static bool Delete(BookModel item)
        {
            bool isDeleted = false;
            SqliteConnection dbConnection = new SqliteConnection(_ConnectionString);
            dbConnection.Open();
            if (dbConnection.State == ConnectionState.Open)
            {
                string dbQuerry = string.Format("SELECT COUNT(*) FROM Books WHERE Id = '{0}'", item.Id);
                SqliteCommand dbCommand = new SqliteCommand(dbQuerry, dbConnection);

                int result = Convert.ToInt32(dbCommand.ExecuteScalar());

                if (result == 1)
                {
                    dbQuerry = string.Format("DELETE FROM Books WHERE Id = '{0}'", item.Id);
                    dbCommand.CommandText = dbQuerry;
                    if (dbCommand.ExecuteNonQuery() == 1)
                    {
                        isDeleted = true;
                    }
                }
            }
            dbConnection.Close();
            return isDeleted;
        }

        /// <summary>
        /// Metoda aktualizująca istniejący w bazie wpis w oparciu o zadany model książki
        /// </summary>
        /// <param name="item">model książki</param>
        /// <returns>true w przypadku powodzenia</returns>
        /// <exception cref="Exception">Błędy komunikacji z bazą, 
        /// oraz przypadki w których nie znaleziono książki o danym id lub gdy zadany tytuł znajduje się już w bazie</exception>
        public static bool Update(BookModel item)
        {
            bool isUpdated = false;
            SqliteConnection dbConnection = new SqliteConnection(_ConnectionString);
            dbConnection.Open();
            if(dbConnection.State == ConnectionState.Open)
            {
                string dbQuerry = string.Format("SELECT COUNT(*) FROM Books WHERE Id = '{0}'", item.Id);    //Wyszukujemy ile jest książek o danym id
                SqliteCommand dbCommand = new SqliteCommand(dbQuerry, dbConnection);
                int result = Convert.ToInt32(dbCommand.ExecuteScalar());
                if (result == 1)    //Jeżeli tylko jedna, przechodzimy dalej. W przeciwnym przypadku przerywanmy działanie
                {
                    dbCommand.CommandText = string.Format("SELECT * FROM Books WHERE Id = '{0}'", item.Id);
                    SqliteDataReader dbDataReader = dbCommand.ExecuteReader();
                    
                    dbDataReader.Read();    //Wczytujemy niezmodyfikowany model z bazy w celu porównania
                    BookModel oldItem = new BookModel()
                    {
                            Id = Convert.ToUInt32(dbDataReader["Id"]),
                            ISBN = dbDataReader["ISBN"].ToString(),
                            Title = dbDataReader["Title"].ToString(),
                            Description = dbDataReader["Description"].ToString(),
                            Image = dbDataReader["Image"].ToString(),
                            Pages = Convert.ToUInt32(dbDataReader["Pages"]),
                            Read = Convert.ToBoolean(dbDataReader["Read"]),
                            Author = dbDataReader["Author"].ToString(),
                    };
                    
                    dbDataReader.Close();

                    //Jeżeli zmieniono tytuł w porównaniu z wcześniejszym modelem, to musimy przeszukać bazę w celu zapewnienia uniklaności tytułu
                    if (!String.Equals(oldItem.Title,item.Title)) { 
                        dbQuerry = string.Format("SELECT COUNT(*) FROM Books WHERE Title = '{0}'", item.Title);
                        dbCommand.CommandText = dbQuerry;
                        result = Convert.ToInt32(dbCommand.ExecuteScalar());
                        if (result > 0)
                        {   //Jeżeli tytuł się powtarza to przerywamy działanie
                            throw new Exception("Książka o podanym tytule już istnieje. Wpisz inny tytuł");
                        }
                        dbQuerry = String.Format("UPDATE Books SET Title = '{0}' WHERE Id = '{1}'", item.Title, item.Id);
                        dbCommand.CommandText = dbQuerry;
                        if (dbCommand.ExecuteNonQuery() != 1)
                        {
                            throw new Exception("Błąd bazy danych przy aktualizowaniu tytułu książki.");
                        }
                    }   //We wszystkich przypadkach nadpisujemy pozostałe pola obiektu, za wyjątkiem Id
                    dbQuerry = String.Format("UPDATE Books SET ISBN = '{0}', Description = '{1}', Image = '{2}', Pages = '{3}', Read = '{4}', Author = '{5}' WHERE Id = '{6}'",
                         item.ISBN, item.Description, item.Image, item.Pages, item.Read, item.Author, item.Id);
                    dbCommand.CommandText = dbQuerry;
                    if (dbCommand.ExecuteNonQuery() == 1)
                    {
                        isUpdated = true;
                    }
                    else
                    {
                        throw new Exception("Błąd bazy danych przy aktualizowaniu informacji o książce.");
                    }
                }
                else
                {
                    throw new Exception("Nie znaleziono książki w bazie danych");
                }
            }
            dbConnection.Close();
            return isUpdated;
        }
    }
}
