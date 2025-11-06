import React from 'react';
import ImageEditor from '../components/ImageEditor.jsx';

const Home = () => {
  return (
    <div className="home-page">
      <header>
        <h1>Image Processing Application</h1>
        <p>Upload and edit your images with our powerful tools</p>
      </header>
      
      <main>
        <ImageEditor />
      </main>
      
      <footer>
        <p>&copy; 2025 Image Processing Application</p>
      </footer>
    </div>
  );
};

export default Home;