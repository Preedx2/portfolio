import './InfoBar.css';
import { TbTruckDelivery } from 'react-icons/tb';
import { MdOutlineShoppingCart, MdOutlineSpeed } from 'react-icons/md';

const InfoBar = () => {
  return (
    <div className="info-container">
      <div className="info">
        <TbTruckDelivery className="icon-size" />
        <h3>Darmowa dostawa</h3>
        <p>Już od 400zł.</p>
      </div>
      <div className="info">
        <MdOutlineShoppingCart className="icon-size" />
        <h3>Polski sklep</h3>
        <p>100% polski sklep.</p>
      </div>
      <div className="info">
        <MdOutlineSpeed className="icon-size" />

        <h3>Ekspresowa realizacja</h3>
        <p>95% zamówień realizujemy w 24h.</p>
      </div>
    </div>
  );
};
export default InfoBar;
