document.addEventListener("DOMContentLoaded", function () {
    const data = window.dashboardData;
    let attendanceChart;

    const leaderLinesPlugin = {
        id: "leaderLines",
        afterDatasetsDraw: (chart) => {
            const { ctx } = chart;
            chart.data.datasets.forEach((dataset, i) => {
                chart.getDatasetMeta(i).data.forEach((datapoint, index) => {
                    const { x, y, startAngle, endAngle, outerRadius } = datapoint;
                    const value = chart.data.datasets[i].data[index];
                    if (value === 0) return;
                    const midAngle = (startAngle + endAngle) / 2;
                    const isRightSide = Math.cos(midAngle) >= 0;

                    const startX = x + Math.cos(midAngle) * outerRadius;
                    const startY = y + Math.sin(midAngle) * outerRadius;

                    const bendX = datapoint.x + Math.cos(midAngle) * (outerRadius + 15);
                    const bendY = datapoint.y + Math.sin(midAngle) * (outerRadius + 15);

                    const lineLength = 20;
                    const endX = bendX + (isRightSide ? lineLength : -lineLength);
                    const endY = bendY;

                    const color = dataset.backgroundColor[index];
                    ctx.strokeStyle = color;
                    ctx.lineWidth = 1.5;
                    ctx.lineCap = "round";
                    ctx.lineJoin = "round";

                    ctx.beginPath();
                    ctx.moveTo(startX, startY);
                    ctx.lineTo(bendX, bendY);
                    ctx.lineTo(endX, endY);
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.fillStyle = color;
                    ctx.arc(startX, startY, 3, 0, 2 * Math.PI);
                    ctx.fill();

                    ctx.font = '700 13px "Inter", sans-serif';
                    ctx.fillStyle = "#1E293B";
                    ctx.textAlign = isRightSide ? "left" : "right";
                    ctx.textBaseline = "middle";

                    const textPadding = 10;
                    const textX = endX + (isRightSide ? textPadding : -textPadding);
                    ctx.fillText(value, textX, endY);
                });
            });
        },
    };

    const pieCanvas = document.getElementById("salesPieChart");
    if (pieCanvas) {
        new Chart(pieCanvas, {
            type: "doughnut",
            plugins: [leaderLinesPlugin],
            data: {
                labels: ["PKL", "Magang"],
                datasets: [
                    {
                        data: [data.totalPkl || 0, data.totalMagang || 0],
                        backgroundColor: ["#4f46e5", "#f59e0b"],
                        borderWidth: 2,
                        borderColor: "#ffffff",
                        spacing: 4,
                        cutout: "75%",
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: { top: 20, bottom: 20, left: 55, right: 55 },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: "#1E293B",
                        padding: 12,
                        cornerRadius: 8,
                    },
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1500,
                    easing: "easeOutQuart",
                },
            },
        });
    }

    const ctxAttendance = document.getElementById("attendanceChart")?.getContext("2d");
    const loadingOverlay = document.getElementById("chartLoading");
    const filterButtons = document.querySelectorAll(".filter-btn");
    const weekSelector = document.getElementById("weekSelector");

    if (ctxAttendance) {
        function initAttendanceChart(labels, datasets) {
            if (attendanceChart) {
                attendanceChart.destroy();
            }

            const colors = [
                { border: "#4f46e5", bg: "rgba(79, 70, 229, 0.1)" },
                { border: "#10b981", bg: "rgba(16, 185, 129, 0.1)" }
            ];

            const formattedDatasets = datasets.map((ds, i) => {
                const color = colors[i % colors.length];
                const gradient = ctxAttendance.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, color.bg);
                gradient.addColorStop(1, "rgba(255, 255, 255, 0)");

                return {
                    label: ds.label,
                    data: ds.data,
                    borderColor: color.border,
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: "#fff",
                    pointBorderColor: color.border,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                };
            });

            attendanceChart = new Chart(ctxAttendance, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: formattedDatasets,
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 12, weight: '600' }
                            }
                        },
                        tooltip: {
                            backgroundColor: "#1E293B",
                            padding: 12,
                            cornerRadius: 8,
                            mode: 'index',
                            intersect: false
                        },
                    },
                    interaction: { mode: "index", intersect: false },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: { size: 11, weight: "600" },
                                color: "#64748B",
                            },
                            grid: { color: "#F1F5F9" },
                        },
                        x: {
                            ticks: { font: { size: 11, weight: "bold" }, color: "#64748B" },
                            grid: { display: false },
                        },
                    },
                },
            });
        }

        if (window.attendanceData) {
            initAttendanceChart(window.attendanceData.labels, window.attendanceData.datasets);
        }

        function toggleWeekSelector(filter) {
            if (weekSelector) {
                if (filter === "minggu") {
                    weekSelector.classList.remove("hidden");
                } else {
                    weekSelector.classList.add("hidden");
                }
            }
        }

        if (window.initialFilter) {
            toggleWeekSelector(window.initialFilter);
        }

        function fetchChartData(filter, week = null) {
            if (loadingOverlay) loadingOverlay.classList.remove("hidden");

            let url = `${window.routes.dashboard}?filter=${filter}`;
            if (filter === "minggu" && week) {
                url += `&week=${week}`;
            }

            fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    initAttendanceChart(data.labels, data.datasets);
                    if (loadingOverlay) loadingOverlay.classList.add("hidden");
                })
                .catch((error) => {
                    console.error("Error fetching chart data:", error);
                    if (loadingOverlay) loadingOverlay.classList.add("hidden");
                });
        }

        filterButtons.forEach((btn) => {
            btn.addEventListener("click", function () {
                const filter = this.getAttribute("data-filter");

                filterButtons.forEach((b) => {
                    b.classList.remove("bg-white", "text-primary", "shadow-sm", "border", "border-gray-100");
                    b.classList.add("text-slate-500", "hover:text-slate-700");
                });
                this.classList.add("bg-white", "text-primary", "shadow-sm", "border", "border-gray-100");
                this.classList.remove("text-slate-500", "hover:text-slate-700");

                toggleWeekSelector(filter);

                let weekVal = null;
                if (filter === "minggu" && weekSelector) {
                    weekVal = weekSelector.value;
                }
                fetchChartData(filter, weekVal);
            });
        });

        if (weekSelector) {
            weekSelector.addEventListener("change", function () {
                fetchChartData("minggu", this.value);
            });
        }
    }

    const ctxDonut = document.getElementById("attendanceDonutChart")?.getContext("2d");
    if (ctxDonut && window.attendanceBreakdown) {
        new Chart(ctxDonut, {
            type: "doughnut",
            plugins: [leaderLinesPlugin],
            data: {
                labels: ["Hadir", "Izin", "Sakit", "Alpha"],
                datasets: [
                    {
                        data: [
                            window.attendanceBreakdown.Hadir,
                            window.attendanceBreakdown.Izin,
                            window.attendanceBreakdown.Sakit,
                            window.attendanceBreakdown.Alpha,
                        ],
                        backgroundColor: ["#3B82F6", "#EAB308", "#EF4444", "#94A3B8"],
                        hoverBackgroundColor: ["#2563EB", "#CA8A04", "#DC2626", "#64748B"],
                        borderWidth: 2,
                        borderColor: "#ffffff",
                        spacing: 4,
                        cutout: "75%",
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: { top: 20, bottom: 20, left: 55, right: 55 },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: "#1E293B",
                        padding: 12,
                        cornerRadius: 8,
                    },
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: "easeOutQuart",
                },
            },
        });
    }

    function initBarChart(ctxId, wrapperId, chartData, color) {
        const canvas = document.getElementById(ctxId);
        if (!canvas || !chartData) return;

        const labels = chartData.map((item) => item.asal_sekolah_universitas);
        const totals = chartData.map((item) => item.total);

        const maxValue = Math.max(...totals, 5);
        const stepSize = maxValue <= 10 ? 1 : Math.ceil(maxValue / 5);

        const wrapper = document.getElementById(wrapperId);
        if (wrapper) {
            const count = chartData.length;
            wrapper.style.minWidth = Math.max(count * 80, 200) + "px";
        }

        new Chart(canvas, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: "Jumlah Peserta",
                    data: totals,
                    backgroundColor: color.bg,
                    borderColor: color.border,
                    borderWidth: 1,
                    borderRadius: 8,
                    barThickness: 40,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 30,
                            font: { size: 10, weight: '600' },
                            color: '#64748B'
                        },
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: stepSize,
                            precision: 0,
                            font: { size: 10, weight: '600' },
                            color: '#64748B'
                        },
                        grid: { color: '#F8FAFC' }
                    },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: "#1E293B",
                        padding: 10,
                        cornerRadius: 8,
                    },
                },
            },
        });
    }

    initBarChart("uniBarChart", "uniChartWrapper", data.pesertaUniversitas, { bg: '#0ea5e9', border: '#0284c7' });

    document.querySelectorAll('.delete-feedback').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const item = this.closest('.group');
            
            if (confirm('Apakah Anda yakin ingin menghapus feedback ini?')) {
                fetch(`/admin/feedback/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        item.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            item.remove();
                            if (window.showToast) {
                                window.showToast('Feedback berhasil dihapus', 'success');
                            }
                        }, 300);
                    } else {
                        if (window.showToast) {
                            window.showToast('Gagal menghapus feedback', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (window.showToast) {
                        window.showToast('Terjadi kesalahan sistem', 'error');
                    }
                });
            }
        });
    });
});
