const user = window.BackendAIUser || {
    name: "User",
    email: "",
    initials: "U",
};

const state = {
    projects: [],
    selectedProject: null,
};

function getCsrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
}

function escapeHtml(value) {
    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

function renderUser() {
    const avatar = document.getElementById("userAvatar");
    const userName = document.getElementById("userName");
    const userRole = document.getElementById("userRole");

    if (avatar) avatar.textContent = user.initials;
    if (userName) userName.textContent = user.name;
    if (userRole) userRole.textContent = user.email;
}

function renderMarkdown(markdown) {
    const output = document.getElementById("documentationOutput");

    if (!output) return;

    if (window.marked) {
        output.innerHTML = marked.parse(markdown);
    } else {
        output.textContent = markdown;
    }
}

function setDocumentationStatus(status, output = null) {
    const badge = document.getElementById("documentationStatusBadge");

    if (badge) badge.textContent = status;

    if (output !== null) {
        renderMarkdown(output);
    }
}

function setDownloadLink(projectId, visible) {
    const downloadMdBtn = document.getElementById("downloadDocumentationBtn");
    const downloadPdfBtn = document.getElementById(
        "downloadDocumentationPdfBtn",
    );

    if (!projectId || !visible) {
        if (downloadMdBtn) {
            downloadMdBtn.style.display = "none";
            downloadMdBtn.href = "#";
        }

        if (downloadPdfBtn) {
            downloadPdfBtn.style.display = "none";
            downloadPdfBtn.href = "#";
        }

        return;
    }

    if (downloadMdBtn) {
        downloadMdBtn.href = `/app-api/projects/${projectId}/documentation/download`;
        downloadMdBtn.style.display = "block";
    }

    if (downloadPdfBtn) {
        downloadPdfBtn.href = `/app-api/projects/${projectId}/documentation/download-pdf`;
        downloadPdfBtn.style.display = "block";
    }
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

async function fetchDocumentation(projectId) {
    const response = await fetch(
        `/app-api/projects/${projectId}/documentation`,
        {
            headers: { Accept: "application/json" },
            credentials: "same-origin",
        },
    );

    const payload = await response.json();

    if (!response.ok) {
        return null;
    }

    return payload.data || null;
}

async function generateDocumentation(projectId) {
    const response = await fetch(
        `/app-api/projects/${projectId}/documentation/generate`,
        {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": getCsrfToken(),
            },
            credentials: "same-origin",
        },
    );

    const payload = await response.json();

    if (!response.ok) {
        throw new Error(payload.message || "Failed to generate documentation.");
    }

    return payload.data;
}

function renderProjectOptions(filter = "") {
    const select = document.getElementById("documentationProjectSelect");
    if (!select) return;

    const query = filter.toLowerCase();

    const filteredProjects = state.projects.filter((project) => {
        return (
            project.name.toLowerCase().includes(query) ||
            (project.description || "").toLowerCase().includes(query) ||
            (project.framework || "").toLowerCase().includes(query)
        );
    });

    if (!filteredProjects.length) {
        select.innerHTML = `<option value="">No projects found</option>`;
        return;
    }

    select.innerHTML = `
        <option value="">Select a project</option>
        ${filteredProjects
            .map((project) => {
                return `
                    <option value="${project.id}">
                        ${escapeHtml(project.name)} - ${escapeHtml(project.framework || "Laravel")}
                    </option>
                `;
            })
            .join("")}
    `;
}

async function handleProjectChange(event) {
    const projectId = event.target.value;

    state.selectedProject = state.projects.find(
        (project) => String(project.id) === String(projectId),
    );

    setDownloadLink(null, false);

    if (!projectId) {
        setDocumentationStatus(
            "Waiting",
            "Select a project and generate documentation.",
        );
        return;
    }

    setDocumentationStatus("Loading", "Checking existing documentation...");

    try {
        const documentation = await fetchDocumentation(projectId);

        if (!documentation) {
            setDocumentationStatus(
                "Not generated",
                "This project does not have generated documentation yet. Click Generate Documentation.",
            );
            return;
        }

        setDocumentationStatus("Loaded", documentation.content);
        setDownloadLink(projectId, true);
    } catch (error) {
        console.error(error);
        setDocumentationStatus("Error", error.message);
    }
}

async function handleGenerateDocumentation() {
    const select = document.getElementById("documentationProjectSelect");
    const button = document.getElementById("generateDocumentationBtn");

    const projectId = select?.value;

    if (!projectId) {
        setDocumentationStatus("Validation", "Please select a project first.");
        return;
    }

    button.disabled = true;
    button.style.opacity = "0.7";

    setDocumentationStatus(
        "Generating",
        "Generating project documentation, please wait...",
    );

    try {
        const documentation = await generateDocumentation(projectId);

        setDocumentationStatus("Generated", documentation.content);
        setDownloadLink(projectId, true);
    } catch (error) {
        console.error(error);
        setDocumentationStatus("Error", error.message);
    } finally {
        button.disabled = false;
        button.style.opacity = "1";
    }
}

async function initDocumentationPage() {
    renderUser();

    const select = document.getElementById("documentationProjectSelect");
    const searchInput = document.getElementById("documentationSearchInput");
    const generateButton = document.getElementById("generateDocumentationBtn");

    select?.addEventListener("change", handleProjectChange);

    searchInput?.addEventListener("input", (event) => {
        renderProjectOptions(event.target.value || "");
    });

    generateButton?.addEventListener("click", handleGenerateDocumentation);

    try {
        state.projects = await fetchProjects();
        renderProjectOptions();
    } catch (error) {
        console.error(error);

        const select = document.getElementById("documentationProjectSelect");

        if (select) {
            select.innerHTML = `<option value="">Failed to load projects</option>`;
        }

        setDocumentationStatus("Error", error.message);
    }
}

initDocumentationPage();
