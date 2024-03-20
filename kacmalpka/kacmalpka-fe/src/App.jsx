import RootLayout from './Layout/RootLayout';
import Home from './Pages/Home';
import NotFound from './Pages/NotFound';
import Produkty, { productLoader } from './Pages/Produkty'

import {
  Route,
  createRoutesFromElements,
  createBrowserRouter,
  RouterProvider,
} from 'react-router-dom';
import Sklepy from './Pages/Sklepy';
import ShopProvider from './Context/ShopContext'
import ShoppingCart from './Pages/ShoppingCart';

const kategorie = ["wodka", "whisky", "likiery-nalewki", "rumy", "wina", "szampany", "inne"];
const router = createBrowserRouter(
  
  createRoutesFromElements(

    <Route path="/" element={<RootLayout />}>
      <Route index element={<Home />} />
      <Route path="sklepy" element={<Sklepy />}></Route>
      {/* Produkty */}

        {kategorie.map(kategoria => (
          <Route key={kategoria} path={kategoria} element={<Produkty />} loader={productLoader} />
          ))}
      <Route path="cart" element={<ShoppingCart />}></Route>
      <Route path="*" element={<NotFound />} />
    </Route>
    
  )
);

function App() {
  return <RouterProvider router={router} />;
}

export default App;
