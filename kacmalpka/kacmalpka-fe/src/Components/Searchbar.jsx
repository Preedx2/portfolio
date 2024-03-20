import { LiaSearchSolid } from 'react-icons/lia';
import './Searchbar.css';

const Searchbar = () => {
  return (
    <form className="flex-center">
      <input className="search" type="text" placeholder="Szukaj..." />
      <button className="box flex-center">
        <LiaSearchSolid />
      </button>
    </form>
  );
};
export default Searchbar;

// Kola
// W srodku ikona
// JaK click to sie rozwija pasek
// i robi dipls
