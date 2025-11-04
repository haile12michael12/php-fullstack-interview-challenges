import React, { useState, useEffect } from 'react'
import './App.css'

function App() {
  const [users, setUsers] = useState([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)

  useEffect(() => {
    fetchUsers()
  }, [])

  const fetchUsers = async () => {
    setLoading(true)
    setError(null)
    try {
      // In a real application, this would be your API endpoint
      const response = await fetch('/api/users')
      if (!response.ok) {
        throw new Error('Failed to fetch users')
      }
      const data = await response.json()
      setUsers(data.data || [])
    } catch (err) {
      setError(err.message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="App">
      <header className="App-header">
        <h1>PHP Challenge - React Frontend</h1>
      </header>
      <main>
        <section>
          <h2>Users</h2>
          {loading && <p>Loading...</p>}
          {error && <p className="error">Error: {error}</p>}
          <ul>
            {users.map(user => (
              <li key={user.id}>
                {user.name} ({user.email})
              </li>
            ))}
          </ul>
          <button onClick={fetchUsers} disabled={loading}>
            Refresh Users
          </button>
        </section>
      </main>
    </div>
  )
}

export default App