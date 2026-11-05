// Portfolio interactions: mobile menu, theme, validation and UI components.
document.addEventListener("DOMContentLoaded", () => {
  // 4. Hamburger menu toggle
  const menuButton = document.querySelector(".menu-toggle");
  const navLinks = document.querySelector(".nav-links");
  if (menuButton && navLinks) {
    menuButton.addEventListener("click", () => {
      const isOpen = navLinks.classList.toggle("open");
      menuButton.setAttribute("aria-expanded", String(isOpen));
      menuButton.textContent = isOpen ? "✕" : "☰";
    });
    navLinks.querySelectorAll("a").forEach(link => link.addEventListener("click", () => {
      navLinks.classList.remove("open");
      menuButton.setAttribute("aria-expanded", "false");
      menuButton.textContent = "☰";
    }));
  }

  // 7. Dark/light theme using CSS root variables
  const themeButtons = document.querySelectorAll(".theme-toggle");
  const savedTheme = localStorage.getItem("portfolio-theme");
  if (savedTheme === "dark") document.documentElement.dataset.theme = "dark";
  themeButtons.forEach(button => {
    const updateIcon = () => button.textContent = document.documentElement.dataset.theme === "dark" ? "☀" : "☾";
    updateIcon();
    button.addEventListener("click", () => {
      const dark = document.documentElement.dataset.theme !== "dark";
      if (dark) document.documentElement.dataset.theme = "dark";
      else delete document.documentElement.dataset.theme;
      localStorage.setItem("portfolio-theme", dark ? "dark" : "light");
      themeButtons.forEach(updateIcon);
    });
  });

  // 6. Client-side contact form validation
  const form = document.getElementById("contact-form");
  if (form) {
    form.addEventListener("submit", event => {
      event.preventDefault();
      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const message = document.getElementById("message").value.trim();
      const feedback = document.getElementById("form-feedback");
      const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      feedback.className = "form-feedback";
      if (!name || !email || !message) {
        feedback.textContent = "Please fill in your name, email and message.";
        feedback.classList.add("error");
      } else if (!validEmail) {
        feedback.textContent = "Please enter a valid email address.";
        feedback.classList.add("error");
      } else {
        feedback.textContent = "Thank you! Your details passed validation. (Demo form: no message was sent.)";
        feedback.classList.add("success");
        form.reset();
      }
    });
  }

  // 9. Smooth scrolling for same-page anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", event => {
      const target = document.querySelector(anchor.getAttribute("href"));
      if (target) {
        event.preventDefault();
        target.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    });
  });

  // 10. FAQ accordion
  document.querySelectorAll(".faq-question").forEach(question => {
    question.addEventListener("click", () => {
      const item = question.closest(".faq-item");
      const isOpen = item.classList.toggle("open");
      question.setAttribute("aria-expanded", String(isOpen));
      question.querySelector("span").textContent = isOpen ? "−" : "+";
    });
  });

  // 11. Testimonials slider using an array
  const testimonials = [
    { quote: "“Alex transformed our ideas into a clean and easy-to-use website.”", name: "Jordan Lee", role: "Small business owner" },
    { quote: "“The process was clear, friendly and focused on the details that matter.”", name: "Taylor Reed", role: "Creative founder" },
    { quote: "“A thoughtful design that works beautifully on both mobile and desktop.”", name: "Casey Patel", role: "Project collaborator" }
  ];
  let testimonialIndex = 0;
  const slide = document.getElementById("testimonial-slide");
  function showTestimonial(index) {
    if (!slide) return;
    slide.classList.add("fade");
    window.setTimeout(() => {
      const item = testimonials[index];
      slide.innerHTML = `<p class="quote">${item.quote}</p><h3>${item.name}</h3><span>${item.role}</span>`;
      slide.classList.remove("fade");
    }, 180);
  }
  document.getElementById("testimonial-prev")?.addEventListener("click", () => {
    testimonialIndex = (testimonialIndex - 1 + testimonials.length) % testimonials.length;
    showTestimonial(testimonialIndex);
  });
  document.getElementById("testimonial-next")?.addEventListener("click", () => {
    testimonialIndex = (testimonialIndex + 1) % testimonials.length;
    showTestimonial(testimonialIndex);
  });

  // 13. Back-to-top floating button
  const topButton = document.getElementById("back-to-top");
  if (topButton) {
    window.addEventListener("scroll", () => topButton.classList.toggle("visible", window.scrollY > 350));
    topButton.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));
  }

  // 15. Animate statistics when they enter the viewport
  const counters = document.querySelectorAll(".counter");
  if ("IntersectionObserver" in window && counters.length) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const element = entry.target;
        const target = Number(element.dataset.target);
        const duration = 1100;
        const start = performance.now();
        function update(now) {
          const progress = Math.min((now - start) / duration, 1);
          element.textContent = Math.floor(progress * target);
          if (progress < 1) requestAnimationFrame(update);
        }
        requestAnimationFrame(update);
        observer.unobserve(element);
      });
    }, { threshold: 0.45 });
    counters.forEach(counter => observer.observe(counter));
  } else {
    counters.forEach(counter => counter.textContent = counter.dataset.target);
  }

  // 17. Modal popup (available on any page with #open-modal and #portfolio-modal)
  const modal = document.getElementById("portfolio-modal");
  const openModal = document.getElementById("open-modal");
  const closeModal = modal?.querySelector(".modal-close");
  openModal?.addEventListener("click", () => modal.classList.add("show"));
  closeModal?.addEventListener("click", () => modal.classList.remove("show"));
  modal?.addEventListener("click", event => {
    if (event.target === modal) modal.classList.remove("show");
  });
  document.addEventListener("keydown", event => {
    if (event.key === "Escape") modal?.classList.remove("show");
  });

  // 19. Live project search filter
  const search = document.getElementById("project-search");
  const projectCards = document.querySelectorAll(".project-card");
  const noProjects = document.getElementById("no-projects");
  search?.addEventListener("input", () => {
    const term = search.value.toLowerCase().trim();
    let visibleCount = 0;
    projectCards.forEach(card => {
      const content = `${card.dataset.search} ${card.textContent}`.toLowerCase();
      const match = content.includes(term);
      card.hidden = !match;
      if (match) visibleCount++;
    });
    if (noProjects) noProjects.hidden = visibleCount !== 0;
  });

  // Footer year
  document.querySelectorAll(".year").forEach(node => node.textContent = new Date().getFullYear());
});
