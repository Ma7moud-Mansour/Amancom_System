const ctx = document.getElementById('subscriptionChart').getContext('2d');
const subscriptionChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['1 إبريل', '5 إبريل', '10 إبريل', '15 إبريل', '20 إبريل'],
    datasets: [{
      label: 'اشتراكات جديدة',
      data: [12, 19, 7, 14, 22],
      borderColor: '#007bff',
      backgroundColor: 'rgba(0, 123, 255, 0.1)',
      tension: 0.4,
      fill: true,
      pointRadius: 4,
      pointHoverRadius: 6
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    },
    plugins: {
      legend: {
        display: false
      }
    }
  }
});
