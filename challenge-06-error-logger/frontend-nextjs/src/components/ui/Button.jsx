import React from 'react';

const Button = ({ children, onClick, variant = 'primary', disabled = false, className = '', ...props }) => {
  const baseClasses = 'btn';
  const variantClasses = `btn-${variant}`;
  const disabledClasses = disabled ? 'btn-disabled' : '';
  const classes = `${baseClasses} ${variantClasses} ${disabledClasses} ${className}`;

  return (
    <button 
      className={classes} 
      onClick={onClick} 
      disabled={disabled}
      {...props}
    >
      {children}
    </button>
  );
};

export default Button;