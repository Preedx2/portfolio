import "./ProductCard.css"
import { ShopContext } from "../../Context/ShopContext"
import { useContext } from "react"

const ProductCard = ({product}) => {
  const {addToCart} = useContext(ShopContext);
  const img_path = './images/prods/'+product.prod_image
  return (
    <div className='product-card'>
      <h3 className="product-title">{product.prod_name_short}</h3>
      <img src={img_path} alt="" className="product-image"/>
      <p>{product.prod_description}</p>
      <p className="product-price">{(product.prices.price_price/100).toFixed(2)} ZŁ</p>
      <button className='add-to-cart-button' onClick={() => addToCart(product)}>Dodaj do koszyka</button>

    </div>
  )
}

export default ProductCard
