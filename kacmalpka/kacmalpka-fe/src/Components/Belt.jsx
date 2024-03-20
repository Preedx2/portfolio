import './Belt.css';
import { useState } from 'react';

function generateBelts(isMobile = false) {
  let list = [];
  let amount = isMobile ? 3 : 7;
  for (let i = 1; i < amount; i++) {
    list.push(`belt-${i}.jpg`);
  }
  return list;
}

const Belt = () => {
  const [matches, setMatches] = useState(
    !window.matchMedia('(min-width: 768px)').matches
  );

  const images = generateBelts(matches);
  return (
    <div className="belt">
      {images.map((item, idx) => (
        <img key={idx} src={`./images/${item}`}></img>
      ))}
    </div>
  );
};
export default Belt;
