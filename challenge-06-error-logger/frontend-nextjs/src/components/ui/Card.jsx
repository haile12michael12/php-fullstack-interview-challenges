import React from 'react';

const Card = ({ children, className = '', onClick, ...props }) => {
  const baseClasses = 'card';
  const classes = `${baseClasses} ${className}`;

  if (onClick) {
    return (
      <div className={classes} onClick={onClick} {...props}>
        {children}
      </div>
    );
  }

  return (
    <div className={classes} {...props}>
      {children}
    </div>
  );
};

export default Card;