import React, { useState } from 'react'
import './App.css'
import QueryBuilder from './components/QueryBuilder'
import QueryResults from './components/QueryResults'
import QueryService from './services/queryService'

function App() {
  const [results, setResults] = useState(null)
  const [query, setQuery] = useState('')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)

  const handleQuerySubmit = async (queryData) => {
    setLoading(true)
    setError(null)
    setResults(null)
    
    try {
      const response = await QueryService.executeQuery(queryData)
      setResults(response.data || response.results || [])
      setQuery(response.query || '')
    } catch (err) {
      setError(err.message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="App">
      <header className="App-header">
        <h1>Query Builder</h1>
      </header>
      <main>
        <section>
          <QueryBuilder onQuerySubmit={handleQuerySubmit} />
          <QueryResults 
            results={results}
            query={query}
            loading={loading}
            error={error}
          />
        </section>
      </main>
    </div>
  )
}

export default App