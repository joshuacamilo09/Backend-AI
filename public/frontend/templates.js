const templateUser = window.BackendAIUser || {
    name: "User",
    email: "",
    initials: "U",
};

const templates = [
    {
        name: "E-Commerce API",
        description:
            "Full e-commerce backend with products, orders, payments and user management.",
        framework: "Laravel",
        entities: 8,
        href: "/generate-backend?template=ecommerce-api",
    },
    {
        name: "Blog Platform",
        description:
            "Blog system with posts, categories, comments and authentication.",
        framework: "Laravel",
        entities: 5,
        href: "/generate-backend?template=blog-platform",
    },
    {
        name: "Task Manager",
        description:
            "Project management with tasks, assignments and team collaboration.",
        framework: "Laravel",
        entities: 4,
        href: "/generate-backend?template=task-manager",
    },
    {
        name: "CRM System",
        description:
            "Customer relationship management with contacts, deals and pipelines.",
        framework: "Laravel",
        entities: 12,
        href: "/generate-backend?template=crm-system",
    },
    {
        name: "SaaS Starter",
        description:
            "Multi-tenant SaaS boilerplate with subscriptions and billing.",
        framework: "Laravel",
        entities: 6,
        href: "/generate-backend?template=saas-starter",
    },
    {
        name: "Chat Application",
        description:
            "Real-time messaging with channels, messages and user presence.",
        framework: "Laravel",
        entities: 6,
        href: "/generate-backend?template=chat-application",
    },
];

function renderTemplateUser() {
    const avatar = document.getElementById("userAvatar");
    const userName = document.getElementById("userName");
    const userRole = document.getElementById("userRole");

    if (avatar) avatar.textContent = templateUser.initials;
    if (userName) userName.textContent = templateUser.name;
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

function renderTemplates(filter = "") {
    const grid = document.getElementById("templatesGrid");
    if (!grid) return;

    const q = filter.trim().toLowerCase();

    const filtered = templates.filter((template) => {
        return (
            template.name.toLowerCase().includes(q) ||
            template.description.toLowerCase().includes(q) ||
            template.framework.toLowerCase().includes(q)
        );
    });

    grid.innerHTML = filtered
        .map(
            (template) => `
    <article class="doc-card template-card">
      <div class="doc-card-top">
        <div class="doc-card-icon">
          <img src="/frontend/assets/icons/folder.png" alt="">
        </div>
        <span class="doc-card-tag">${escapeHtml(template.framework)}</span>
      </div>

      <h3 class="doc-card-title">${escapeHtml(template.name)}</h3>
      <p class="doc-card-description">${escapeHtml(template.description)}</p>

      <div class="template-card-bottom">
        <span class="template-entities">${template.entities} entities</span>
        <a href="${template.href}" class="doc-card-link">
          <img src="/frontend/assets/icons/wand stars.png" alt="">
          Use template
        </a>
      </div>
    </article>
  `,
        )
        .join("");
}

renderTemplateUser();
renderTemplates();

document
    .getElementById("templatesSearchInput")
    ?.addEventListener("input", (event) => {
        renderTemplates(event.target.value || "");
    });
