import { create } from 'zustand';

const useConnectionStore = create((set, get) => ({
  // Connection state
  connections: {},
  activeConnection: null,
  
  // User authentication
  isAuthenticated: false,
  user: null,
  
  // Query results
  queryResults: [],
  isQueryLoading: false,
  queryError: null,
  
  // Add a new connection
  addConnection: (id, connection) => set((state) => ({
    connections: {
      ...state.connections,
      [id]: connection
    }
  })),
  
  // Set active connection
  setActiveConnection: (connectionId) => set({ activeConnection: connectionId }),
  
  // Remove a connection
  removeConnection: (id) => set((state) => {
    const newConnections = { ...state.connections };
    delete newConnections[id];
    
    // If we're removing the active connection, clear it
    const activeConnection = state.activeConnection === id ? null : state.activeConnection;
    
    return {
      connections: newConnections,
      activeConnection
    };
  }),
  
  // Set authentication status
  setAuthenticated: (isAuthenticated, user = null) => set({ isAuthenticated, user }),
  
  // Add query result
  addQueryResult: (result) => set((state) => ({
    queryResults: [...state.queryResults, result]
  })),
  
  // Clear query results
  clearQueryResults: () => set({ queryResults: [] }),
  
  // Set query loading state
  setQueryLoading: (isLoading) => set({ isQueryLoading: isLoading }),
  
  // Set query error
  setQueryError: (error) => set({ queryError: error })
}));

export default useConnectionStore;