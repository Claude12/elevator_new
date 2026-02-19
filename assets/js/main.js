jQuery(document).ready(function ($) {

  // =============================================
  // Mobile Search Toggle
  // =============================================
  $("#mobile-search-toggle").on("click", function () {
    $(".woo-search-popup").fadeIn(200);
  });

  $(".woo-search-popup__close").on("click", function () {
    $(".woo-search-popup").fadeOut(200);
  });

  $(document).on("click", function (e) {
    if (
      $(e.target).closest(".woo-search-popup__inner, #mobile-search-toggle")
        .length === 0
    ) {
      $(".woo-search-popup").fadeOut(200);
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