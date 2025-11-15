import React from 'react';
import DatabaseSelector from '../components/DatabaseSelector';
import QueryExecutor from '../components/QueryExecutor';
import ConnectionStatusCard from '../components/ConnectionStatusCard';

const Dashboard = () => {
  return (
    <div className="min-h-screen bg-gray-100">
      <header className="bg-white shadow">
        <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <h1 className="text-3xl font-bold text-gray-900">Abstract Factory DB System</h1>
        </div>
      </header>
      <main>
        <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
          <div className="px-4 py-6 sm:px-0">
            <ConnectionStatusCard />
            <DatabaseSelector />
            <QueryExecutor />
          </div>
        </div>
      </main>
    </div>
  );
};

export default Dashboard;