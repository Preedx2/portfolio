import { NavLink } from 'react-router-dom';
import './Dropdown.css';
import DropdownLinks from './DropdownLinks';

const Dropdown = () => {
  return (
    <div className="dropdown-container">
      <DropdownLinks />
    </div>
  );
};
export default Dropdown;
