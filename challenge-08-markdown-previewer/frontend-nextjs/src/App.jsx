import React from 'react';
import { ThemeProvider } from './context/ThemeContext.jsx';
import Editor from './components/Editor.jsx';
import Preview from './components/Preview.jsx';
import Toolbar from './components/Toolbar.jsx';
import SplitPane from './components/SplitPane.jsx';

function App() {
  return (
    <ThemeProvider>
      <div className="app">
        <Toolbar />
        <SplitPane>
          <Editor />
          <Preview />
        </SplitPane>
      </div>
    </ThemeProvider>
  );
}

export default App;