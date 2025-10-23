import React, { useState, useEffect } from 'react'
import './App.css'

function App() {
  const [user, setUser] = useState(null)
  const [token, setToken] = useState(null)
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: ''
  })
  const [message, setMessage] = useState('')

  // Check if user is already logged in
  useEffect(() => {
    const storedToken = localStorage.getItem('token')
    const storedUser = localStorage.getItem('user')
    
    if (storedToken && storedUser) {
      setToken(storedToken)
      setUser(JSON.parse(storedUser))
    }
  }, [])

  const handleInputChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    })
  }

  const handleRegister = async (e) => {
    e.preventDefault()
    
    try {
      // In a real app, you would make an API call here
      // For this challenge, we'll simulate the response
      const mockResponse = {
        message: 'User registered successfully',
        user: {
          id: '1',
          name: formData.name,
          email: formData.email
        },
        access_token: 'mock_access_token',
        refresh_token: 'mock_refresh_token'
      }
      
      setToken(mockResponse.access_token)
      setUser(mockResponse.user)
      localStorage.setItem('token', mockResponse.access_token)
      localStorage.setItem('user', JSON.stringify(mockResponse.user))
      setMessage('Registration successful!')
    } catch (error) {
      setMessage('Registration failed: ' + error.message)
    }
  }

  const handleLogin = async (e) => {
    e.preventDefault()
    
    try {
      // In a real app, you would make an API call here
      // For this challenge, we'll simulate the response
      const mockResponse = {
        message: 'Login successful',
        user: {
          id: '1',
          name: formData.name,
          email: formData.email
        },
        access_token: 'mock_access_token',
        refresh_token: 'mock_refresh_token'
      }
      
      setToken(mockResponse.access_token)
      setUser(mockResponse.user)
      localStorage.setItem('token', mockResponse.access_token)
      localStorage.setItem('user', JSON.stringify(mockResponse.user))
      setMessage('Login successful!')
    } catch (error) {
      setMessage('Login failed: ' + error.message)
    }
  }

  const handleLogout = () => {
    setToken(null)
    setUser(null)
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    setMessage('Logged out successfully!')
  }

  return (
    <div className="App">
      <header className="App-header">
        <h1>Challenge 12: REST API with JWT Authentication</h1>
        
        {message && (
          <div className="message">
            {message}
          </div>
        )}
        
        {user ? (
          <div className="user-info">
            <h2>Welcome, {user.name}!</h2>
            <p>Email: {user.email}</p>
            <button onClick={handleLogout}>Logout</button>
          </div>
        ) : (
          <div className="auth-forms">
            <div className="form-section">
              <h2>Register</h2>
              <form onSubmit={handleRegister}>
                <input
                  type="text"
                  name="name"
                  placeholder="Name"
                  value={formData.name}
                  onChange={handleInputChange}
                  required
                />
                <input
                  type="email"
                  name="email"
                  placeholder="Email"
                  value={formData.email}
                  onChange={handleInputChange}
                  required
                />
                <input
                  type="password"
                  name="password"
                  placeholder="Password"
                  value={formData.password}
                  onChange={handleInputChange}
                  required
                />
                <button type="submit">Register</button>
              </form>
            </div>
            
            <div className="form-section">
              <h2>Login</h2>
              <form onSubmit={handleLogin}>
                <input
                  type="email"
                  name="email"
                  placeholder="Email"
                  value={formData.email}
                  onChange={handleInputChange}
                  required
                />
                <input
                  type="password"
                  name="password"
                  placeholder="Password"
                  value={formData.password}
                  onChange={handleInputChange}
                  required
                />
                <button type="submit">Login</button>
              </form>
            </div>
          </div>
        )}
      </header>
    </div>
  )
}

export default App