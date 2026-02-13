/**
 * Elevator Theme - Main JavaScript
 *
 * UI helpers only. No WooCommerce business logic.
 * Custom WooCommerce JS will be added in dedicated files during later phases.
 *
 * @package elevator
 */

jQuery(document).ready(function ($) {

	/* =========================================================
	 * Mobile search popup toggle
	 * ========================================================= */
	$('#mobile-search-toggle').on('click', function () {
		$('.woo-search-popup').fadeIn(200);
	});

	$('.woo-search-popup__close').on('click', function () {
		$('.woo-search-popup').fadeOut(200);
	});

	// Close on outside click.
	$(document).on('click', function (e) {
		if (
			$(e.target).closest('.woo-search-popup__inner, #mobile-search-toggle')
				.length === 0
		) {
			$('.woo-search-popup').fadeOut(200);
		}
	});

	/* =========================================================
	 * WooCommerce product tabs — equal height panels
	 * ========================================================= */
	var maxTabHeight = 0;

	$('.woocommerce-Tabs-panel').each(function () {
		var panelHeight = $(this).outerHeight();
		if (panelHeight > maxTabHeight) {
			maxTabHeight = panelHeight;
		}
	});

	$('.woocommerce-Tabs-panel').css('min-height', maxTabHeight + 'px');

});

/**
 * Equal height for product loop items on archive pages.
 *
 * Runs outside jQuery.ready so it fires on window.load (images loaded).
 */
(function () {
	'use strict';

	function equalizeProductHeights() {
		var products = document.querySelectorAll(
			'.archive ul.products li.product a.woocommerce-LoopProduct-link'
		);

		if (!products.length) {
			products = document.querySelectorAll(
				'.archive ul.products li.product a[aria-label^="Visit product"]'
			);
		}

		if (!products.length) {
			return;
		}

		var maxHeight = 0;

		// Reset heights first to get accurate measurement.
		products.forEach(function (product) {
			product.style.height = '';
		});

		products.forEach(function (product) {
			if (product.offsetHeight > maxHeight) {
				maxHeight = product.offsetHeight;
			}
		});

		products.forEach(function (product) {
			product.style.height = maxHeight + 'px';
		});
	}

	window.addEventListener('load', function () {
		setTimeout(equalizeProductHeights, 100);
	});

	// Re-equalize on window resize.
	var resizeTimer;
	window.addEventListener('resize', function () {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(equalizeProductHeights, 250);
	});
})();