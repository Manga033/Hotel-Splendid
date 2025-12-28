(() => {
  const loader = document.getElementById('loader');
  const scrollBar = document.getElementsByClassName('scroll-bar')[0];
  
  window.addEventListener('load', () => {
    if (loader) {
      loader.classList.add('none');
    }
    if (scrollBar) {
      scrollBar.classList.remove('scroll-bar');
    }
  });
})();

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