// Set standar font untuk Chart.js agar sesuai dengan tema dashboard
Chart.defaults.global.defaultFontFamily =
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

// --- 1. Area Chart ---
// var ctxArea = document.getElementById("myAreaChart");
// var myAreaChart = new Chart(ctxArea, {
//     type: "line",
//     data: {
//         // Label diubah mewakili tanggal transaksi harian kafe
//         labels: [
//             "May 20",
//             "May 21",
//             "May 22",
//             "May 23",
//             "May 24",
//             "May 25",
//             "May 26",
//             "May 27",
//             "May 28",
//             "May 29",
//             "May 30",
//             "May 31",
//         ],
//         datasets: [
//             {
//                 label: "Pendapatan Harian",
//                 lineTension: 0.3,
//                 backgroundColor: "rgba(22, 45, 77, 0.05)",
//                 borderColor: "rgba(22, 45, 77, 1)",
//                 pointRadius: 5,
//                 pointBackgroundColor: "rgba(22, 45, 77, 1)",
//                 pointBorderColor: "rgba(255, 255, 255, 0.8)",
//                 pointHoverRadius: 6,
//                 pointHoverBackgroundColor: "#ffc107", // Aksen Emas PraCafe saat di-hover
//                 pointHoverBorderColor: "rgba(22, 45, 77, 1)",
//                 pointHitRadius: 50,
//                 pointBorderWidth: 2,
//                 // Data nominal simulasi omset harian kafe dalam Rupiah
//                 data: [
//                     450000, 620000, 580000, 950000, 1200000, 700000, 650000,
//                     820000, 790000, 1100000, 1450000, 1600000,
//                 ],
//             },
//         ],
//     },
//     options: {
//         responsive: true,
//         maintainAspectRatio: false,
//         scales: {
//             xAxes: [
//                 {
//                     time: { unit: "date" },
//                     gridLines: { display: false },
//                     ticks: { maxTicksLimit: 7 },
//                 },
//             ],
//             yAxes: [
//                 {
//                     ticks: {
//                         beginAtZero: true,
//                         maxTicksLimit: 5,
//                         // Mengubah angka sumbu Y menjadi format ringkas (contoh: 1M untuk 1 Juta)
//                         callback: function (value) {
//                             if (value >= 1000000)
//                                 return "Rp " + value / 1000000 + "M";
//                             if (value >= 1000)
//                                 return "Rp " + value / 1000 + "k";
//                             return "Rp " + value;
//                         },
//                     },
//                     gridLines: { color: "rgba(0, 0, 0, .08)" },
//                 },
//             ],
//         },
//         legend: { display: false },
//         // Mengubah popup tooltip agar memunculkan format rupiah utuh yang rapi
//         tooltips: {
//             callbacks: {
//                 label: function (tooltipItem, data) {
//                     return (
//                         " " +
//                         data.datasets[tooltipItem.datasetIndex].label +
//                         ": Rp " +
//                         tooltipItem.yLabel.toLocaleString("id-ID")
//                     );
//                 },
//             },
//         },
//     },
// });

// // --- 2. Bar Chart: Monthly Sales Comparison ---
// var ctxBar = document.getElementById("myBarChart");
// var myBarChart = new Chart(ctxBar, {
//     type: "bar",
//     data: {
//         labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
//         datasets: [
//             {
//                 label: "Total Omset Bulanan",
//                 backgroundColor: "rgba(22, 45, 77, 1)",
//                 borderColor: "rgba(22, 45, 77, 1)",
//                 hoverBackgroundColor: "#ffc107", // Berganti warna emas saat baris balok disorot
//                 data: [
//                     14200000, 18500000, 22100000, 28400000, 34200000, 41900000,
//                 ],
//             },
//         ],
//     },
//     options: {
//         responsive: true,
//         maintainAspectRatio: false,
//         scales: {
//             xAxes: [
//                 { gridLines: { display: false }, ticks: { maxTicksLimit: 6 } },
//             ],
//             yAxes: [
//                 {
//                     ticks: {
//                         beginAtZero: true,
//                         maxTicksLimit: 5,
//                         callback: function (value) {
//                             if (value >= 1000000)
//                                 return "Rp " + value / 1000000 + "M";
//                             return "Rp " + value;
//                         },
//                     },
//                     gridLines: { display: true, color: "rgba(0, 0, 0, .05)" },
//                 },
//             ],
//         },
//         legend: { display: false },
//         tooltips: {
//             callbacks: {
//                 label: function (tooltipItem, data) {
//                     return (
//                         " " +
//                         data.datasets[tooltipItem.datasetIndex].label +
//                         ": Rp " +
//                         tooltipItem.yLabel.toLocaleString("id-ID")
//                     );
//                 },
//             },
//         },
//     },
// });

// --- 3. Pie Chart ---
var ctxPie = document.getElementById("myPieChart");
var myPieChart = new Chart(ctxPie, {
    type: "pie",
    data: {
        labels: ["Direct", "Social", "Referral", "Other"],
        datasets: [
            {
                data: [12.21, 15.58, 11.25, 8.32],
                backgroundColor: ["#162d4d", "#10b981", "#f59e0b", "#ef4444"],
            },
        ],
    },
});

// --- 4. Doughnut Chart ---
var ctxDou = document.getElementById("myDoughnutChart");
var myDoughnutChart = new Chart(ctxDou, {
    type: "doughnut",
    data: {
        labels: ["Desktop", "Mobile", "Tablet"],
        datasets: [
            {
                data: [55, 30, 15],
                backgroundColor: ["#162d4d", "#8898aa", "#eef2ff"],
                borderWidth: 1,
            },
        ],
    },
    options: {
        cutoutPercentage: 70,
        legend: { position: "bottom" },
    },
});
