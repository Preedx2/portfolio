import "./CartContainer.css"
import { useContext } from "react"
import { ShopContext } from "../../Context/ShopContext"
import CartCard from "./CartCard"
import CartSummary from "./CartSummary"

const CartContainer = () => {
const {cart} = useContext(ShopContext);
let elements_in_cart = 0;
cart.forEach((item) => elements_in_cart+=item.amount);

  return (
    <div>
        <div className="cart-head">
            <h2>Koszyk {"("}<span className="amount">{elements_in_cart}{")"}</span>:</h2>
        </div>
        <div className="cart-main">
            <div className="cart-cards-container">
                {cart.map((item) => <CartCard key={item.id} product={item}/>)}
            </div>
            <div className="wrapper">
                <div className="fixed">
                    <CartSummary/>
                </div>
            </div>
        </div>
    </div>
  )
}

export default CartContainer
