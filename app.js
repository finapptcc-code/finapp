document.addEventListener("DOMContentLoaded", () => {
    const menu = document.getElementById("mobileMenu");
    const sidebar = document.getElementById("sidebar");
    if (menu && sidebar) {
        menu.addEventListener("click", () => sidebar.classList.toggle("open"));
    }

    document.querySelectorAll(".password-toggle").forEach(btn => {
        btn.addEventListener("click", () => {
            const input = document.getElementById(btn.dataset.target);
            if (!input) return;
            input.type = input.type === "password" ? "text" : "password";
            btn.classList.toggle("fa-eye");
            btn.classList.toggle("fa-eye-slash");
        });
    });

    document.querySelectorAll(".auto-dismiss").forEach(el => {
        setTimeout(() => el.remove(), 4500);
    });
});
