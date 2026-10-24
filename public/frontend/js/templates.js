const templatesData = [
    // já tinhas
    {
        key: "rest",
        title: "REST API Starter",
        description: "Basic CRUD structure with authentication and validation.",
    },
    {
        key: "saas",
        title: "SaaS Starter",
        description: "Users, subscriptions, billing structure and admin panel.",
    },
    {
        key: "microservice",
        title: "Microservice API",
        description: "Lightweight architecture ready for scaling services.",
    },

    // novos (simples)
    {
        key: "todo",
        title: "Todo App API",
        description: "Simple task management with users and tasks.",
    },
    {
        key: "blog",
        title: "Blog API",
        description: "Posts, comments and user authentication.",
    },
    {
        key: "ecommerce",
        title: "E-commerce API",
        description: "Products, cart and orders system.",
    },
    {
        key: "notes",
        title: "Notes API",
        description: "Create and manage personal notes.",
    },
    {
        key: "auth",
        title: "Auth System",
        description: "Authentication system with login and register.",
    },
    {
        key: "chat",
        title: "Chat Backend",
        description: "Basic messaging system between users.",
    },
    {
        key: "inventory",
        title: "Inventory System",
        description: "Manage products and stock levels.",
    },
    {
        key: "booking",
        title: "Booking System",
        description: "Reservations and scheduling system.",
    },
    {
        key: "school",
        title: "School Management",
        description: "Students, teachers and classes system.",
    },
    {
        key: "fitness",
        title: "Fitness Tracker API",
        description: "Track workouts and user progress.",
    },
];

const templates = {
    rest: `I need a REST API with:
- Authentication (JWT)
- CRUD for users
- CRUD for products
- Validation and pagination
- API documentation`,

    saas: `I need a SaaS backend with:
- User authentication
- Subscription system
- Billing structure
- Admin panel
- Multi-tenant architecture`,

    microservice: `I need a microservice architecture with:
- Independent services
- API Gateway
- Service communication
- Scalable structure
- Logging and monitoring`,

    todo: `I need a simple todo app backend with:
- Users authentication
- Tasks CRUD
- Each user has their own tasks
- Mark tasks as completed`,

    blog: `I need a blog backend with:
- Users authentication
- Posts CRUD
- Comments on posts
- Like system`,

    ecommerce: `I need an e-commerce backend with:
- Products CRUD
- Categories
- Shopping cart
- Orders system
- User authentication`,

    notes: `I need a notes app backend with:
- User authentication
- Notes CRUD
- Private notes per user`,

    auth: `I need an authentication system with:
- Register and login
- Password reset
- Email verification
- JWT or session auth`,

    chat: `I need a chat backend with:
- Users
- Conversations
- Messages between users
- Basic real-time structure`,

    inventory: `I need an inventory management backend with:
- Products CRUD
- Stock tracking
- Categories
- Low stock alerts`,

    booking: `I need a booking system backend with:
- Users
- Reservations
- Available time slots
- Booking management`,

    school: `I need a school management backend with:
- Students
- Teachers
- Classes
- Enrollments`,

    fitness: `I need a fitness tracker backend with:
- Users
- Workouts
- Exercises
- Progress tracking`,
};

function renderTemplates() {
    const grid = document.getElementById("templatesGrid");

    grid.innerHTML = templatesData
        .map(
            (template) => `
        <div class="card template-card">

            <div class="card-header">
                <h3>${template.title}</h3>
                <span class="badge">Template</span>
            </div>

            <p class="template-desc">
                ${template.description}
            </p>

            <div class="template-actions">
                <button
                    class="primary use-template"
                    data-template="${template.key}"
                >
                    Use Template
                </button>
            </div>

        </div>
    `,
        )
        .join("");

    // eventos
    document.querySelectorAll(".use-template").forEach((btn) => {
        btn.addEventListener("click", () => {
            const key = btn.dataset.template;
            const templateText = templates[key];

            if (!templateText) return;

            localStorage.setItem("backend_template", templateText);
            window.location.href = "/generate-backend";
        });
    });
}

renderTemplates();
