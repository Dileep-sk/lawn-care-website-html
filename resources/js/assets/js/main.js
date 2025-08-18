const toggleBtn = document.getElementById("collapseToggle");
const body = document.body;

// Check localStorage on page load
if (localStorage.getItem("sidebar-collapsed") === "true") {
  body.classList.add("collapsed");
}

toggleBtn.addEventListener("click", () => {
  const isCollapsed = body.classList.toggle("collapsed");

  // Save current state to localStorage
  localStorage.setItem("sidebar-collapsed", isCollapsed);
});

window.addEventListener("load", function () {
  const loader = document.getElementById("loader");
  loader.style.display = "none";
});

function ShippingModal(show) {
  const modal = document.getElementById("Shipping_modal");
  if (show) {
    modal.classList.remove("pointer-events-none", "opacity-0", "scale-95");
    modal.classList.add("opacity-100", "scale-100");
  } else {
    modal.classList.remove("opacity-100");
    modal.classList.add("opacity-0");
    setTimeout(() => {
      modal.classList.add("pointer-events-none");
    }, 300); // match `duration-300`
  }
}

document.addEventListener("DOMContentLoaded", () => {
  document.body.classList.add("fade-in");
});

 function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    const isPassword = input.type === "password";
    input.type = isPassword ? "text" : "password";
    icon.src = isPassword
      ? "../../../assets/icons/eye-on.svg"
      : "../../../assets/icons/eye-off.svg";
  }





const chart = new Chart(document.getElementById("salesChart"), {
  type: "bar",
  data: {
    labels: ["MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN"],
    datasets: [
      {
        label: "Data A",
        data: [10, 130, 20, 65, 50, 20, 45],
        backgroundColor: "#0ec96f"
      },
      {
        label: "Data B",
        data: [150, 10, 40, 60, 35, 65, 20],
        backgroundColor: "#f84f84"
      },
      {
        label: "Data C",
        data: [55, 22, 56, 130, 10, 30, 55],
        backgroundColor: "#fbc41d"
      },
      {
        label: "Data D",
        data: [30, 100, 90, 15, 120, 90, 90],
        backgroundColor: "#5858d6"
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false, // Allow full width
    plugins: {
      legend: { display: false }
    },
    scales: {
      x: {
        grid: { display: false },
        barPercentage: 0.6,
        categoryPercentage: 0.7
      },
      y: {
        beginAtZero: true,
        grid: { display: false }
      }
    }
  }
});




    const ctx = document.getElementById('pieChart').getContext('2d');
    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['D1002', 'D1004', 'D1005', 'D1006', 'D1003'],
        datasets: [{
          data: [20, 25, 30, 15, 10],
          backgroundColor: [
            '#fbc41d', // Yellow
            '#5858d6', // Blue
            '#0ec96f', // Green
            '#f84f84', // Red/Orange
            '#e22a25'  // Red
          ],
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
  

$(document).ready(function () {
  $("#kt_daterangepicker_1").daterangepicker({
    locale: {
      format: "DD-MM-YYYY",
    },
  });
});
