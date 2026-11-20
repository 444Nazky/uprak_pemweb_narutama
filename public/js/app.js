(function () {
    "use strict";

    var toggle = document.querySelector("[data-nav-toggle]");
    var menu = document.getElementById("nav-mobile");

    if (toggle && menu) {
        toggle.addEventListener("click", function () {
            var open = menu.classList.toggle("is-open");
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
        });
    }

    document.querySelectorAll("[data-flash-close]").forEach(function (button) {
        button.addEventListener("click", function () {
            var flash = button.closest(".ka-flash");
            if (flash) {
                flash.remove();
            }
        });
    });

    var input = document.querySelector("[data-photo-input]");
    var preview = document.querySelector("[data-photo-preview]");

    if (input && preview) {
        input.addEventListener("change", function () {
            var file = input.files && input.files[0];
            if (!file) {
                preview.hidden = true;
                return;
            }

            var reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.hidden = false;
            };
            reader.readAsDataURL(file);
        });
    }
})();
