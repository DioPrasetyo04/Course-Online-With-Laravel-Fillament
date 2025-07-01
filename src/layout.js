const hamburger = document.getElementById("hamburger");
const mobileMenu = document.getElementById("mobile-menu");
const overlay = document.getElementById("overlay");
const closeBtn = document.getElementById("close-menu");
const profileBtn = document.getElementById("profile-dropdown");
const profileMenu = document.getElementById("profile-menu-dropdown");
const profileMobileBtn = document.getElementById("profile-mobile-dropdown");
const profileMobileMenu = document.getElementById(
  "profile-menu-mobile-dropdown"
);

hamburger.addEventListener("click", () => {
  mobileMenu.classList.remove("translate-x-full");
  overlay.classList.remove("hidden");
  closeBtn.classList.remove("hidden");
});

closeBtn.addEventListener("click", () => {
  mobileMenu.classList.add("translate-x-full");
  overlay.classList.add("hidden");
});

profileBtn.addEventListener("click", () => {
  profileMenu.classList.toggle("hidden");
});

profileMobileBtn.addEventListener("click", () => {
  profileMobileMenu.classList.toggle("hidden");
});

document.addEventListener("click", (e) => {
  if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
    profileMenu.classList.add("hidden");
  }
});
