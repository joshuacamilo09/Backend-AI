const docsUser = window.BackendAIUser || {
    name: "User",
    email: "",
    initials: "U",
};

const docs = [
    {
        title: "Getting Started",
        description:
            "Learn how to create your first backend generation with BackendAI.",
        href: "/generate-backend",
    },
    {
        title: "Prompt Guide",
        description:
            "Best practices for writing effective backend descriptions.",
        href: "/generate-backend",
    },
    {
        title: "Framework Support",
        description: "Supported frameworks and their configuration options.",
        href: "/documentation#framework-support",
    },
    {
        title: "API Reference",
        description: "Complete reference for the BackendAI generation API.",
        href: "/documentation#api-reference",
    },
    {
        title: "Authentication",
        description: "Understanding generated authentication systems.",
        href: "/documentation#authentication",
    },
    {
        title: "Deployment",
        description: "How to deploy your generated backend to production.",
        href: "/documentation#deployment",
    },
];

function renderDocsUser() {
    const avatar = document.getElementById("userAvatar");
    const userName = document.getElementById("userName");
    const userRole = document.getElementById("userRole");

    if (avatar) avatar.textContent = docsUser.initials;
    if (userName) userName.textContent = docsUser.name;
    if (userRole) userRole.textContent = "Pro Workspace";
}

function escapeHtml(value) {
    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

function renderDocs(filter = "") {
    const grid = document.getElementById("docsGrid");
    if (!grid) return;

    const q = filter.trim().toLowerCase();

    const filtered = docs.filter((doc) => {
        return (
            doc.title.toLowerCase().includes(q) ||
            doc.description.toLowerCase().includes(q)
        );
    });

    grid.innerHTML = filtered
        .map(
            (doc) => `
    <a href="${doc.href}" class="doc-card">
      <div class="doc-card-top">
        <div class="doc-card-icon">
          <img src="/frontend/assets/icons/docs.png" alt="">
        </div>
        <div class="doc-card-external">↗</div>
      </div>

      <h3 class="doc-card-title">${escapeHtml(doc.title)}</h3>
      <p class="doc-card-description">${escapeHtml(doc.description)}</p>
    </a>
  `,
        )
        .join("");
}

renderDocsUser();
renderDocs();

document
    .getElementById("docsSearchInput")
    ?.addEventListener("input", (event) => {
        renderDocs(event.target.value || "");
    });
