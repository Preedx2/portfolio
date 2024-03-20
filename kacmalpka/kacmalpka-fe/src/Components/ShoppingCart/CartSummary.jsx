import { useContext } from 'react'
import './CardSummary.css'
import { ShopContext } from '../../Context/ShopContext'

const CartSummary = () => {
  const {cart} = useContext(ShopContext);
  let price = 0;
  cart.forEach((item) => price += (item.amount*item.prices.price_price));
  //const podatek = price * 0.23;   //chyba nie musimy naliczac podatku - w polsce ceny musza byc juz sumaryczne, to Hamerykance sa tacy zjebani ze naliczaja VAT dopiero przy kasie
  return (
    <div className="cart-summary">
        <p>Łączna kwota: {(price/100).toFixed(2)} ZŁ</p>
        <div className='underline'></div>
        <div className='btns-cart-summary'>
            <button className='done'>Przejdź do dostawy</button>
        </div>
    </div>
  )
}

export default CartSummary

//stara cena z podatkiem jakby jednak byla potrzebna   <p>Łączna kwota: {((price+podatek)/100).toFixed(2)} ZŁ</p>
