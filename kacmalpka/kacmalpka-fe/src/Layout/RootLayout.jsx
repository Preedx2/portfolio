import Footer from '../Components/Footer';
import Navbar from '../Components/Navbar/Navbar';
import { Outlet } from 'react-router';

const RootLayout = () => {
  return (
    <>
      <div className="root-layout">
        <header>
          <Navbar />
        </header>
        <main>
          <Outlet />
        </main>
        <Footer />
      </div>
    </>
  );
};
export default RootLayout;
