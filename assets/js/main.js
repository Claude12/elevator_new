jQuery(document).ready(function ($) {
  $("#mobile-search-toggle").on("click", function () {
    $(".woo-search-popup").fadeIn(200);
  });

  $(".woo-search-popup__close").on("click", function () {
    $(".woo-search-popup").fadeOut(200);
  });

  // Optional: Close on outside click
  $(document).on("click", function (e) {
    if (
      $(e.target).closest(".woo-search-popup__inner, #mobile-search-toggle")
        .length === 0
    ) {
      $(".woo-search-popup").fadeOut(200);
    }
  });

  // --- Move woovr-variation-price into main p.price ---
  var mainPriceContainer = $(".customized-section .price"); // Top price inside customized section
  var variationForm = $("form.variations_form");

  variationForm.on("change", "select", function () {
    setTimeout(function () {
      var variationPrice = $(".woovr-variation-price").html();
      if (variationPrice && variationPrice.trim() !== "") {
        mainPriceContainer.html(variationPrice);
      }
    }, 100);
  });

  // Find the tallest tab panel height
  var maxHeight = 0;

  $(".woocommerce-Tabs-panel").each(function () {
    var panelHeight = $(this).outerHeight();
    if (panelHeight > maxHeight) {
      maxHeight = panelHeight;
    }
  });

  // Apply min-height to all panels
  $(".woocommerce-Tabs-panel").css("min-height", maxHeight + "px");

  //replace technical information on product tabs
  $("#tab-title-custom_tab_1 a").text("Technical Information");

  $(".woovr-variation-select option").each(function () {
    var text = $(this).text();

    // Match format: "Product Name – Attribute" or "Product Name - Attribute"
    var match = text.match(/[-–]\s*(.+)$/);

    if (match && match[1]) {
      $(this).text(match[1].trim());
    }
  });

  const isVariable =
    $("form.variations_form").length > 0 ||
    $("body").hasClass("product-type-variable");

  if (!isVariable) return; // Let the plugin fully control simple/grouped/etc.

  // DO NOT hide native price nodes; let plugin/WC render as needed
  // $(".summary .price, .single_variation .price").hide();  // ← remove

  // Optional helper text in your custom area (doesn't affect plugin)
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

  //select first option by default
  // const $select = $('.variations select');
  // const firstValidOption = $select.find('option').not('[value=""]').first();
  // $select.val(firstValidOption.val()).trigger('change');

  // Replace all variations of "Shipping Address" text
  const $scope = $(".woocommerce-MyAccount-content"); // Limit to My Account section

  $scope
    .contents()
    .filter(function () {
      return this.nodeType === 3 && /shipping address/i.test(this.nodeValue);
    })
    .each(function () {
      this.nodeValue = this.nodeValue
        .replace(/Shipping Address/g, "Branch Address")
        .replace(/shipping address/g, "branch address")
        .replace(/Shipping address/g, "Branch address");
    });

  $scope.find(".wc-address-book-add-shipping-button").text(function (_, text) {
    return text.replace(/Shipping Address/g, "Branch Address");
  });

  $scope
    .find(".wc-address-book-add-shipping-button.disabled")
    .text(function (_, text) {
      return text.replace(/Shipping Address Book/g, "Branch Address Book");
    });

  $scope.find("h3, h2, a").each(function () {
    const $el = $(this);
    $el.text($el.text().replace(/Shipping Address/g, "Branch Address"));
    $el.text($el.text().replace(/Shipping address/g, "Branch address"));
  });

  // Custom Products List in My Account

  // Add to Custom List
  $(".cpl-add-btn").on("click", function (e) {
    e.preventDefault();
    var product_id = $(this).data("product-id");
    var button = $(this);

    $.post(
      cpl_ajax.url,
      {
        action: "cpl_add_product",
        product_id: product_id,
      },
      function (response) {
        if (response.success) {
          // Remove old notices if any
          $(".woocommerce-notices-wrapper").remove();

          // Create WooCommerce-style notice
          var notice = `
                    <div class="woocommerce-notices-wrapper">
                        <div class="woocommerce-message" role="alert" tabindex="-1">
                            Product has been added to your Custom List.
                            <a href="/my-account/custom-products/" class="button wc-forward">View List</a>
                        </div>
                    </div>
                `;

          // Insert notice before product summary or at top
          $(".product").prepend(notice);

          // Update button state
          button.text("Added").prop("disabled", true);
        }
      },
    );
  });

  /**
   * Equal height for product loop items on archive pages.
   */
  (function () {
    "use strict";

    function equalizeProductHeights() {
      var products = document.querySelectorAll(
        ".archive ul.products li.product a.woocommerce-LoopProduct-link",
      );

      if (!products.length) {
        products = document.querySelectorAll(
          '.archive ul.products li.product a[aria-label^="Visit product"]',
        );
      }

      if (!products.length) {
        return;
      }

      var maxHeight = 0;

      // Reset heights first to get accurate measurement.
      products.forEach(function (product) {
        product.style.height = "";
      });

      products.forEach(function (product) {
        if (product.offsetHeight > maxHeight) {
          maxHeight = product.offsetHeight;
        }
      });

      products.forEach(function (product) {
        product.style.height = maxHeight + "px";
      });
    }

    window.addEventListener("load", function () {
      setTimeout(equalizeProductHeights, 100);
    });

    // Re-equalize on window resize.
    var resizeTimer;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(equalizeProductHeights, 250);
    });
  })();

  // Remove from Custom List
  $(document).on("click", ".cpl-remove", function (e) {
    e.preventDefault();
    var product_id = $(this).data("product-id");
    var li = $(this).closest("li");

    $.post(
      cpl_ajax.url,
      {
        action: "cpl_remove_product",
        product_id: product_id,
      },
      function (response) {
        if (response.success) {
          li.fadeOut(300, function () {
            $(this).remove();
          });
        }
      },
    );
  });
});
