import Carousel from 'react-bootstrap/Carousel';
import HeroBlock from './HeroBlock';
import './Carousels.css';

const data = [
  {
    id: 1,
    src: './images/hero-beer.jpg',
    alt: 'beer',
    title: 'Odkryj Rónorodność w Naszym Sklepie',
    text: 'Zapraszamy Cię do odkrycia szerokiego spektrum stylów, od oszałamiających IPA po głęboko aromatyczne stouty. Nasze piwa są nie tylko napojem, ale prawdziwym dziełem sztuki, które łączy tradycję z innowacją.',
  },
  {
    id: 2,
    src: './images/hero-bev.jpg',
    alt: 'drinks',
    title: 'Podróże Kieliszkiem',
    text: 'Przedstawiamy kolekcję alkoholi ze wszystkich zakątków świata, tworzącą zbiór unikalnych smaków i aromatów. Od wykwintnych win po wyjątkowe whisky, nasza oferta przeniesie Cię w podróż ku niezwykłym doznaniom zmysłowym. Każdy kieliszek to opowieść, każdy łyk to eksploracja kultury i tradycji. Odkryj z nami bogactwo globalnych trunków i podkreśl swoje wyjątkowe chwile przy kieliszku. ',
  },
  {
    id: 3,
    src: './images/hero-vodka.jpg',
    alt: 'vodka',
    title: 'Vodka Voyager',
    text: 'Witaj w krainie, gdzie każdy łyk wódki to podróż w nieznane! Nasza kolekcja wódek zabierze Cię w ekscytującą podróż po różnorodnych smakach, aromatach i kulturach. Od klasycznych, czystych wódek po zaskakujące kompozycje smakowe, stworzyliśmy miejsce, gdzie każda butelka jest jak tajemnicza mapa do odkrycia. Zanurz się w świecie Vodka Voyager, gdzie elegancja spotyka się z odwagą eksperymentowania.',
  },
];

const Carousels = () => {
  return (
    <Carousel className="hero" fade>
      {data.map((item) => (
        <Carousel.Item key={item.id} interval={7500}>
          <HeroBlock imageInfo={item} />
        </Carousel.Item>
      ))}
    </Carousel>
  );
};
export default Carousels;
