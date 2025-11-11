const API_BASE_URL = 'http://localhost:8080/api';

export const processData = async (type) => {
  try {
    const response = await fetch(`${API_BASE_URL}/process/${type}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error processing data:', error);
    throw error;
  }
};

export const generateFibonacci = async (limit) => {
  try {
    const response = await fetch(`${API_BASE_URL}/fibonacci/${limit}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error generating Fibonacci:', error);
    throw error;
  }
};

export const generatePrimes = async (limit) => {
  try {
    const response = await fetch(`${API_BASE_URL}/primes/${limit}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error generating primes:', error);
    throw error;
  }
};

export const streamData = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/stream`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
  } catch (error) {
    console.error('Error streaming data:', error);
    throw error;
  }
};