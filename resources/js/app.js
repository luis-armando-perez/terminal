import "./bootstrap";
import "./planificar";
import "./mostrarRutas"
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    sidebar.classList.toggle("-translate-x-full");
    overlay.classList.toggle("hidden");
}

document.querySelectorAll("#sidebar a").forEach((link) => {
    link.addEventListener("click", () => {
        if (window.innerWidth < 1024) toggleSidebar();
    });
});

document
    .getElementById("btnHamburger")
    .addEventListener("click", toggleSidebar);
document.getElementById("close").addEventListener("click", toggleSidebar);
