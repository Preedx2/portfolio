import { useLoaderData } from "react-router";

import ProductContainer from "../Components/Products/ProductContainer"

const Produkty = () => {
  const products = useLoaderData();
  return (
    <ProductContainer products={products}/>
  );
};

export const productLoader = async (request) => {
    // Kategoria produktu wyciagnieta ze strony ktora wysyla request do bazy danych
    const url = request.request.url;
    const category = url.split('/').pop();
    
    // producttags_set -> tag_descr ma być zgodny z category -----> http://localhost:3000/wodka === tag_descr = wodka
    const res = await fetch('http://localhost:8000/api/products/?format=json');
    if (!res.ok) {
      throw Error(`Nie można pobrać danych`);
    }

    const allProducts = await res.json();

    const products = allProducts.map(product => {
      if (product.producttags_set[0].tag_id.tag_descr === category) {
          return product;
      }
      return null;
    }).filter(product => product !== null);

    return products;
  };

export default Produkty;
