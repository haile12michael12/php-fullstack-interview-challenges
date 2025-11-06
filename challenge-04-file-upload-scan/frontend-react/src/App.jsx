import React, { useState, useEffect } from 'react';
import FileUpload from './components/FileUpload';
import FileList from './components/FileList';
import fileService from './services/fileService';

function App() {
  const [files, setFiles] = useState([]);
  const [loading, setLoading] = useState(false);

  const fetchFiles = async () => {
    setLoading(true);
    try {
      const response = await fileService.listFiles();
      setFiles(response.files || []);
    } catch (error) {
      console.error('Failed to fetch files:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleFileUpload = async (file) => {
    try {
      const response = await fileService.uploadFile(file);
      // Refresh file list
      fetchFiles();
      return response;
    } catch (error) {
      throw error;
    }
  };

  useEffect(() => {
    fetchFiles();
  }, []);

  return (
    <div className="App">
      <header>
        <h1>File Upload with Security Scanning</h1>
      </header>
      
      <main>
        <FileUpload onUpload={handleFileUpload} />
        
        <div className="file-list-container">
          <h2>Uploaded Files</h2>
          {loading ? (
            <p>Loading files...</p>
          ) : (
            <FileList files={files} />
          )}
        </div>
      </main>
    </div>
  );
}

export default App;