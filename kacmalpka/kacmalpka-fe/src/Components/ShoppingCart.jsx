import { useContext } from 'react';
import { FaCartShopping } from 'react-icons/fa6';
import { NavLink } from 'react-router-dom';
import { ShopContext } from '../Context/ShopContext';
import "./ShoppingCart.css";

const ShoppingCart = () => {
  const {cart} = useContext(ShopContext);
  let elements_in_cart = 0;
  cart.forEach((item) => elements_in_cart+=item.amount);

  return (
    <NavLink to="cart">
      <div className="flex-center box relative">
        <FaCartShopping style={{ color: 'black' }} />
        {elements_in_cart === 0 ? '' : <div className='cart-items'>{elements_in_cart}</div>}
      </div>
    </NavLink>
  );
};
export default ShoppingCart;
