import { render, screen } from '@testing-library/react'
import { describe, it, expect } from 'vitest'
import App from './App'

describe('App', () => {
  it('renders the app header', () => {
    render(<App />)
    expect(screen.getByText('PHP Challenge - React Frontend')).toBeInTheDocument()
  })

  it('renders the users section', () => {
    render(<App />)
    expect(screen.getByText('Users')).toBeInTheDocument()
  })
})