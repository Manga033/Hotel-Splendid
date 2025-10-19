var app = $.spapp({
    defaultView: "#home",
    templateDir: "frontend/views/"

});


app.run();

$(window).on('hashchange', () => {
  setTimeout(() => window.scrollTo({ top: 0, left: 0, behavior: 'auto' }), 0);
});

function setActiveNavFromHash() {
  const hash = location.hash || "#home";
  document.querySelectorAll(".header-nav-link.header-active")
    .forEach(a => a.classList.remove("header-active"));
  const current = document.querySelector(`.header-nav-link[href="${hash}"]`);
  if (current) current.classList.add("header-active");
}

window.addEventListener("load", setActiveNavFromHash);
window.addEventListener("hashchange", () => {
  setTimeout(setActiveNavFromHash, 0);
});