function centralizar() {
    const el = document.getElementById(location.hash.slice(1));
    if (!el) return;

    window.scrollTo({
        top: el.getBoundingClientRect().top + scrollY
            - (innerHeight - el.offsetHeight) / 2,
        behavior: 'smooth'
    });
}

window.addEventListener('load', () => setTimeout(centralizar, 50));
window.addEventListener('hashchange', centralizar);