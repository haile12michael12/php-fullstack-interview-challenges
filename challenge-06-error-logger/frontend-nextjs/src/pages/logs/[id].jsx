import React, { useState, useEffect } from 'react';
import { useRouter } from 'next/router';
import LogViewer from '../../components/dashboard/LogViewer.jsx';

const LogDetailPage = () => {
  const router = useRouter();
  const { id } = router.query;
  const [log, setLog] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (id) {
      fetchLog();
    }
  }, [id]);

  const fetchLog = async () => {
    setLoading(true);
    try {
      const response = await fetch(`/api/logs/${id}`);
      const data = await response.json();
      setLog(data.log);
    } catch (error) {
      console.error('Failed to fetch log:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <div>Loading...</div>;
  }

  if (!log) {
    return <div>Log not found</div>;
  }

  return (
    <div className="log-detail-page">
      <LogViewer log={log} />
    </div>
  );
};

export default LogDetailPage;