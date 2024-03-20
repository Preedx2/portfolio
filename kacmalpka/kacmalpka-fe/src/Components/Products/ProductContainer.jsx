import "./ProductContainer.css"
import ProductCard from "./ProductCard"

const ProductContainer = ({products}) => {
  console.log(products)
  return (
    <div className='container'>
      {products.map((product) => 
        <ProductCard key={product.prod_id} product={product}/>
      )}
    </div>
  )
}

export default ProductContainer
