const searchInput = document.querySelector(".topbar .search input");
const generateBtn = document.getElementById("createBackendBtn");
const menuItems = document.querySelectorAll(".menu-item");

const user = window.BackendAIUser || {
    name: "User",
    email: "",
    initials: "U",
};

const state = {
    projects: [],
    generations: [],
};

function getCsrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
}

function timeAgo(dateString) {
    if (!dateString) return "—";

    const now = new Date();
    const date = new Date(dateString);
    const diffMs = now - date;
    const minutes = Math.floor(diffMs / 60000);

    if (minutes < 1) return "Just now";
    if (minutes < 60) return `${minutes} min ago`;

    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours}h ago`;

    const days = Math.floor(hours / 24);
    if (days < 30) return `${days}d ago`;

    return date.toLocaleDateString();
}

function statusClass(status) {
    if (status === "completed") return "completed";
    if (status === "processing" || status === "running") return "running";
    return "pending";
}

function escapeHtml(value) {
    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

function getLatestGenerationForProject(projectId) {
    return (
        state.generations.find(
            (generation) => generation.project_id === projectId,
        ) || null
    );
}

function renderUser() {
    const avatar = document.getElementById("userAvatar");
    const userName = document.getElementById("userName");
    const userRole = document.getElementById("userRole");
    const heroUserName = document.getElementById("heroUserName");

    if (avatar) avatar.textContent = user.initials;
    if (userName) userName.textContent = user.name;
    if (userRole) userRole.textContent = user.email;
    if (heroUserName) heroUserName.textContent = user.name;
}

function renderStats() {
    const totalProjects = state.projects.length;
    const totalGenerations = state.generations.length;
    const latestGeneration = state.generations[0] || null;

    const frameworkCounts = {};
    state.projects.forEach((project) => {
        const key = project.framework || "Unknown";
        frameworkCounts[key] = (frameworkCounts[key] || 0) + 1;
    });

    const mostUsedFramework =
        Object.entries(frameworkCounts).sort((a, b) => b[1] - a[1])[0]?.[0] ||
        "—";

    document.getElementById("totalProjectsValue").textContent = totalProjects;
    document.getElementById("totalGenerationsValue").textContent =
        totalGenerations;
    document.getElementById("projectsBadge").textContent =
        `${totalProjects} total`;
    document.getElementById("generationsBadge").textContent =
        `${totalGenerations} total`;
    document.getElementById("mostUsedValue").textContent = mostUsedFramework;
    document.getElementById("mostUsedBadge").textContent = totalProjects
        ? "Most used framework"
        : "No data";

    if (latestGeneration) {
        const ago = timeAgo(latestGeneration.created_at);
        document.getElementById("lastActivityValue").textContent = ago;
        document.getElementById("lastActivityBadge").textContent =
            latestGeneration.status;
    } else {
        document.getElementById("lastActivityValue").textContent = "—";
        document.getElementById("lastActivityBadge").textContent =
            "No activity";
    }
}

function renderProjects(filter = "") {
    const tbody = document.getElementById("projectsTableBody");
    if (!tbody) return;

    const filtered = state.projects.filter((project) =>
        project.name.toLowerCase().includes(filter.toLowerCase()),
    );

    if (!filtered.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6">No projects found.</td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = filtered
        .map((project) => {
            const generation = getLatestGenerationForProject(project.id);
            const downloadUrl = generation?.download_url || null;
            const status = generation?.status || "pending";

            return `
                <tr>
                    <td>${escapeHtml(project.name)}</td>
                    <td><span class="pill">${escapeHtml(project.framework || "Laravel")}</span></td>
                    <td>${escapeHtml(project.description || "—")}</td>
                    <td>${timeAgo(project.created_at)}</td>
                    <td><span class="status ${statusClass(status)}">${escapeHtml(status)}</span></td>
                    <td>
                        ${
                            downloadUrl
                                ? `<a href="${downloadUrl}" class="view-all">Download ZIP</a>`
                                : "—"
                        }
                    </td>
                </tr>
            `;
        })
        .join("");
}

function filterProjects(query) {
    renderProjects(query.trim());
}

async function fetchProjects() {
    const response = await fetch("/app-api/projects", {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
    });

    if (!response.ok) {
        throw new Error("Failed to load projects.");
    }

    const payload = await response.json();
    return payload.data || [];
}

async function fetchGenerations() {
    const response = await fetch("/app-api/generations", {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
    });

    if (!response.ok) {
        throw new Error("Failed to load generations.");
    }

    const payload = await response.json();
    return payload.data || [];
}

function showLoadingOverlay(projectName) {
    const loading = document.createElement("div");
    loading.className = "ai-loading";
    loading.innerHTML = `
        <div class="ai-loading__inner">
            Generating backend for <strong>${escapeHtml(projectName)}</strong>
        </div>
    `;
    document.body.appendChild(loading);
    return loading;
}

async function generateBackendFromPrompt(description) {
    const response = await fetch("/app-api/generate-backend", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": getCsrfToken(),
        },
        credentials: "same-origin",
        body: JSON.stringify({ description }),
    });

    const payload = await response.json();

    if (!response.ok) {
        throw new Error(
            payload.message || payload.error || "Failed to generate backend.",
        );
    }

    return payload;
}

async function refreshDashboard() {
    const [projects, generations] = await Promise.all([
        fetchProjects(),
        fetchGenerations(),
    ]);

    state.projects = projects;
    state.generations = generations;

    renderStats();
    renderProjects(searchInput?.value || "");
}

async function openGenerator() {
    const description = prompt("Describe the backend you want to generate:");
    if (!description) return;

    const loading = showLoadingOverlay(description);

    try {
        const result = await generateBackendFromPrompt(description);
        await refreshDashboard();
        alert(
            `Backend generated successfully: ${result.project?.name ?? "New project"}`,
        );
    } catch (error) {
        console.error(error);
        alert(
            error.message || "An error occurred while generating the backend.",
        );
    } finally {
        loading.remove();
    }
}

if (searchInput) {
    searchInput.addEventListener("input", () => {
        filterProjects(searchInput.value);
    });
}

if (generateBtn) {
    generateBtn.addEventListener("click", () => {
        openGenerator();
    });
}

menuItems.forEach((item) => {
    item.addEventListener("click", () => {
        menuItems.forEach((i) => i.classList.remove("active"));
        item.classList.add("active");
    });
});

const styleSheet = document.createElement("style");
styleSheet.textContent = `
.ai-loading {
  position: fixed;
  inset: 0;
  display: grid;
  place-items: center;
  background: rgba(0, 0, 0, 0.65);
  z-index: 1000;
}
.ai-loading__inner {
  background: rgba(17, 24, 39, 0.92);
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 14px;
  padding: 20px 24px;
  font-size: 14px;
  color: #e5e7eb;
  backdrop-filter: blur(8px);
}
`;
document.head.appendChild(styleSheet);

renderUser();
refreshDashboard().catch((error) => {
    console.error(error);
    const tbody = document.getElementById("projectsTableBody");
    if (tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6">Failed to load dashboard data.</td>
            </tr>
        `;
    }
});
