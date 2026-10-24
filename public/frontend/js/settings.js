const settingsUser = window.BackendAIUser || {
    name: "User",
    email: "",
    initials: "U",
};

document.getElementById("userAvatar").textContent = settingsUser.initials;
document.getElementById("userName").textContent = settingsUser.name;
document.getElementById("userRole").textContent = settingsUser.email;
