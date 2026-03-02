jQuery(document).ready(function ($) {
  // =============================================
  // Mobile Search Toggle
  // =============================================
  const toggleBtn = document.getElementById("hdr-mobile-search-toggle");
  const drawer = document.getElementById("hdr-search-drawer");
  const closeBtn = document.getElementById("hdr-search-drawer-close");
  const mobileInput = document.getElementById("hdr-search-mobile");

  if (!toggleBtn || !drawer) return;

  function openDrawer() {
    drawer.classList.add("is-open");
    drawer.setAttribute("aria-hidden", "false");
    toggleBtn.setAttribute("aria-expanded", "true");
    if (mobileInput) mobileInput.focus();
  }

  function closeDrawer() {
    drawer.classList.remove("is-open");
    drawer.setAttribute("aria-hidden", "true");
    toggleBtn.setAttribute("aria-expanded", "false");
  }

  toggleBtn.addEventListener("click", openDrawer);
  if (closeBtn) closeBtn.addEventListener("click", closeDrawer);

  // Close on backdrop click
  drawer.addEventListener("click", function (e) {
    if (e.target === drawer) closeDrawer();
  });

  // Close on Escape
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && drawer.classList.contains("is-open")) {
      closeDrawer();
    }
  });

  // =============================================
  // Variable Product: Price Display & Variation Events
  // =============================================
  const isVariable =
    $("form.variations_form").length > 0 ||
    $("body").hasClass("product-type-variable");

  if (isVariable) {
    $(".customized-section .price").text("Select Option");

    const $form = $("form.variations_form");

    $form.on("show_variation", function (e, v) {
      if (v && v.price_html) {
        $(".customized-section .price").html(v.price_html);
      }
    });

    $form.on("hide_variation", function () {
      $(".customized-section .price").text("Select Option");
    });
  }

  // =============================================
  // Tab Panel Height Equalization (after full load)
  // =============================================
  $(window).on("load", function () {
    var maxHeight = 0;

    $(".woocommerce-Tabs-panel").each(function () {
      var panelHeight = $(this).outerHeight();
      if (panelHeight > maxHeight) {
        maxHeight = panelHeight;
      }
    });

    $(".woocommerce-Tabs-panel").css("min-height", maxHeight + "px");
  });

  // =============================================
  // Replace "Shipping Address" with "Branch Address" in My Account
  // =============================================
  var $scope = $(".woocommerce-MyAccount-content");

  if ($scope.length) {
    // Replace all descendant text nodes recursively
    $scope
      .find("*")
      .addBack()
      .contents()
      .filter(function () {
        return this.nodeType === 3;
      })
      .each(function () {
        this.nodeValue = this.nodeValue
          .replace(/Shipping Address/g, "Branch Address")
          .replace(/shipping address/g, "branch address")
          .replace(/Shipping address/g, "Branch address");
      });

    // Replace button text specifically
    $scope
      .find(".wc-address-book-add-shipping-button")
      .text(function (_, text) {
        return text
          .replace(/Shipping Address Book/g, "Branch Address Book")
          .replace(/Shipping Address/g, "Branch Address");
      });
  }
});
