import './Footer.css';
import { FaStreetView, FaPhone, FaHouse } from 'react-icons/fa6';
import { GiMonkey } from 'react-icons/gi';

const Footer = () => {
  return (
    <footer>
      <div className="two-columns">
        <div className="footer-about">
          <GiMonkey />
          <p>
            KacMalpka!
            <br />
            Dołącz do naszej przygody <br />
            smakowej w <br />
            towarzystwie uroczej <br /> małpki Kaca!
          </p>
        </div>
        <div className="footer-contact">
          <FaHouse />
          <p>Opole 45-040</p>
          <FaStreetView />
          <p>Plac Kopernika 16</p>
          <FaPhone />
          <p>+48 234 567 779</p>
        </div>
      </div>
      <p className="footer-summary">&copy; 2023 Copyright: KacMałpka</p>
    </footer>
  );
};
export default Footer;
