import './HeroBlock.css';
const HeroBlock = ({ imageInfo }) => {
  return (
    <div className="hero">
      <div className="block">
        <div className="text">
          <h1>{imageInfo.title}</h1>
          <p>{imageInfo.text}</p>
        </div>
        <button className="btn-hero">Więcej...</button>
      </div>
      <div className="hero-img">
        <img src={imageInfo.src} alt={imageInfo.alt} />
      </div>
    </div>
  );
};
export default HeroBlock;
