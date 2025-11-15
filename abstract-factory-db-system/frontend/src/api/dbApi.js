import axiosClient from './axiosClient';

const dbApi = {
  // Authentication
  login: (username, password) => {
    return axiosClient.post('/auth/login', { username, password });
  },

  // Connection management
  testConnection: (dbType, dbConfig) => {
    return axiosClient.post('/connection/test', { db_type: dbType, db_config: dbConfig });
  },

  // Query execution
  executeQuery: (dbType, dbConfig, sql, params = []) => {
    return axiosClient.post('/query/execute', { db_type: dbType, db_config: dbConfig, sql, params });
  }
};

export default dbApi;