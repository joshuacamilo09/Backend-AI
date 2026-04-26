const user = window.BackendAIUser || {
    name: "User",
    email: "",
    initials: "U",
    role: "user",
};

function renderUser() {
    const avatar = document.getElementById("userAvatar");
    const userName = document.getElementById("userName");
    const userRole = document.getElementById("userRole");

    if (avatar) avatar.textContent = user.initials;
    if (userName) userName.textContent = user.name;
    if (userRole) userRole.textContent = `${user.email} · ${user.role}`;
}

function setText(id, value) {
    const element = document.getElementById(id);
    if (element) element.textContent = value;
}

function percent(value) {
    return `${Number(value || 0).toFixed(1)}%`;
}

async function fetchJson(url) {
    const response = await fetch(url, {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
    });

    if (!response.ok) {
        throw new Error(`Failed to load ${url}`);
    }

    const payload = await response.json();
    return payload.data;
}

function renderBars(containerId, items, labelKey = "date") {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (!items || !items.length) {
        container.innerHTML = `<p class="analytics-muted">No data available.</p>`;
        return;
    }

    const max = Math.max(...items.map((item) => Number(item.total || 0)), 1);

    container.innerHTML = items
        .map((item) => {
            const width = (Number(item.total || 0) / max) * 100;

            return `
                <div class="bar-row">
                    <span>${item[labelKey]}</span>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:${width}%"></div>
                    </div>
                    <strong>${item.total}</strong>
                </div>
            `;
        })
        .join("");
}

function renderListChart(containerId, items) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (!items || !items.length) {
        container.innerHTML = `<p class="analytics-muted">No data available.</p>`;
        return;
    }

    const max = Math.max(...items.map((item) => Number(item.total || 0)), 1);

    container.innerHTML = items
        .map((item) => {
            const width = (Number(item.total || 0) / max) * 100;

            return `
                <div class="bar-row">
                    <span>${item.label}</span>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:${width}%"></div>
                    </div>
                    <strong>${item.total}</strong>
                </div>
            `;
        })
        .join("");
}

function renderSummary(data) {
    setText("avgProjectsValue", data.averages?.projects_per_user ?? "0");
    setText("avgGenerationsValue", data.averages?.generations_per_user ?? "0");
    setText("successRateValue", percent(data.rates?.success_rate));
    setText("errorRateValue", percent(data.rates?.error_rate));

    const docsRate = data.rates?.documentation_generation_rate || 0;
    setText("documentationDonut", percent(docsRate));

    const peak = data.activity?.peak_hour;
    setText(
        "peakHourValue",
        peak ? `${String(peak.hour).padStart(2, "0")}:00` : "—",
    );

    renderBars("generationsChart", data.activity?.generations_by_day || []);
    renderListChart("frameworksChart", data.popular?.frameworks || []);
}

function renderAdmin(data) {
    document.getElementById("adminAnalyticsSection").style.display = "block";

    setText("totalUsersValue", data.totals?.users ?? "0");
    setText("totalProjectsValue", data.totals?.projects ?? "0");
    setText("totalGenerationsValue", data.totals?.generations ?? "0");
    setText("abandonmentRateValue", percent(data.retention?.abandonment_rate));

    renderListChart("countriesChart", data.geo?.countries || []);
    renderListChart("citiesChart", data.geo?.cities || []);
    renderListChart("continentsChart", data.geo?.continents || []);

    setText(
        "avgDocumentationTimeValue",
        `${Number(data.performance?.average_documentation_time_ms || 0).toFixed(0)}ms`,
    );

    setText(
        "documentationGenerationRateValue",
        percent(data.documentation?.generation_rate),
    );

    setText("newUsersValue", data.users?.new_users ?? "0");
    setText("returningUsersValue", data.users?.returning_users ?? "0");

    const heatmap = document.getElementById("heatmapOutput");

    if (heatmap) {
        heatmap.textContent = JSON.stringify(
            data.geo?.heatmap_points || [],
            null,
            2,
        );
    }
}

async function initAnalytics() {
    renderUser();

    try {
        const admin = await fetchJson("/app-api/admin/analytics");

        renderSummary(admin);
        renderAdmin(admin);

        const modeBadge = document.getElementById("analyticsModeBadge");
        if (modeBadge) modeBadge.textContent = "Admin mode";
    } catch (error) {
        console.error(error);

        const modeBadge = document.getElementById("analyticsModeBadge");
        if (modeBadge) modeBadge.textContent = "Access denied";
    }
}

initAnalytics();
