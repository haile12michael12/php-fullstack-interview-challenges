import React, { useState } from 'react';

const ServiceInspector = () => {
  const [selectedService, setSelectedService] = useState('');
  const [inspectionResult, setInspectionResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const services = [
    'App\\Container\\Contracts\\ContainerInterface',
    'App\\Container\\Core\\Container',
    'App\\Container\\Contracts\\ResolverInterface',
    'App\\Container\\Core\\ReflectionResolver',
    'App\\Container\\Contracts\\FactoryInterface',
    'App\\Container\\Core\\ServiceFactory',
    'App\\Services\\Interfaces\\LoggerInterface',
    'App\\Services\\Implementations\\FileLogger',
    'App\\Services\\Interfaces\\MailerInterface',
    'App\\Services\\Implementations\\SmtpMailer',
  ];

  const inspectService = async () => {
    if (!selectedService) return;

    setLoading(true);
    try {
      // In a real application, this would call an API endpoint
      // that uses reflection to inspect the service
      const mockResult = {
        class: selectedService,
        methods: [
          { name: '__construct', parameters: ['param1', 'param2'] },
          { name: 'getMethod', parameters: ['name'] },
          { name: 'getProperty', parameters: ['name'] },
        ],
        properties: [
          { name: 'property1', visibility: 'private' },
          { name: 'property2', visibility: 'protected' },
        ],
        interfaces: ['Interface1', 'Interface2'],
        parentClass: 'ParentClass',
      };

      setInspectionResult(mockResult);
    } catch (error) {
      console.error('Inspection failed:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="service-inspector">
      <h2>Service Inspector</h2>
      
      <div className="inspector-controls">
        <select 
          value={selectedService} 
          onChange={(e) => setSelectedService(e.target.value)}
        >
          <option value="">Select a service</option>
          {services.map((service, index) => (
            <option key={index} value={service}>
              {service}
            </option>
          ))}
        </select>
        <button 
          onClick={inspectService} 
          disabled={!selectedService || loading}
        >
          {loading ? 'Inspecting...' : 'Inspect'}
        </button>
      </div>

      {inspectionResult && (
        <div className="inspection-result">
          <h3>Inspection Results for {inspectionResult.class}</h3>
          
          <div className="result-section">
            <h4>Methods</h4>
            <ul>
              {inspectionResult.methods.map((method, index) => (
                <li key={index}>
                  <strong>{method.name}</strong>
                  <span> ({method.parameters.join(', ')})</span>
                </li>
              ))}
            </ul>
          </div>

          <div className="result-section">
            <h4>Properties</h4>
            <ul>
              {inspectionResult.properties.map((property, index) => (
                <li key={index}>
                  <span className={`visibility ${property.visibility}`}>
                    {property.visibility}
                  </span>
                  <span> {property.name}</span>
                </li>
              ))}
            </ul>
          </div>

          <div className="result-section">
            <h4>Interfaces</h4>
            <ul>
              {inspectionResult.interfaces.map((interface, index) => (
                <li key={index}>{interface}</li>
              ))}
            </ul>
          </div>

          <div className="result-section">
            <h4>Parent Class</h4>
            <p>{inspectionResult.parentClass}</p>
          </div>
        </div>
      )}
    </div>
  );
};

export default ServiceInspector;