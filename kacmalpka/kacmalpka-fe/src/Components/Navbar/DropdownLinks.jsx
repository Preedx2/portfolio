import { NavLink } from 'react-router-dom';
import './DropdownLinks.css';

const DropdownLinks = () => {
  return (
    <>
      <div className="dropdown-links-container">
        <NavLink to="/wodka">Wódka</NavLink>
        <NavLink to="/whisky">Whisky</NavLink>
        <NavLink to="/likiery-nalewki">Likiery/Nalewki</NavLink>
        <NavLink to="/rumy">Rum</NavLink>
        <NavLink to="/wina">Wino</NavLink>
        <NavLink to="/szampany">Szampan</NavLink>
        <NavLink to="/inne">Inne</NavLink>
      </div>
    </>
  );
};
export default DropdownLinks;
