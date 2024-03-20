import pandas as pd
import warnings
import time
from selenium import webdriver
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys

FIREFOX_BIN_PATH = r'C:\Program Files\Mozilla Firefox\firefox.exe'


def library_scrap(search_term: str = "python") -> None:
    warnings.simplefilter("ignore")  # to ignore profile warning from firefox
    prev_window = None
    try:
        print("Initializing...")
        options = Options()
        options.binary_location = FIREFOX_BIN_PATH
        driver = webdriver.Firefox('.', options=options)
        driver.get("https://bg.po.edu.pl/")

        print("Searching...")
        search = driver.find_element(By.ID, value="simple-search-phrase")
        search.send_keys(search_term, Keys.ENTER)
        # WebDriverWait(driver, timeout=10)
        time.sleep(5)

        prev_window = driver.current_window_handle
        driver.switch_to.window(driver.window_handles[-1])

        print("Extracting data...")
        raw_titles = driver.find_elements(By.CLASS_NAME, value="desc-o-mb-title")
        raw_authors = driver.find_elements(By.CLASS_NAME, value="desc-o-b-rest")

        text_titles = [title.text for title in raw_titles]
        text_authors = [authors.text[2:] for authors in raw_authors]

        data = pd.DataFrame(list(zip(text_titles, text_authors)), columns=["Title", "Authors"])
        data.to_excel('Python_Books.xlsx', index=False)
        print("Finished")

    finally:
        driver.close()
        if prev_window is not None:
            driver.switch_to.window(prev_window)
            driver.close()


if __name__ == '__main__':
    library_scrap()
