import { createContext, useState } from "react";

export const ShopContext = createContext();

export const ShopProvider = ({children}) => {
    const [cart, setCart] = useState([]);
    console.log(cart)
    // Product Cart
    const addToCart = (product) => {

        const newItem = {...product, amount: 1}
        const cartItem = cart.find((item) =>  item.prod_id === product.prod_id )

        if(cartItem) {
            const newCart = [...cart].map((item) => {
                if (item.prod_id === product.prod_id) {
                    return {...item, amount: cartItem.amount +1}
                }
                else {
                    return item;
                }
            })
            setCart(newCart);
        }
        else { 
            
            setCart([...cart, newItem])
        }};
    const removeFromCart = (product) => {
        const cartItem = cart.find((item) => item.prod_id === product.prod_id);

        const newCart = [...cart].map((item) => {
            if (cartItem.amount > 1 && item.prod_id === product.prod_id) {
                return {...item, amount: cartItem.amount -1}
            }
            else {
                if (item.prod_id === product.prod_id)
                    return null;
                return {...item};
            }
        }).filter(Boolean);
        setCart(newCart)
    }
    
    return <ShopContext.Provider value={{cart, addToCart, removeFromCart}}>{children}</ShopContext.Provider>
}

