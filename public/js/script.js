document.addEventListener("DOMContentLoaded", function() {
    var toggleBtn = document.querySelector("[data-toggle-target='menu']");
    var item = document.querySelector("[data-toggle-id='menu']");

  toggleBtn.addEventListener("click", function() {
      if (item.classList.contains("show")) {
          item.classList.remove("show");
      } else {
          item.classList.add("show");
      }
  });
});