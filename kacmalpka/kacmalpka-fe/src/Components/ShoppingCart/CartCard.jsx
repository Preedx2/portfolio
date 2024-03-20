import { useContext } from 'react';
import './CartCard.css'
import { ShopContext } from '../../Context/ShopContext';

const CartCard = ({product}) => {
  const {addToCart, removeFromCart} = useContext(ShopContext);
  const img_path = './images/prods/'+product.prod_image

  return (
      <div className="cart-card">
        <div className='cart-description'>
          <img src={img_path} />
          <div className='cart-details'>
            <h3 className='title'>{product.prod_name_short}</h3>
            <p>{product.prod_description}</p>
          </div>
        </div>
          <div className='cart-info'>
            <p className='text'>{(product.prices.price_price/100).toFixed(2)} ZŁ</p>
            <div className='cart-btns'>
              <button className='left' onClick={() => removeFromCart(product)}>-</button>
              <input value={product.amount} />
              <button className='right' onClick={() => addToCart(product)}>+</button>
            </div>
          </div>
    </div>
  )
}

export default CartCard
