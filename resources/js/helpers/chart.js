import Chart from 'chart.js/auto';

export function initBarChart() {
    const barCtx = document.getElementById("salesChart");
    if (!barCtx) return;

    new Chart(barCtx, {
        type: "bar",
        data: {
            labels: ["MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN"],
            datasets: [
                { label: "Data A", data: [10, 130, 20, 65, 50, 20, 45], backgroundColor: "#0ec96f" },
                { label: "Data B", data: [150, 10, 40, 60, 35, 65, 20], backgroundColor: "#f84f84" },
                { label: "Data C", data: [55, 22, 56, 130, 10, 30, 55], backgroundColor: "#fbc41d" },
                { label: "Data D", data: [30, 100, 90, 15, 120, 90, 90], backgroundColor: "#5858d6" }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { grid: { display: false }, barPercentage: 0.6, categoryPercentage: 0.7 },
                y: { beginAtZero: true, grid: { display: false } }
            }
        }
    });
}

export function initPieChart() {
    const pieCtx = document.getElementById("pieChart");
    if (!pieCtx) return;

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['D1002', 'D1004', 'D1005', 'D1006', 'D1003'],
            datasets: [{
                data: [20, 25, 30, 15, 10],
                backgroundColor: ['#fbc41d', '#5858d6', '#0ec96f', '#f84f84', '#e22a25'],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 15,
                        padding: 20
                    }
                }
            }
        }
    });
}
