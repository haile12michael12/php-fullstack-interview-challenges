import React from 'react';

const Table = ({ children, className = '', ...props }) => {
  const baseClasses = 'table';
  const classes = `${baseClasses} ${className}`;

  return (
    <table className={classes} {...props}>
      {children}
    </table>
  );
};

const TableHead = ({ children, className = '', ...props }) => {
  const baseClasses = 'table-head';
  const classes = `${baseClasses} ${className}`;

  return (
    <thead className={classes} {...props}>
      {children}
    </thead>
  );
};

const TableBody = ({ children, className = '', ...props }) => {
  const baseClasses = 'table-body';
  const classes = `${baseClasses} ${className}`;

  return (
    <tbody className={classes} {...props}>
      {children}
    </tbody>
  );
};

const TableRow = ({ children, className = '', ...props }) => {
  const baseClasses = 'table-row';
  const classes = `${baseClasses} ${className}`;

  return (
    <tr className={classes} {...props}>
      {children}
    </tr>
  );
};

const TableCell = ({ children, className = '', header = false, ...props }) => {
  const BaseComponent = header ? 'th' : 'td';
  const baseClasses = header ? 'table-header-cell' : 'table-cell';
  const classes = `${baseClasses} ${className}`;

  return (
    <BaseComponent className={classes} {...props}>
      {children}
    </BaseComponent>
  );
};

Table.Head = TableHead;
Table.Body = TableBody;
Table.Row = TableRow;
Table.Cell = TableCell;

export default Table;