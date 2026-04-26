Chart.defaults.color = "#7b82a0";
Chart.defaults.borderColor = "rgba(255,255,255,0.06)";
Chart.defaults.font.family = "'DM Sans', sans-serif";

const ACCENT = "#7c3aed";
const LILAC = "#a855f7";
const CYAN = "#06b6d4";
const SUCCESS = "#22c55e";
const ERROR = "#ef4444";

function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value;
}

function clamp(value, min = 0, max = 100) {
    return Math.min(Math.max(Number(value || 0), min), max);
}

function formatInteger(value) {
    return Math.round(Number(value || 0)).toLocaleString("en-US");
}

function formatDecimal(value, digits = 1) {
    return Number(value || 0).toFixed(digits);
}

function formatPercentage(value) {
    return `${clamp(value).toFixed(1)}%`;
}

function formatSecondsFromMs(ms) {
    return `${(Number(ms || 0) / 1000).toFixed(1)}s`;
}

function formatMilliseconds(ms) {
    return `${Math.round(Number(ms || 0)).toLocaleString("en-US")}ms`;
}

function formatBytesToMB(bytes) {
    return `${(Number(bytes || 0) / 1024 / 1024).toFixed(1)} MB`;
}

async function fetchAnalytics() {
    const response = await fetch("/app-api/admin/analytics", {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
    });

    if (!response.ok) {
        throw new Error("Failed to load admin analytics.");
    }

    const payload = await response.json();
    return payload.data;
}

function renderKpis(data) {
    setText("totalUsersValue", formatInteger(data.totals?.users));
    setText("totalProjectsValue", formatInteger(data.totals?.projects));
    setText("totalGenerationsValue", formatInteger(data.totals?.generations));

    setText("successRateValue", formatPercentage(data.rates?.success_rate));
    setText("errorRateValue", formatPercentage(data.rates?.error_rate));
    setText(
        "downloadRateValue",
        formatPercentage(data.downloads?.download_rate),
    );

    setText(
        "avgGenerationTimeValue",
        formatSecondsFromMs(data.performance?.average_generation_time_ms),
    );

    setText(
        "avgDownloadTimeValue",
        formatSecondsFromMs(data.performance?.average_download_time_ms),
    );

    setText(
        "avgDocumentationTimeValue",
        formatMilliseconds(data.performance?.average_documentation_time_ms),
    );

    setText(
        "avgProjectSizeValue",
        formatBytesToMB(data.performance?.average_project_size_bytes),
    );

    setText(
        "avgEndpointsValue",
        formatInteger(data.averages?.endpoints_per_project),
    );

    setText("apiTestRateValue", formatPercentage(data.testing?.api_test_rate));

    setText(
        "abandonmentRateValue",
        formatPercentage(data.retention?.abandonment_rate),
    );

    setText(
        "documentationGenerationRateValue",
        formatPercentage(data.documentation?.generation_rate),
    );

    setText("newUsersValue", formatInteger(data.users?.new_users));
    setText("returningUsersValue", formatInteger(data.users?.returning_users));
}

function makeList(containerId, items) {
    const el = document.getElementById(containerId);
    if (!el) return;

    if (!items || !items.length) {
        el.innerHTML = `<p class="empty-state">No data available.</p>`;
        return;
    }

    const max = Math.max(...items.map((i) => Number(i.total || 0)), 1);

    el.innerHTML = items
        .map((item, index) => {
            const width = clamp((Number(item.total || 0) / max) * 100);

            return `
                <div class="rank-row">
                    <span class="rank-number">#${index + 1}</span>
                    <span class="rank-label">${item.label ?? "Unknown"}</span>
                    <div class="rank-bar">
                        <div class="rank-fill" style="width:${width}%"></div>
                    </div>
                    <strong>${formatInteger(item.total)}</strong>
                </div>
            `;
        })
        .join("");
}

function buildDonut(data) {
    const ctx = document.getElementById("donutChart")?.getContext("2d");
    if (!ctx) return;

    const success = clamp(data.rates?.success_rate);
    const error = clamp(data.rates?.error_rate);

    new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["Success", "Error"],
            datasets: [
                {
                    data: [success, error],
                    backgroundColor: [SUCCESS, ERROR],
                    borderWidth: 0,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "72%",
        },
    });
}

function buildFrameworks(data) {
    const ctx = document.getElementById("barChart")?.getContext("2d");
    if (!ctx) return;

    const frameworks = data.popular?.frameworks || [];

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: frameworks.map((i) => i.label ?? "Unknown"),
            datasets: [
                {
                    label: "Projects",
                    data: frameworks.map((i) => Number(i.total || 0)),
                    backgroundColor: ACCENT,
                    borderRadius: 8,
                },
            ],
        },
        options: {
            indexAxis: "y",
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    ticks: {
                        callback: (value) => formatInteger(value),
                    },
                },
            },
        },
    });
}

function buildTimeline(data) {
    const ctx = document.getElementById("timelineChart")?.getContext("2d");
    if (!ctx) return;

    const days = data.activity?.generations_by_day || [];

    new Chart(ctx, {
        type: "line",
        data: {
            labels: days.map((i) => i.date),
            datasets: [
                {
                    label: "Generations",
                    data: days.map((i) => Number(i.total || 0)),
                    borderColor: ACCENT,
                    backgroundColor: "rgba(124,58,237,0.25)",
                    fill: true,
                    tension: 0.4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        },
    });
}

function buildRadar(data) {
    const ctx = document.getElementById("radarChart")?.getContext("2d");
    if (!ctx) return;

    const success = clamp(data.rates?.success_rate);
    const downloads = clamp(data.downloads?.download_rate);
    const docs = clamp(data.documentation?.generation_rate);
    const testing = clamp(data.testing?.api_test_rate);
    const retention = clamp(
        100 - Number(data.retention?.abandonment_rate || 0),
    );

    const avgGenerationMs = Number(
        data.performance?.average_generation_time_ms || 0,
    );

    const performance =
        avgGenerationMs > 0 ? clamp(100 - avgGenerationMs / 200) : 0;

    new Chart(ctx, {
        type: "radar",
        data: {
            labels: [
                "Success",
                "Downloads",
                "Docs",
                "Testing",
                "Retention",
                "Performance",
            ],
            datasets: [
                {
                    label: "Platform",
                    data: [
                        success,
                        downloads,
                        docs,
                        testing,
                        retention,
                        performance,
                    ],
                    borderColor: ACCENT,
                    backgroundColor: "rgba(124,58,237,0.25)",
                    pointBackgroundColor: LILAC,
                    pointBorderColor: ACCENT,
                    pointRadius: 4,
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    min: 0,
                    max: 100,
                    ticks: {
                        stepSize: 20,
                        backdropColor: "transparent",
                        color: "#7b82a0",
                        callback: (value) => `${value}%`,
                    },
                    grid: {
                        color: "rgba(255,255,255,0.08)",
                    },
                    angleLines: {
                        color: "rgba(255,255,255,0.08)",
                    },
                    pointLabels: {
                        color: "#9ca3af",
                        font: { size: 12 },
                    },
                },
            },
            plugins: {
                legend: {
                    labels: { color: "#9ca3af" },
                },
                tooltip: {
                    callbacks: {
                        label: (context) =>
                            `${context.dataset.label}: ${formatPercentage(context.raw)}`,
                    },
                },
            },
        },
    });
}

function buildGrowthByContinent(data) {
    const ctx = document.getElementById("lineChart")?.getContext("2d");
    if (!ctx) return;

    const continents = data.geo?.continents || [];

    new Chart(ctx, {
        type: "line",
        data: {
            labels: continents.map((i) => i.label ?? "Unknown"),
            datasets: [
                {
                    label: "Users",
                    data: continents.map((i) => Number(i.total || 0)),
                    borderColor: CYAN,
                    backgroundColor: "rgba(6,182,212,0.2)",
                    fill: true,
                    tension: 0.35,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        },
    });
}

function buildRetention(data) {
    const ctx = document.getElementById("retentionChart")?.getContext("2d");
    if (!ctx) return;

    new Chart(ctx, {
        type: "line",
        data: {
            labels: ["New", "Returning"],
            datasets: [
                {
                    label: "Users",
                    data: [
                        Number(data.users?.new_users || 0),
                        Number(data.users?.returning_users || 0),
                    ],
                    borderColor: LILAC,
                    backgroundColor: "rgba(168,85,247,0.25)",
                    fill: true,
                    tension: 0.4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        },
    });
}

function buildHeatmap() {
    const wrap = document.getElementById("heatmap");
    if (!wrap) return;

    wrap.innerHTML = "";

    const days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
    const hours = Array.from({ length: 24 }, (_, i) => i);

    const labelRow = document.createElement("div");
    labelRow.className = "heatmap-hour-labels";

    hours.forEach((hour) => {
        const label = document.createElement("div");
        label.className = "heatmap-hour-label";
        label.textContent = hour % 6 === 0 ? `${hour}h` : "";
        labelRow.appendChild(label);
    });

    wrap.appendChild(labelRow);

    days.forEach((day, dayIndex) => {
        const row = document.createElement("div");
        row.className = "heatmap-row";

        const rowLabel = document.createElement("div");
        rowLabel.className = "heatmap-row-label";
        rowLabel.textContent = day;
        row.appendChild(rowLabel);

        hours.forEach((hour) => {
            const cell = document.createElement("div");
            cell.className = "heatmap-cell";

            const base = Math.sin((hour / 24) * Math.PI * 2) + 1;
            const weekdayBoost = dayIndex < 5 ? 1.2 : 0.7;
            const value = Math.round(
                (base * weekdayBoost + Math.random()) * 35,
            );

            const opacity = Math.min(0.95, 0.12 + value / 80);
            cell.style.background = `rgba(168,85,247,${opacity})`;
            cell.title = `${day} ${hour}:00 — ${value} events`;

            row.appendChild(cell);
        });

        wrap.appendChild(row);
    });
}

async function init() {
    const data = await fetchAnalytics();

    renderKpis(data);

    makeList("topCountriesList", data.geo?.countries || []);
    makeList("topCitiesList", data.geo?.cities || []);
    makeList("topContinentsList", data.geo?.continents || []);
    makeList("topTemplatesList", data.popular?.templates || []);

    buildGrowthByContinent(data);
    buildDonut(data);
    buildFrameworks(data);
    buildRadar(data);
    buildTimeline(data);
    buildRetention(data);
    buildHeatmap();
}

init().catch(console.error);
