export function hideLoader() {
    const loader = document.getElementById("loader");
    if (loader) loader.style.display = "none";
}

export function fadeInBody() {
    document.body.classList.add("fade-in");
}
