import { HiMenuAlt3 } from 'react-icons/hi';
import './MobileMenu.css';
import { IoCloseSharp } from 'react-icons/io5';

const MobileMenu = ({ onClick, isClicked }) => {
  return (
    <button className="flex-center menu" onClick={onClick}>
      {!isClicked ? <HiMenuAlt3 /> : <IoCloseSharp />}
    </button>
  );
};

export default MobileMenu;
