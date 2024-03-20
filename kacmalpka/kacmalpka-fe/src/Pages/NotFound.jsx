import { Link } from 'react-router-dom';
import AlertWindow from '../Components/AlertWindow';

const NotFound = () => {
  return (
    <AlertWindow>
      <h2>Błąd 404: Strona Nie Znaleziona</h2>
      <p>
        Ups! Wygląda na to, że zeszliśmy trochę z kursu. <br />
        Strona, której szukasz, niestety nie istnieje lub została przeniesiona
        gdzieś indziej. Możliwe, że wprowadzony adres URL jest niepoprawny lub
        strona została usunięta. Jeśli uważasz, że to jest nasza wina, prosimy o
        kontakt z nami, abyśmy mogli naprawić ten problem. Przepraszamy za
        wszelkie niedogodności! Dziękujemy za zrozumienie.
      </p>

      <h3>Co możesz zrobić?</h3>

      <p>
        {' '}
        Sprawdź poprawność adresu URL. Upewnij się, że nie ma literówek i
        sprawdź, czy używasz właściwego formatu. Wróć na stronę główną i spróbuj
        nawigacji od nowa. Skorzystaj z naszej wyszukiwarki, aby szybko odnaleźć
        to, czego szukasz.
      </p>

      <h6>
        Przejdź do <Link path="/">Głównej</Link>
      </h6>
    </AlertWindow>
  );
};
export default NotFound;
