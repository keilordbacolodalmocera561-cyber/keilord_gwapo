document.addEventListener("DOMContentLoaded", function () {
  const revenueCtx = document.getElementById('revenueChart');
  if (revenueCtx) {
    new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: chartData.labels,
        datasets: [{
          label: 'Daily Revenue',
          data: chartData.totals,
          backgroundColor: 'rgba(192, 0, 0, 0.2)',
          borderColor: '#c00',
          borderWidth: 2,
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  }

  const productCtx = document.getElementById('productChart');
  if (productCtx) {
    new Chart(productCtx, {
      type: 'bar',
      data: {
        labels: ['Total Products'],
        datasets: [{
          label: 'Products in Inventory',
          data: [window.productCount || 0],
          backgroundColor: '#c00',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        indexAxis: 'y',
        scales: {
          x: { beginAtZero: true }
        }
      }
    });
  }
});