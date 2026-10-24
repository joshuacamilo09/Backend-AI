const user = window.BackendAIUser || {
    name: "User",
    email: "",
    initials: "U",
};

function loadTemplateIfExists() {
    const textarea = document.getElementById("backendDescription");

    const template = localStorage.getItem("backend_template");

    console.log("Template recebido:", template); // DEBUG

    if (template && textarea) {
        textarea.value = template;

        localStorage.removeItem("backend_template");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    loadTemplateIfExists();
});

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

function getCsrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
}

async function generateBackend(description) {
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

function setResult(status, html) {
    const badge = document.getElementById("generateStatusBadge");
    const result = document.getElementById("generateResult");

    if (badge) badge.textContent = status;
    if (result) result.innerHTML = html;
}

async function handleGenerate() {
    const textarea = document.getElementById("backendDescription");
    const button = document.getElementById("generateBackendSubmit");

    const description = textarea?.value?.trim();

    if (!description) {
        setResult(
            "Validation",
            '<span style="color:#f97316;">Please enter a project description first.</span>',
        );
        return;
    }

    button.disabled = true;
    button.style.opacity = "0.7";
    setResult(
        "Generating",
        '<span style="color:#a78bfa;">Generating backend, please wait...</span>',
    );

    try {
        const payload = await generateBackend(description);

        const projectName = escapeHtml(payload.project?.name || "New project");
        const framework = escapeHtml(payload.project?.framework || "laravel");
        const downloadUrl = payload.generation?.download_url || null;

        setResult(
            "Completed",
            `
        <div style="display:grid; gap:10px;">
          <div><strong style="color:#fff;">${projectName}</strong></div>
          <div style="color:#94a3b8;">Framework: ${framework}</div>
          <div style="color:#94a3b8;">Status: ${escapeHtml(payload.generation?.status || "completed")}</div>
          ${
              downloadUrl
                  ? `<div><a href="${downloadUrl}" class="view-all">Download ZIP →</a></div>`
                  : ""
          }
        </div>
      `,
        );

        textarea.value = "";
    } catch (error) {
        console.error(error);
        setResult(
            "Error",
            `<span style="color:#ef4444;">${escapeHtml(error.message)}</span>`,
        );
    } finally {
        button.disabled = false;
        button.style.opacity = "1";
    }
}

document
    .getElementById("generateBackendSubmit")
    ?.addEventListener("click", handleGenerate);

renderUser();
