import React from 'react';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  TimeScale
} from 'chart.js';
import { Line } from 'react-chartjs-2';
import 'chartjs-adapter-date-fns';

// Register Chart.js components
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  TimeScale
);

const MetricsChart = ({ data = [], chartType = 'default' }) => {
  // Prepare chart data
  const chartData = {
    datasets: [
      {
        label: getLabelForChartType(chartType),
        data: data.filter(d => d !== null && d !== undefined),
        borderColor: getColorForChartType(chartType),
        backgroundColor: getColorWithOpacity(chartType),
        tension: 0.1,
        pointRadius: 3,
        pointHoverRadius: 5
      }
    ]
  };

  // Chart options
  const options = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      x: {
        type: 'time',
        time: {
          unit: 'second',
          displayFormats: {
            second: 'HH:mm:ss'
          }
        },
        title: {
          display: true,
          text: 'Time'
        }
      },
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: getUnitForChartType(chartType)
        }
      }
    },
    plugins: {
      legend: {
        display: true
      },
      tooltip: {
        mode: 'index',
        intersect: false
      }
    },
    animation: {
      duration: 0 // Disable animation for real-time updates
    }
  };

  return (
    <div className="metrics-chart">
      <Line data={chartData} options={options} />
    </div>
  );
};

// Helper functions
function getLabelForChartType(chartType) {
  switch (chartType) {
    case 'cpu': return 'CPU Usage (%)';
    case 'memory': return 'Memory Usage (%)';
    case 'network': return 'Network Traffic (B/s)';
    case 'connections': return 'Active Connections';
    default: return 'Metric Value';
  }
}

function getColorForChartType(chartType) {
  switch (chartType) {
    case 'cpu': return 'rgba(255, 99, 132, 1)';
    case 'memory': return 'rgba(54, 162, 235, 1)';
    case 'network': return 'rgba(75, 192, 192, 1)';
    case 'connections': return 'rgba(153, 102, 255, 1)';
    default: return 'rgba(255, 159, 64, 1)';
  }
}

function getColorWithOpacity(chartType) {
  const color = getColorForChartType(chartType);
  return color.replace('1)', '0.2)');
}

function getUnitForChartType(chartType) {
  switch (chartType) {
    case 'cpu': return 'CPU Usage (%)';
    case 'memory': return 'Memory Usage (%)';
    case 'network': return 'Bytes per second';
    case 'connections': return 'Connections';
    default: return 'Value';
  }
}

export default MetricsChart;