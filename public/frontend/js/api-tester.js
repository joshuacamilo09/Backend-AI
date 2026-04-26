const user = window.BackendAIUser || {
    name: "User",
    email: "",
    initials: "U",
};

const state = {
    projects: [],
    endpoints: [],
    selectedEndpoint: null,
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

function prettyJson(value) {
    return JSON.stringify(value, null, 2);
}

function parseJsonTextarea(id, fallback = {}) {
    const element = document.getElementById(id);
    const raw = element?.value?.trim();

    if (!raw) return fallback;

    try {
        return JSON.parse(raw);
    } catch {
        throw new Error(`Invalid JSON in ${id}.`);
    }
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

    if (!response.ok) {
        throw new Error("Failed to load projects.");
    }

    const payload = await response.json();

    return payload.data || [];
}

async function fetchEndpoints(projectId) {
    const response = await fetch(`/app-api/projects/${projectId}/endpoints`, {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
    });

    if (!response.ok) {
        throw new Error("Failed to load project endpoints.");
    }

    const payload = await response.json();

    return payload.data || [];
}

function renderProjectsSelect() {
    const select = document.getElementById("projectSelect");

    if (!select) return;

    if (!state.projects.length) {
        select.innerHTML = `<option value="">No projects found</option>`;
        return;
    }

    select.innerHTML = `
        <option value="">Select a project</option>
        ${state.projects
            .map(
                (project) =>
                    `<option value="${project.id}">${escapeHtml(project.name)}</option>`,
            )
            .join("")}
    `;
}

function endpointButtonClass(method) {
    const normalized = String(method || "").toUpperCase();

    if (normalized === "GET") return "endpoint-method-get";
    if (normalized === "POST") return "endpoint-method-post";
    if (normalized === "PUT" || normalized === "PATCH")
        return "endpoint-method-put";
    if (normalized === "DELETE") return "endpoint-method-delete";

    return "";
}

function renderEndpoints(filter = "") {
    const list = document.getElementById("endpointsList");
    const badge = document.getElementById("endpointsCountBadge");

    if (!list) return;

    const query = filter.toLowerCase();

    const filtered = state.endpoints.filter((endpoint) => {
        return (
            endpoint.name.toLowerCase().includes(query) ||
            endpoint.path.toLowerCase().includes(query) ||
            endpoint.method.toLowerCase().includes(query)
        );
    });

    if (badge) badge.textContent = String(filtered.length);

    if (!filtered.length) {
        list.innerHTML = `<p style="color:var(--muted); font-size:14px;">No endpoints found.</p>`;
        return;
    }

    list.innerHTML = filtered
        .map(
            (endpoint) => `
                <button
                    type="button"
                    class="endpoint-item"
                    data-endpoint-id="${endpoint.id}"
                    style="text-align:left; background:var(--surface-2); border:1px solid var(--border); border-radius:12px; padding:12px; color:var(--text); cursor:pointer;"
                >
                    <div style="display:flex; gap:8px; align-items:center; margin-bottom:6px;">
                        <span class="badge ${endpointButtonClass(endpoint.method)}">${escapeHtml(endpoint.method)}</span>
                        <strong style="font-size:13px;">${escapeHtml(endpoint.name)}</strong>
                    </div>
                    <div style="font-size:12px; color:var(--muted); font-family:monospace;">
                        ${escapeHtml(endpoint.path)}
                    </div>
                </button>
            `,
        )
        .join("");

    document.querySelectorAll(".endpoint-item").forEach((button) => {
        button.addEventListener("click", () => {
            const id = Number(button.dataset.endpointId);
            const endpoint = state.endpoints.find((item) => item.id === id);

            if (endpoint) {
                selectEndpoint(endpoint);
            }
        });
    });
}

function selectEndpoint(endpoint) {
    state.selectedEndpoint = endpoint;

    const methodInput = document.getElementById("methodInput");
    const pathInput = document.getElementById("pathInput");
    const bodyInput = document.getElementById("bodyInput");
    const selectedBadge = document.getElementById("selectedEndpointBadge");

    if (methodInput) methodInput.value = endpoint.method;
    if (pathInput) pathInput.value = endpoint.path;
    if (selectedBadge) selectedBadge.textContent = endpoint.name;

    if (bodyInput) {
        bodyInput.value = endpoint.sample_body
            ? prettyJson(endpoint.sample_body)
            : "{}";
    }

    setResponse(
        "Waiting",
        "Endpoint selected. Fill Base URL and click Send Request.",
    );
}

function setResponse(status, output) {
    const badge = document.getElementById("responseStatusBadge");
    const pre = document.getElementById("responseOutput");

    if (badge) badge.textContent = status;

    if (pre) {
        pre.textContent =
            typeof output === "string" ? output : prettyJson(output);
    }
}

async function handleProjectChange(event) {
    const projectId = event.target.value;

    state.endpoints = [];
    state.selectedEndpoint = null;

    renderEndpoints();

    if (!projectId) {
        return;
    }

    setResponse("Loading", "Loading endpoints...");

    try {
        state.endpoints = await fetchEndpoints(projectId);
        renderEndpoints(
            document.getElementById("apiTesterSearchInput")?.value || "",
        );
        setResponse("Waiting", "Select an endpoint to start testing.");
    } catch (error) {
        console.error(error);
        setResponse("Error", error.message);
    }
}

async function handleSendRequest() {
    const endpoint = state.selectedEndpoint;

    if (!endpoint) {
        setResponse("Error", "Please select an endpoint first.");
        return;
    }

    const baseUrl = document.getElementById("baseUrlInput")?.value?.trim();
    const path = document.getElementById("pathInput")?.value?.trim();
    const method = document.getElementById("methodInput")?.value?.trim();

    if (!baseUrl) {
        setResponse(
            "Error",
            "Please enter the Base URL of the running backend.",
        );
        return;
    }

    if (!path || !method) {
        setResponse("Error", "Endpoint method/path is missing.");
        return;
    }

    try {
        const headers = parseJsonTextarea("headersInput", {});
        const body = parseJsonTextarea("bodyInput", {});

        setResponse("Sending", "Sending request...");

        const response = await fetch("/app-api/endpoint-tests/run", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": getCsrfToken(),
            },
            credentials: "same-origin",
            body: JSON.stringify({
                endpoint_id: endpoint.id,
                base_url: baseUrl,
                method,
                path,
                headers,
                body,
            }),
        });

        const payload = await response.json();

        if (!response.ok) {
            throw new Error(
                payload.error || payload.message || "Request failed.",
            );
        }

        const status = payload.response?.status ?? "Unknown";
        const duration = payload.response?.duration_ms ?? "—";

        setResponse(`HTTP ${status} • ${duration}ms`, payload);
    } catch (error) {
        console.error(error);
        setResponse("Error", error.message);
    }
}

async function initApiTester() {
    renderUser();

    const projectSelect = document.getElementById("projectSelect");
    const searchInput = document.getElementById("apiTesterSearchInput");
    const sendButton = document.getElementById("sendRequestBtn");

    projectSelect?.addEventListener("change", handleProjectChange);

    searchInput?.addEventListener("input", () => {
        renderEndpoints(searchInput.value || "");
    });

    sendButton?.addEventListener("click", handleSendRequest);

    try {
        state.projects = await fetchProjects();
        renderProjectsSelect();
    } catch (error) {
        console.error(error);

        const select = document.getElementById("projectSelect");

        if (select) {
            select.innerHTML = `<option value="">Failed to load projects</option>`;
        }

        setResponse("Error", error.message);
    }
}

initApiTester();
