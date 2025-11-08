import React, { createContext, useContext, useReducer } from 'react';

// Create the context
const ErrorContext = createContext();

// Action types
const ERROR_ACTION_TYPES = {
  ADD_ERROR: 'ADD_ERROR',
  REMOVE_ERROR: 'REMOVE_ERROR',
  CLEAR_ERRORS: 'CLEAR_ERRORS',
  SET_GLOBAL_ERROR: 'SET_GLOBAL_ERROR',
  CLEAR_GLOBAL_ERROR: 'CLEAR_GLOBAL_ERROR',
};

// Initial state
const initialState = {
  errors: [],
  globalError: null,
};

// Reducer function
const errorReducer = (state, action) => {
  switch (action.type) {
    case ERROR_ACTION_TYPES.ADD_ERROR:
      return {
        ...state,
        errors: [...state.errors, action.payload],
      };
    
    case ERROR_ACTION_TYPES.REMOVE_ERROR:
      return {
        ...state,
        errors: state.errors.filter(error => error.id !== action.payload),
      };
    
    case ERROR_ACTION_TYPES.CLEAR_ERRORS:
      return {
        ...state,
        errors: [],
      };
    
    case ERROR_ACTION_TYPES.SET_GLOBAL_ERROR:
      return {
        ...state,
        globalError: action.payload,
      };
    
    case ERROR_ACTION_TYPES.CLEAR_GLOBAL_ERROR:
      return {
        ...state,
        globalError: null,
      };
    
    default:
      return state;
  }
};

// Provider component
export const ErrorProvider = ({ children }) => {
  const [state, dispatch] = useReducer(errorReducer, initialState);

  // Actions
  const addError = (error) => {
    const errorWithId = {
      ...error,
      id: Date.now() + Math.random(),
      timestamp: new Date().toISOString(),
    };
    
    dispatch({
      type: ERROR_ACTION_TYPES.ADD_ERROR,
      payload: errorWithId,
    });
    
    // Auto-remove error after 5 seconds
    setTimeout(() => {
      removeError(errorWithId.id);
    }, 5000);
  };

  const removeError = (errorId) => {
    dispatch({
      type: ERROR_ACTION_TYPES.REMOVE_ERROR,
      payload: errorId,
    });
  };

  const clearErrors = () => {
    dispatch({
      type: ERROR_ACTION_TYPES.CLEAR_ERRORS,
    });
  };

  const setGlobalError = (error) => {
    dispatch({
      type: ERROR_ACTION_TYPES.SET_GLOBAL_ERROR,
      payload: error,
    });
  };

  const clearGlobalError = () => {
    dispatch({
      type: ERROR_ACTION_TYPES.CLEAR_GLOBAL_ERROR,
    });
  };

  const value = {
    ...state,
    addError,
    removeError,
    clearErrors,
    setGlobalError,
    clearGlobalError,
  };

  return (
    <ErrorContext.Provider value={value}>
      {children}
    </ErrorContext.Provider>
  );
};

// Custom hook to use the error context
export const useErrorContext = () => {
  const context = useContext(ErrorContext);
  if (!context) {
    throw new Error('useErrorContext must be used within an ErrorProvider');
  }
  return context;
};

export default ErrorContext;