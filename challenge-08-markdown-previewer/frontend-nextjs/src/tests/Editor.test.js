import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import Editor from '../components/Editor.jsx';

// Mock the ThemeContext
jest.mock('../context/ThemeContext.jsx', () => ({
  ThemeContext: {
    Consumer: ({ children }) => children({ theme: 'light' }),
  },
}));

describe('Editor', () => {
  test('renders textarea with initial content', () => {
    render(<Editor />);
    
    const textarea = screen.getByRole('textbox');
    expect(textarea).toBeInTheDocument();
    expect(textarea).toHaveValue('# Hello World\n\nThis is a **markdown** previewer.');
  });

  test('allows user to type in the editor', () => {
    render(<Editor />);
    
    const textarea = screen.getByRole('textbox');
    fireEvent.change(textarea, { target: { value: '# New Content' } });
    
    expect(textarea).toHaveValue('# New Content');
  });
});