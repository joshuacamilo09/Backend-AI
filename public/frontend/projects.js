const user = window.BackendAIUser || {
    name: "User",
    email: "",
    initials: "U",
};

const state = {
    projects: [],
    generations: [],
};

function escapeHtml(value) {
    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
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

function renderUser() {
    const avatar = document.getElementById("userAvatar");
    const userName = document.getElementById("userName");
    const userRole = document.getElementById("userRole");

    if (avatar) avatar.textContent = user.initials;
    if (userName) userName.textContent = user.name;
    if (userRole) userRole.textContent = user.email;
}

async function fetchProjects() {
    const response = await fetch("/app-api/projects", {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
    });

    if (!response.ok) throw new Error("Failed to load projects.");

    const payload = await response.json();
    return payload.data || [];
}

async function fetchGenerations() {
    const response = await fetch("/app-api/generations", {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
    });

    if (!response.ok) throw new Error("Failed to load generations.");

    const payload = await response.json();
    return payload.data || [];
}

function getLatestGenerationForProject(projectId) {
    return (
        state.generations.find(
            (generation) => generation.project_id === projectId,
        ) || null
    );
}

function renderProjects(filter = "") {
    const tbody = document.getElementById("allProjectsTableBody");
    const badge = document.getElementById("projectsCountBadge");

    const filtered = state.projects.filter((project) => {
        const q = filter.toLowerCase();
        return (
            project.name.toLowerCase().includes(q) ||
            (project.description || "").toLowerCase().includes(q) ||
            (project.framework || "").toLowerCase().includes(q)
        );
    });

    if (badge) {
        badge.textContent = `${filtered.length} total`;
    }

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
            const status = generation?.status || "pending";
            const downloadUrl = generation?.download_url || null;

            return `
                <tr>
                    <td>${escapeHtml(project.name)}</td>
                    <td><span class="pill">${escapeHtml(project.framework || "Laravel")}</span></td>
                    <td>${escapeHtml(project.description || "—")}</td>
                    <td>${timeAgo(project.created_at)}</td>
                    <td><span class="status ${statusClass(status)}">${escapeHtml(status)}</span></td>
                    <td>${downloadUrl ? `<a href="${downloadUrl}" class="view-all">Download ZIP</a>` : "—"}</td>
                </tr>
            `;
        })
        .join("");
}

async function initProjectsPage() {
    renderUser();

    try {
        const [projects, generations] = await Promise.all([
            fetchProjects(),
            fetchGenerations(),
        ]);

        state.projects = projects;
        state.generations = generations;

        renderProjects();
    } catch (error) {
        console.error(error);
        const tbody = document.getElementById("allProjectsTableBody");
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6">Failed to load projects.</td>
                </tr>
            `;
        }
    }

    document
        .getElementById("projectsSearchInput")
        ?.addEventListener("input", (event) => {
            renderProjects(event.target.value || "");
        });
}

initProjectsPage();
