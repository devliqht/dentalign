document.addEventListener("DOMContentLoaded", function () {
  initializeCharts();
});

function initializeCharts() {
  // Revenue Trend Chart
  initializeRevenueChart();

  // Appointment Type Distribution Chart
  initializeAppointmentTypeChart();

  // Appointment Status Chart
  initializeAppointmentStatusChart();

  // Daily Appointments Chart
  initializeDailyAppointmentsChart();

  // Monthly Appointments Chart
  initializeMonthlyAppointmentsChart();
}

function initializeRevenueChart() {
  const ctx = document.getElementById("revenueChart");
  if (!ctx) return;

  const data = window.reportData.revenue;

  new Chart(ctx, {
    type: "line",
    data: {
      labels: data.labels,
      datasets: [
        {
          label: "Revenue (₱)",
          data: data.data,
          borderColor: "#3B82F6",
          backgroundColor: "rgba(59, 130, 246, 0.1)",
          borderWidth: 3,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: "#3B82F6",
          pointBorderColor: "#ffffff",
          pointBorderWidth: 2,
          pointRadius: 6,
          pointHoverRadius: 8,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: "top",
          labels: {
            usePointStyle: true,
            padding: 20,
          },
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          titleColor: "#ffffff",
          bodyColor: "#ffffff",
          borderColor: "#3B82F6",
          borderWidth: 1,
          callbacks: {
            label: function (context) {
              return "Revenue: ₱" + context.parsed.y.toLocaleString();
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return "₱" + value.toLocaleString();
            },
          },
          grid: {
            color: "rgba(0, 0, 0, 0.1)",
          },
        },
        x: {
          grid: {
            color: "rgba(0, 0, 0, 0.1)",
          },
        },
      },
      elements: {
        point: {
          hoverRadius: 8,
        },
      },
    },
  });
}

function initializeAppointmentTypeChart() {
  const ctx = document.getElementById("appointmentTypeChart");
  if (!ctx) return;

  const data = window.reportData.appointmentTypes;

  const colors = [
    "#143e79",
    "#8da733",
    "#8b5629",
    "#143e79cc",
    "#8da733cc",
    "#8b5629cc",
    "#143e7999",
    "#8da73399",
    "#8b562999",
    "#143e7966",
  ];

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: data.labels,
      datasets: [
        {
          data: data.data,
          backgroundColor: colors.slice(0, data.labels.length),
          borderColor: "#ffffff",
          borderWidth: 2,
          hoverBorderWidth: 3,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: "right",
          labels: {
            usePointStyle: true,
            padding: 15,
            generateLabels: function (chart) {
              const data = chart.data;
              if (data.labels.length && data.datasets.length) {
                const dataset = data.datasets[0];
                return data.labels.map((label, i) => {
                  const value = dataset.data[i];
                  const percentage = (
                    (value / data.datasets[0].data.reduce((a, b) => a + b, 0)) *
                    100
                  ).toFixed(1);
                  return {
                    text: `${label} (${percentage}%)`,
                    fillStyle: dataset.backgroundColor[i],
                    index: i,
                  };
                });
              }
              return [];
            },
          },
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          titleColor: "#ffffff",
          bodyColor: "#ffffff",
          borderColor: "#3B82F6",
          borderWidth: 1,
          callbacks: {
            label: function (context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((context.parsed / total) * 100).toFixed(1);
              return `${context.label}: ${context.parsed} (${percentage}%)`;
            },
          },
        },
      },
    },
  });
}

function initializeAppointmentStatusChart() {
  const ctx = document.getElementById("appointmentStatusChart");
  if (!ctx) return;

  const data = window.reportData.appointmentStatus;

  const statusColors = {
    Completed: "#8da733", // nhd-green
    Pending: "#fdf3de", // nhd-pale
    Approved: "#143e79", // nhd-blue
    Cancelled: "#8b5629", // nhd-brown
    Declined: "#6B7280", // keeping gray for declined
    Rescheduled: "#143e79", // nhd-blue again since no other brand colors
  };

  const colors = data.labels.map((label) => statusColors[label] || "#6B7280");

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: data.labels,
      datasets: [
        {
          label: "Number of Appointments",
          data: data.data,
          backgroundColor: colors,
          borderColor: colors,
          borderWidth: 1,
          borderRadius: 8,
          borderSkipped: false,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          titleColor: "#ffffff",
          bodyColor: "#ffffff",
          borderColor: "#3B82F6",
          borderWidth: 1,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
          },
          grid: {
            color: "rgba(0, 0, 0, 0.1)",
          },
        },
        x: {
          grid: {
            display: false,
          },
        },
      },
    },
  });
}

function initializeDailyAppointmentsChart() {
  const ctx = document.getElementById("dailyAppointmentsChart");
  if (!ctx) return;

  const rawData = window.reportData.dailyAppointments;

  // Process data to ensure we have all dates in the last 30 days
  const endDate = new Date();
  const startDate = new Date();
  startDate.setDate(endDate.getDate() - 29);

  const labels = [];
  const data = [];

  for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
    const dateStr = d.toISOString().split("T")[0];
    labels.push(
      d.toLocaleDateString("en-US", { month: "short", day: "numeric" }),
    );

    const found = rawData.find((item) => item.date === dateStr);
    data.push(found ? parseInt(found.count) : 0);
  }

  new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Daily Appointments",
          data: data,
          borderColor: "rgba(20, 62, 121, .4)",
          backgroundColor: "rgba(20, 62, 121, .2)",
          borderWidth: 2,
          fill: true,
          tension: 0.3,
          pointBackgroundColor: "#143e79",
          pointBorderColor: "#143e79",
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: "top",
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          titleColor: "#ffffff",
          bodyColor: "#ffffff",
          borderColor: "#10B981",
          borderWidth: 1,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
          },
          grid: {
            color: "rgba(0, 0, 0, 0.1)",
          },
        },
        x: {
          grid: {
            color: "rgba(0, 0, 0, 0.1)",
          },
        },
      },
    },
  });
}

function initializeMonthlyAppointmentsChart() {
  const ctx = document.getElementById("monthlyAppointmentsChart");
  if (!ctx) return;

  const rawData = window.reportData.monthlyAppointments;

  const labels = rawData.map((item) => item.month_name + " " + item.year);
  const data = rawData.map((item) => parseInt(item.count));

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Monthly Appointments",
          data: data,
          backgroundColor: "rgba(59, 130, 246, 0.8)",
          borderColor: "#3B82F6",
          borderWidth: 1,
          borderRadius: 8,
          borderSkipped: false,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: "top",
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          titleColor: "#ffffff",
          bodyColor: "#ffffff",
          borderColor: "#3B82F6",
          borderWidth: 1,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
          },
          grid: {
            color: "rgba(0, 0, 0, 0.1)",
          },
        },
        x: {
          grid: {
            display: false,
          },
        },
      },
    },
  });
}

// Export functionality
function exportReport(format) {
  switch (format) {
    case "pdf":
      exportToPDF();
      break;
    case "excel":
      exportToExcel();
      break;
    default:
      toast.info("Export format not supported yet");
  }
}

function exportToPDF() {
  // For now, use browser's print functionality
  // In a real implementation, you'd want to use a library like jsPDF
  window.print();
  toast.success(
    "Print dialog opened. You can save as PDF from the print options.",
  );
}

function exportToExcel() {
  // Basic CSV export (can be opened in Excel)
  let csvContent = "data:text/csv;charset=utf-8,";

  // Add revenue data
  csvContent += "Revenue Report\n";
  csvContent += "Month,Amount\n";
  window.reportData.revenue.labels.forEach((label, index) => {
    csvContent += `${label},${window.reportData.revenue.data[index]}\n`;
  });

  csvContent += "\nAppointment Types\n";
  csvContent += "Type,Count\n";
  window.reportData.appointmentTypes.labels.forEach((label, index) => {
    csvContent += `${label},${window.reportData.appointmentTypes.data[index]}\n`;
  });

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "clinic_report.csv");
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  toast.success("Report exported as CSV file");
}

function printReport() {
  window.print();
}
