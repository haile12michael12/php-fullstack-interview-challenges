import React from 'react';
import { render, screen } from '@testing-library/react';
import Preview from '../components/Preview.jsx';

// Mock the ThemeContext
jest.mock('../context/ThemeContext.jsx', () => ({
  ThemeContext: {
    Consumer: ({ children }) => children({ theme: 'light' }),
  },
}));

describe('Preview', () => {
  test('renders preview content', () => {
    render(<Preview markdownContent="# Hello World" />);
    
    const previewContent = screen.getByText('Hello World');
    expect(previewContent).toBeInTheDocument();
  });

  test('renders default content when no markdown is provided', () => {
    render(<Preview />);
    
    const previewContent = screen.getByText('Hello World');
    expect(previewContent).toBeInTheDocument();
  });
});