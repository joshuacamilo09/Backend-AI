/* app.js — BackendAI Landing Page */

const navbar = document.getElementById("navbar");
if (navbar) {
    window.addEventListener("scroll", () => {
        if (window.scrollY > 20) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });
}

const hamburger = document.getElementById("hamburger");
const navLinks = document.getElementById("navLinks");

if (hamburger && navLinks) {
    hamburger.addEventListener("click", () => {
        hamburger.classList.toggle("open");
        navLinks.classList.toggle("open");
    });

    navLinks.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", () => {
            hamburger.classList.remove("open");
            navLinks.classList.remove("open");
        });
    });
}

const observerOptions = { threshold: 0.12, rootMargin: "0px 0px -40px 0px" };

const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            revealObserver.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll(".about-card").forEach((card, i) => {
    card.style.setProperty("--delay", `${i * 0.08}s`);
    revealObserver.observe(card);
});

document.querySelectorAll(".feature-item").forEach((item, i) => {
    item.style.setProperty("--index", i);
    revealObserver.observe(item);
});

document.querySelectorAll("[data-animate]").forEach((el) => {
    revealObserver.observe(el);
});

const sections = document.querySelectorAll("section[id]");
const navItems = document.querySelectorAll(".nav-links a");

const sectionObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute("id");
                navItems.forEach((a) => {
                    a.style.color =
                        a.getAttribute("href") === `#${id}`
                            ? "var(--text-primary)"
                            : "";
                });
            }
        });
    },
    { threshold: 0.4 },
);

sections.forEach((s) => sectionObserver.observe(s));

const form = document.getElementById("contactForm");
const formSuccess = document.getElementById("formSuccess");

if (form && formSuccess) {
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        btn.textContent = "Sending…";
        btn.disabled = true;

        setTimeout(() => {
            btn.textContent = "Send Message →";
            btn.disabled = false;
            form.reset();
            formSuccess.classList.add("show");
            setTimeout(() => formSuccess.classList.remove("show"), 4000);
        }, 1200);
    });
}

document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        const href = this.getAttribute("href");
        if (!href || href === "#") return;

        const target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            const offset = 72;
            const top =
                target.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({ top, behavior: "smooth" });
        }
    });
});

const chartObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.querySelectorAll(".bar").forEach((bar, i) => {
                    bar.style.transition = `height 0.6s ease ${i * 0.08}s`;
                });
                chartObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.5 },
);

const chartEl = document.querySelector(".mock-chart");
if (chartEl) chartObserver.observe(chartEl);
