document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector(".admin-sidebar");
    const toggleBtn = document.createElement("button");

    toggleBtn.textContent = "Toggle Menu";
    toggleBtn.style.margin = "10px 0";
    toggleBtn.style.padding = "10px";
    toggleBtn.style.border = "none";
    toggleBtn.style.backgroundColor = "#003e9a";
    toggleBtn.style.color = "#fff";
    toggleBtn.style.borderRadius = "5px";
    toggleBtn.style.cursor = "pointer";

    sidebar.prepend(toggleBtn);

    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("collapsed");
        sidebar.style.width = sidebar.classList.contains("collapsed") ? "60px" : "250px";
    });
});
