(() => {
  "use strict";

  const root = document.documentElement;
  const storageKey = "tower-exchange-theme";

  function init() {
    if (root.dataset.interactionsReady === "true") return;
    root.dataset.interactionsReady = "true";

    const variant = root.dataset.variant || "1";
    const assetBase = root.dataset.assetBase || "../assets";
    const themeColors = {
      "1": { light: "#fbfaf7", dark: "#08090b" },
      "2": { light: "#f2efe7", dark: "#08090b" },
      "3": { light: "#f4ecdd", dark: "#181411" },
      "4": { light: "#f4f3ed", dark: "#090a0c" },
    };

    const toggles = [...document.querySelectorAll(".theme-toggle")];
    const themeMeta = document.querySelector('meta[name="theme-color"]');
    const favicon = document.querySelector('link[rel~="icon"]');
    const themeLogos = document.querySelectorAll(
      ".brand__mark img, .hero-art__logo img",
    );
    const logoUrl = (theme) =>
      `${assetBase}/brand/tower-${theme}.png`;

    function applyTheme(theme, persist = true) {
      if (theme !== "light" && theme !== "dark") return;

      root.dataset.theme = theme;
      root.style.colorScheme = theme;

      toggles.forEach((toggle) => {
        const nextTheme = theme === "light" ? "темну" : "світлу";
        toggle.dataset.currentTheme = theme;
        toggle.setAttribute("aria-checked", String(theme === "dark"));
        toggle.setAttribute("aria-label", `Увімкнути ${nextTheme} тему`);
        toggle.title = `Увімкнути ${nextTheme} тему`;
      });

      themeLogos.forEach((image) => {
        image.src = logoUrl(theme);
      });

      if (favicon) favicon.href = logoUrl(theme);
      if (themeMeta) {
        themeMeta.content =
          (themeColors[variant] || themeColors["1"])[theme];
      }

      if (persist) {
        try {
          localStorage.setItem(storageKey, theme);
        } catch {
          // Direct file opening may block storage; theme switching still works.
        }
      }
    }

    applyTheme(root.dataset.theme === "dark" ? "dark" : "light", false);

    toggles.forEach((toggle) => {
      toggle.addEventListener("click", () => {
        applyTheme(root.dataset.theme === "dark" ? "light" : "dark");
      });
    });

    window.addEventListener("storage", (event) => {
      if (
        event.key === storageKey &&
        (event.newValue === "light" || event.newValue === "dark")
      ) {
        applyTheme(event.newValue, false);
      }
    });

    const menuButton = document.querySelector(".menu-button");
    const mobileMenu = document.querySelector("#mobile-menu");

    if (menuButton && mobileMenu) {
      const menuIcon = menuButton.querySelector("svg");

      const setMenu = (open, restoreFocus = false) => {
        mobileMenu.classList.toggle("is-open", open);
        mobileMenu.setAttribute("aria-hidden", String(!open));
        mobileMenu.inert = !open;
        menuButton.setAttribute("aria-expanded", String(open));
        menuButton.setAttribute(
          "aria-label",
          open ? "Закрити меню" : "Відкрити меню",
        );

        if (menuIcon) {
          menuIcon.innerHTML = open
            ? '<path d="m6 6 12 12"></path><path d="m18 6-12 12"></path>'
            : '<path d="M4 7h16M4 12h16M4 17h16"></path>';
        }

        if (restoreFocus) menuButton.focus();
      };

      setMenu(false);

      menuButton.addEventListener("click", () => {
        setMenu(menuButton.getAttribute("aria-expanded") !== "true");
      });

      mobileMenu.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", () => setMenu(false));
      });

      document.addEventListener("keydown", (event) => {
        if (
          event.key === "Escape" &&
          menuButton.getAttribute("aria-expanded") === "true"
        ) {
          setMenu(false, true);
        }
      });

      const desktopQuery = window.matchMedia("(min-width: 901px)");
      const closeDesktopMenu = (event) => {
        if (event.matches) setMenu(false);
      };

      if (typeof desktopQuery.addEventListener === "function") {
        desktopQuery.addEventListener("change", closeDesktopMenu);
      } else {
        desktopQuery.addListener(closeDesktopMenu);
      }
    }

    const calculator = document.querySelector('[data-feature="calculator"]');

    if (calculator) {
      const directionButtons = [
        ...calculator.querySelectorAll(".direction-switch button"),
      ];
      const cryptoButton = directionButtons[0];
      const cashButton = directionButtons[1];
      const swapButton = calculator.querySelector(".swap-button");
      const amountInput = calculator.querySelector(".amount-field input");
      const fromUnit = calculator.querySelector(
        ".amount-field:not(.amount-field--result) .amount-field__control strong",
      );
      const toUnit = calculator.querySelector(
        ".amount-field--result .amount-field__control strong",
      );
      const telegramLink = calculator.querySelector(".calculator-action a");
      const managerUrl = telegramLink
        ? telegramLink.href.split("?")[0]
        : "https://t.me/towerexchange_kyiv";
      let cryptoToCash = true;

      function updateCalculator() {
        const from = cryptoToCash ? "USDT" : "Готівка";
        const to = cryptoToCash ? "Готівка" : "USDT";

        cryptoButton?.classList.toggle("is-active", cryptoToCash);
        cashButton?.classList.toggle("is-active", !cryptoToCash);
        cryptoButton?.setAttribute("aria-pressed", String(cryptoToCash));
        cashButton?.setAttribute("aria-pressed", String(!cryptoToCash));

        if (fromUnit) fromUnit.textContent = from;
        if (toUnit) toUnit.textContent = to;
        amountInput?.setAttribute("aria-label", `Сума: ${from}`);

        if (telegramLink) {
          const direction = cryptoToCash
            ? "USDT → готівка"
            : "Готівка → USDT";
          const amount = amountInput?.value.trim();
          const amountCopy = amount ? `, сума ${amount}` : "";
          const url = new URL(managerUrl);
          url.searchParams.set(
            "text",
            `Вітаю! Хочу уточнити розрахунок: ${direction}${amountCopy}.`,
          );
          telegramLink.href = url.href;
        }
      }

      cryptoButton?.addEventListener("click", () => {
        cryptoToCash = true;
        updateCalculator();
      });

      cashButton?.addEventListener("click", () => {
        cryptoToCash = false;
        updateCalculator();
      });

      swapButton?.addEventListener("click", () => {
        cryptoToCash = !cryptoToCash;
        updateCalculator();
      });

      amountInput?.addEventListener("input", updateCalculator);
      updateCalculator();
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init, { once: true });
  } else {
    init();
  }
})();
