window.toggleSidebar = function () {
    const sidebarText = document.getElementById("sidebarText");
    const mainContent = document.getElementById("mainContent");

    if (!sidebarText || !mainContent) return;

    const isOpen = sidebarText.classList.contains("w-64");

    if (isOpen) {
        // CLOSE
        sidebarText.classList.remove("w-64");
        sidebarText.classList.add("w-0");

        mainContent.classList.remove("ml-[336px]");
        mainContent.classList.add("ml-[80px]");
    } else {
        // OPEN
        sidebarText.classList.remove("w-0");
        sidebarText.classList.add("w-64");

        mainContent.classList.remove("ml-[80px]");
        mainContent.classList.add("ml-[336px]");
    }
};
