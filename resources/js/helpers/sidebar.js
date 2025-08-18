export function initSidebarToggle() {
    const toggleBtn = document.getElementById("collapseToggle");
    const body = document.body;

    if (localStorage.getItem("sidebar-collapsed") === "true") {
        body.classList.add("collapsed");
    }

    if (toggleBtn) {
        toggleBtn.addEventListener("click", () => {
            const isCollapsed = body.classList.toggle("collapsed");
            localStorage.setItem("sidebar-collapsed", isCollapsed);
        });
    }
}
