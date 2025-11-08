// Dashboard service for handling WebSocket communication and data processing

class DashboardService {
  // Process metrics data for chart display
  static processMetricsForChart(metricsData) {
    if (!metricsData || !metricsData.data) {
      return null;
    }

    const data = metricsData.data;
    const timestamp = new Date(data.timestamp).getTime();

    return {
      cpu: {
        x: timestamp,
        y: data.cpu_usage || 0
      },
      memory: {
        x: timestamp,
        y: data.memory_usage || 0
      },
      network: {
        x: timestamp,
        y: data.network_traffic || 0
      },
      connections: {
        x: timestamp,
        y: data.active_connections || 0
      }
    };
  }

  // Format metrics for display
  static formatMetrics(metricsData) {
    if (!metricsData || !metricsData.data) {
      return {};
    }

    const data = metricsData.data;

    return {
      cpuUsage: data.cpu_usage ? `${data.cpu_usage.toFixed(2)}%` : 'N/A',
      memoryUsage: data.memory_usage ? `${data.memory_usage.toFixed(2)}%` : 'N/A',
      activeConnections: data.active_connections || 0,
      networkTraffic: data.network_traffic ? `${data.network_traffic} B/s` : 'N/A',
      uptime: this.formatUptime(data.uptime),
      timestamp: data.timestamp
    };
  }

  // Format uptime for display
  static formatUptime(seconds) {
    if (!seconds) return 'N/A';

    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;

    if (hours > 0) {
      return `${hours}h ${minutes}m ${secs}s`;
    } else if (minutes > 0) {
      return `${minutes}m ${secs}s`;
    } else {
      return `${secs}s`;
    }
  }

  // Get chart configuration
  static getChartConfig(chartType, data) {
    const baseConfig = {
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
        }
      },
      plugins: {
        legend: {
          display: true
        }
      }
    };

    switch (chartType) {
      case 'cpu':
        return {
          ...baseConfig,
          scales: {
            ...baseConfig.scales,
            y: {
              min: 0,
              max: 100,
              title: {
                display: true,
                text: 'CPU Usage (%)'
              }
            }
          }
        };
      case 'memory':
        return {
          ...baseConfig,
          scales: {
            ...baseConfig.scales,
            y: {
              min: 0,
              max: 100,
              title: {
                display: true,
                text: 'Memory Usage (%)'
              }
            }
          }
        };
      default:
        return baseConfig;
    }
  }
}

export default DashboardService;