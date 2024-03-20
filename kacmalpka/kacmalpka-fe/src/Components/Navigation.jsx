import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';
import Searchbar from './Searchbar';
import ShoppingCart from './ShoppingCart';
import Login from './Login';
import { useEffect, useState } from 'react';
import MobileMenu from './MobileMenu';
import { NavLink } from 'react-bootstrap';
import { motion } from 'framer-motion';

function Navigation() {
  const [matches, setMatches] = useState(
    !window.matchMedia('(min-width: 768px)').matches
  );
  // Only when mobile-mode - show mobile menu-links
  const [isClicked, setClicked] = useState(false);
  const changeClicked = () => {
    setClicked(!isClicked);
  };

  const [scroll, setScroll] = useState(false);

  const handleScroll = () => {
    if (window.scrollY > 20) {
      console.log(false);
      setScroll(true);
    } else {
      setScroll(false);
    }
  };

  useEffect(() => {
    window.addEventListener('scroll', handleScroll);

    return () => {
      window.removeEventListener('scroll', handleScroll);
    };
  });

  const nav_links = [
    <NavLink to="#" key={1}>
      O nas
    </NavLink>,
    <NavLink to="#" key={2}>
      Sklepy
    </NavLink>,
    <NavLink to="#" key={3}>
      Produkty
    </NavLink>,
  ];

  return (
    <Navbar
      expand="lg"
      className={`${
        scroll ? 'nav-scroll' : ''
      } bg-nav text-white font-nav sticky-top`}
    >
      <Container>
        <img src="./images/logo.png" alt="logo" id="logo" />

        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="me-auto">
            <Nav.Link href="#home">O nas</Nav.Link>
            <Nav.Link href="#">Sklepy</Nav.Link>
            <NavDropdown title="Produkty" id="basic-nav-dropdown">
              <NavDropdown.Item href="#action/3.1">Wódka</NavDropdown.Item>
              <NavDropdown.Item href="#action/3.2">Whisky</NavDropdown.Item>
              <NavDropdown.Item href="#action/3.3">Likiery</NavDropdown.Item>
            </NavDropdown>
          </Nav>
        </Navbar.Collapse>
        <div className="button-section">
          <Searchbar />
          <ShoppingCart />
          <Login />
          {matches ? <MobileMenu onClick={changeClicked} /> : ''}
        </div>
      </Container>
      {isClicked ? (
        <div className="mobile-menu">{nav_links.map((item) => item)}</div>
      ) : (
        ''
      )}
    </Navbar>
  );
}

export default Navigation;
