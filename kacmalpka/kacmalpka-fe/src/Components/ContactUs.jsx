import './ContactUs.css';
import { Button } from 'react-bootstrap';

const ContactUs = () => {
  return (
    <div id="contact">
      <h1>Napisz do nas!</h1>
      <form id="contact-form">
        <div className="contact-container">
          <div className="contact-person">
            <input type="text" placeholder="Imie" />
            <input type="email" placeholder="E-mail" />
          </div>
          <div>
            <textarea
              cols="30"
              rows="3"
              placeholder="Twoja wiadomość.."
            ></textarea>
          </div>
        </div>
        <Button className="">Wyślij</Button>
      </form>
    </div>
  );
};
export default ContactUs;
