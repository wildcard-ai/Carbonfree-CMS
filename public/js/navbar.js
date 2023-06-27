const toggleButton = document.querySelector("[data-toggle='collapse']");
let isTransitioning = false;

toggleButton.addEventListener("click", function () {
    if (isTransitioning) {
        return;
    }

    const targetId = toggleButton.getAttribute("data-target");
    const target = document.querySelector(`[data-id="${targetId}"]`);

    const isShown = target.classList.contains("show");

    if (!isShown) {
        expand(target);
    } else {
        collapse(target);
    }
});

function expand(el) {
    isTransitioning = true;

    el.classList.replace("collapse", "collapsing");
    el.style.height = el.scrollHeight + "px";

    function onTransitionEnd() {
        el.classList.replace("collapsing", "collapse");
        el.classList.add("show");
        el.style.height = null;
        el.removeEventListener("transitionend", onTransitionEnd);
        isTransitioning = false;
    }

    el.addEventListener("transitionend", onTransitionEnd);
}

function collapse(el) {
    isTransitioning = true;

    const expandedHeight = el.clientHeight + "px";
    el.style.height = expandedHeight;

    if (window.getComputedStyle(el).height === expandedHeight) {
        el.classList.remove("collapse", "show");
        el.classList.add("collapsing");
        el.style.height = null;
    }

    function onTransitionEnd() {
        el.classList.replace("collapsing", "collapse");
        el.removeEventListener("transitionend", onTransitionEnd);
        isTransitioning = false;
    }

    el.addEventListener("transitionend", onTransitionEnd);
}