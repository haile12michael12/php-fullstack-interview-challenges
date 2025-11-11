import React, { useState, useEffect } from 'react';

const ServiceContainer = () => {
  const [services, setServices] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchServices = async () => {
      try {
        const response = await fetch('/api/services');
        if (!response.ok) {
          throw new Error('Failed to fetch services');
        }
        const data = await response.json();
        setServices(data.services || []);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchServices();
  }, []);

  if (loading) return <div>Loading services...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div className="service-container">
      <h2>Service Container</h2>
      <div className="services-list">
        <h3>Registered Services</h3>
        <ul>
          {services.map((service, index) => (
            <li key={index} className="service-item">
              <span className="service-name">{service}</span>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default ServiceContainer;