import './AlertWindow.css';
import { IoAlertCircle } from 'react-icons/io5';

const AlertWindow = (props) => {
  return (
    <div className="alert-window">
      <div className="alert-icon">
        <IoAlertCircle />
      </div>
      {props.children}
    </div>
  );
};
export default AlertWindow;
