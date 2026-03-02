document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector(".sidebar");
    const closeBtn = document.querySelector("#btn");
    const logoLink = document.querySelector("#logo-link");
    const sidebarOverlay = document.querySelector("#sidebar-overlay");
    const navLinks = document.querySelectorAll(".nav-list li a");

    let sidebarState = localStorage.getItem("sidebarState") === "open";

    function loadSidebarState() {
        if (sidebar) {
            sidebarState = sidebar.classList.contains("open");
            updateMenuIcon();
        }
    }

    function toggleSidebar() {
        if (!sidebar) return;

        const isOpen = sidebar.classList.contains("open");

        if (isOpen) {
            sidebar.classList.remove("open");
            document.documentElement.classList.remove("sidebar-is-open");
            sidebarState = false;
        } else {
            sidebar.classList.add("open");
            document.documentElement.classList.add("sidebar-is-open");
            sidebarState = true;
        }

        updateMenuIcon();

        if (window.innerWidth > 1200) {
            localStorage.setItem(
                "sidebarState",
                sidebarState ? "open" : "closed",
            );
        }

        handleMobileOverlay();
    }

    function updateMenuIcon() {
        if (!sidebar) return;

        const isOpen = sidebar.classList.contains("open");
        const btn = document.querySelector("#btn");

        if (btn) {
            const icon = btn.querySelector("i");
            if (icon) {
                if (isOpen) {
                    icon.classList.replace("bx-menu", "bx-menu-alt-right");
                } else {
                    icon.classList.replace("bx-menu-alt-right", "bx-menu");
                }
            }
        }
    }

    function handleMobileOverlay() {
        const overlay = document.getElementById("sidebar-overlay");
        if (!overlay || !sidebar) return;

        if (window.innerWidth <= 1200) {
            if (sidebar.classList.contains("open")) {
                overlay.classList.add("active");
                document.body.style.overflow = "hidden";
            } else {
                overlay.classList.remove("active");
                document.body.style.overflow = "";
            }
        }
    }

    loadSidebarState();

    if (closeBtn) {
        closeBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            toggleSidebar();
        });
    }

    if (logoLink) {
        logoLink.addEventListener("click", (e) => {
            const targetUrl = logoLink.getAttribute("data-href");
            if (targetUrl && targetUrl !== "#") {
                e.preventDefault();
                window.location.href = targetUrl;
            }
        });
    }

    navLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            if (this.classList.contains("dropdown-toggle")) {
                e.preventDefault();
                e.stopPropagation();
                const parent = this.parentElement;
                parent.classList.toggle("show");
                
                dropdownItems.forEach((otherItem) => {
                    if (otherItem !== parent) {
                        otherItem.classList.remove("show");
                    }
                });
                return;
            }

            navLinks.forEach((l) => l.classList.remove("active"));
            this.classList.add("active");

            const submenu = this.closest(".submenu");
            if (submenu) {
                const parentToggle = submenu.parentElement.querySelector(".dropdown-toggle");
                if (parentToggle) {
                    navLinks.forEach((l) => {
                        if (l !== this) l.classList.remove("active");
                    });
                    parentToggle.classList.add("active");
                }
            }

            if (window.innerWidth <= 1200) {
                if (sidebar) sidebar.classList.remove("open");
                if (sidebarOverlay) sidebarOverlay.classList.remove("active");
                document.body.style.overflow = "";
                updateMenuIcon();
            }
        });
    });

    // Removed the separate document/sidebar listener that closes dropdowns on outside click
    // to satisfy the requirement: "pertahankan agar tetap terbuka meskipun saya mengklik 
    // area lain di halaman (cegah penutupan otomatis saat outside click)."


    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", () => {
            if (sidebar) sidebar.classList.remove("open");
            sidebarOverlay.classList.remove("active");
            document.body.style.overflow = "";
            updateMenuIcon();
        });
    }

    const logoutBtn = document.querySelector("#log_out");
    const logoutModal = document.querySelector("#logout-modal");
    const cancelLogout = document.querySelector("#cancel-logout");
    const confirmLogout = document.querySelector("#confirm-logout");

    if (logoutBtn) {
        logoutBtn.addEventListener("click", (e) => {
            e.preventDefault();
            if (logoutModal) logoutModal.classList.add("show");
        });
    }

    if (cancelLogout) {
        cancelLogout.addEventListener("click", () => {
            if (logoutModal) logoutModal.classList.remove("show");
        });
    }

    if (logoutModal) {
        logoutModal.addEventListener("click", (e) => {
            if (e.target === logoutModal) {
                logoutModal.classList.remove("show");
            }
        });
    }

    if (confirmLogout) {
        confirmLogout.addEventListener("click", () => {
            const logoutForm = document.getElementById("logout-form-hidden");
            if (logoutForm) {
                confirmLogout.innerHTML =
                    '<i class="bx bx-loader-alt bx-spin"></i>';
                confirmLogout.style.pointerEvents = "none";
                confirmLogout.style.opacity = "0.8";

                logoutForm.submit();
            } else {
                console.error("Logout form not found");
            }
        });
    }

    window.addEventListener("resize", function () {
        if (window.innerWidth > 1200) {
            const overlay = document.getElementById("sidebar-overlay");
            if (overlay) overlay.classList.remove("active");
            document.body.style.overflow = "";
        } else {
            handleMobileOverlay();
        }
    });
});
