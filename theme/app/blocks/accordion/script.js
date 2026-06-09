import "./style.css";

const initAccordions = () => {
  const accordions = document.querySelectorAll(".wp-block-nf-accordion");

  accordions.forEach((accordion) => {
    const items = [
      ...accordion.querySelectorAll(".wp-block-nf-accordion-item"),
    ];
    const jqAccordion = window.jQuery?.(accordion);
    const pluginInstance = jqAccordion?.data("SlideAccordion");

    if (pluginInstance?.destroy) {
      pluginInstance.destroy();
    }

    const setExpanded = (item, expanded) => {
      const opener = item.querySelector(".accordion-opener");
      if (opener) {
        opener.setAttribute("aria-expanded", expanded ? "true" : "false");
      }
    };

    const finishClose = (item, slide) => {
      item.classList.remove("slide-open");
      slide.classList.add("js-acc-hidden");
      slide.style.display = "none";
      slide.style.height = "0px";
      slide.style.overflow = "hidden";
      setExpanded(item, false);
    };

    const finishOpen = (item, slide) => {
      item.classList.add("slide-open");
      slide.classList.remove("js-acc-hidden");
      slide.style.display = "block";
      slide.style.height = "auto";
      slide.style.overflow = "visible";
      setExpanded(item, true);
    };

    const animateClose = (item) => {
      const slide = item.querySelector(".accordion-slide");
      if (
        !slide ||
        item.dataset.accordionState === "closing" ||
        !item.classList.contains("slide-open")
      ) {
        return;
      }

      item.dataset.accordionState = "closing";
      slide.classList.remove("js-acc-hidden");
      slide.style.display = "block";
      slide.style.overflow = "hidden";
      slide.style.height = `${slide.scrollHeight}px`;
      setExpanded(item, false);

      requestAnimationFrame(() => {
        item.classList.remove("slide-open");
        slide.style.height = "0px";
      });

      const onEnd = (event) => {
        if (event.target !== slide || event.propertyName !== "height") {
          return;
        }

        slide.removeEventListener("transitionend", onEnd);
        delete item.dataset.accordionState;
        finishClose(item, slide);
      };

      slide.addEventListener("transitionend", onEnd);
    };

    const animateOpen = (item) => {
      const slide = item.querySelector(".accordion-slide");
      if (
        !slide ||
        item.dataset.accordionState === "opening" ||
        item.classList.contains("slide-open")
      ) {
        return;
      }

      item.dataset.accordionState = "opening";
      items.forEach((otherItem) => {
        if (otherItem !== item) {
          animateClose(otherItem);
        }
      });

      item.classList.add("slide-open");
      slide.classList.remove("js-acc-hidden");
      slide.style.display = "block";
      slide.style.overflow = "hidden";
      slide.style.height = "0px";
      setExpanded(item, true);

      requestAnimationFrame(() => {
        slide.style.height = `${slide.scrollHeight}px`;
      });

      const onEnd = (event) => {
        if (event.target !== slide || event.propertyName !== "height") {
          return;
        }

        slide.removeEventListener("transitionend", onEnd);
        delete item.dataset.accordionState;
        finishOpen(item, slide);
      };

      slide.addEventListener("transitionend", onEnd);
    };

    items.forEach((item) => {
      const opener = item.querySelector(".accordion-opener");
      const slide = item.querySelector(".accordion-slide");

      if (!opener || !slide) {
        return;
      }

      opener.setAttribute("role", "button");
      opener.setAttribute("tabindex", "0");

      if (item.classList.contains("slide-open")) {
        finishOpen(item, slide);
      } else {
        finishClose(item, slide);
      }

      if (opener.dataset.accordionBound === "true") {
        return;
      }

      opener.dataset.accordionBound = "true";

      const toggle = () => {
        if (item.classList.contains("slide-open")) {
          animateClose(item);
        } else {
          animateOpen(item);
        }
      };

      opener.addEventListener("click", (event) => {
        event.preventDefault();
        toggle();
      });

      opener.addEventListener("keydown", (event) => {
        if (event.key === "Enter" || event.key === " ") {
          event.preventDefault();
          toggle();
        }
      });
    });
  });
};

document.addEventListener("DOMContentLoaded", initAccordions);
window.addEventListener("load", () => {
  window.setTimeout(initAccordions, 0);
});
