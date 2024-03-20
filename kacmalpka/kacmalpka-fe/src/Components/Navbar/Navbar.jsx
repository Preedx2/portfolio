import { NavLink } from 'react-router-dom';
import './Navbar.css';
import Dropdown from './Dropdown';
import Searchbar from '../Searchbar';
import ShoppingCart from '../ShoppingCart';
import Login from '../Login';
import MobileMenu from '../MobileMenu';

import { useState, useEffect } from 'react';
import DropdownLinks from './DropdownLinks';
import { IoIosArrowDown, IoIosArrowUp } from 'react-icons/io';

const Navbar = () => {
  const [isDrop, setDrop] = useState(false);

  const [scroll, setScroll] = useState(false);
  const [matches, setMatches] = useState(
    !window.matchMedia('(min-width: 768px)').matches
  );
  const [isClicked, setClicked] = useState(false);
  useEffect(() => {
    window.addEventListener('scroll', handleScroll);

    return () => {
      window.removeEventListener('scroll', handleScroll);
    };
  });

  const showDropdown = () => {
    setDrop(!isDrop);
  };
  const handleScroll = () => {
    if (window.scrollY > 20) {
      setScroll(true);
    } else {
      setScroll(false);
    }
  };
  const onClick = () => {
    setClicked(!isClicked);
  };

  return (
    <nav className={`${scroll ? 'nav-scroll' : ''} `}>
      <div className="nav-container">
        <div className="logo">
          <img src="./images/logo.png" alt="logo" id="Logo" />
        </div>
        {matches ? (
          ''
        ) : (
          <div className="links-container">
            <NavLink to="/">O nas</NavLink>
            <div className="underline-block"></div>
            <NavLink to="/sklepy">Sklepy</NavLink>
            <div className="dropdown-box">
              <button className="dropdown-btn" onClick={showDropdown}>
                Produkty {!isDrop ? <IoIosArrowDown /> : <IoIosArrowUp />}
              </button>
              {isDrop ? <Dropdown /> : ''}
            </div>
          </div>
        )}
        <div className="button-section">
          <Searchbar />
          <ShoppingCart />
          <Login />
          {matches ? (
            <MobileMenu onClick={onClick} isClicked={isClicked} />
          ) : (
            ''
          )}
        </div>

        {/* Only on mobile  */}
        {isClicked ? (
          <div className="links-container">
            <NavLink to="/">O nas</NavLink>
            <div className="underline-block"></div>
            <NavLink to="/sklepy">Sklepy</NavLink>
            <button className="dropdown-btn" onClick={showDropdown}>
              Produkty <IoIosArrowDown />
            </button>
            <div className={`dropdown-box ${!isDrop ? 'hide' : ''}`}>
              <DropdownLinks />
            </div>
          </div>
        ) : (
          ''
        )}
      </div>
    </nav>
  );
};
export default Navbar;
