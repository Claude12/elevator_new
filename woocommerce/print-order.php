<?php
/**
 * Template for printing WooCommerce order
 */

global $print_order;

if (!$print_order) {
    echo '<p>Invalid order.</p>';
    return;
}

$user_id = $print_order->get_user_id();
$company_name = get_field('company_name', 'user_' . $user_id);
$company_logo = get_field('company_logo', 'user_' . $user_id);

$billing_email = $print_order->get_billing_email();
$billing_phone = $print_order->get_billing_phone();
$payment_method = $print_order->get_payment_method_title();
$shipping_method = $print_order->get_shipping_method();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title>Print Order #<?php echo $print_order->get_order_number(); ?></title>
    <style>
        .header {
            display: flex;
            gap: 64px;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1140px;
            margin: auto;
        }

        .header .logo {
            max-width: 180px;
        }

        .header .company-info {
            text-align: right;
        }

        h1,
        h2 {
            margin-top: 40px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f8f8f8;
            color: #154ed3;
        }

        .addresses {
            display: flex;
            justify-content: space-between;
            gap: 40px;
        }

        .address-box {
            width: 48%;
        }

        .section {
            margin-top: 30px;
        }

        .print-button button {
            margin-top: 40px;
            background: #154ed3;
            color: #fff;
            text-transform: uppercase;
            padding: 13px 40px;
            border-radius: 20px;
            font-family: "Open Sans", sans-serif;
            font-weight: 800;
            display: inline-block;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s ease-in-out !important;
			cursor: pointer;
        }

        .footer-note {
            margin-top: 60px;
            text-align: center;
            font-style: italic;
            font-size: 20px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="container">
            <div class="header">
                <?php if ($company_logo): ?>
                    <div class="logo">
                        <img src="<?php echo esc_url($company_logo['url']); ?>" alt="<?php echo esc_attr($company_name); ?>"
                            style="max-width: 100%;">
                    </div>
                <?php endif; ?>
                <?php if ($company_name): ?>
                    <div class="company-info">
                        <h1><?php echo esc_html($company_name); ?></h1>
                    </div>
                <?php endif; ?>
            </div>

            <h1>Order #<?php echo $print_order->get_order_number(); ?></h1>
            <p><strong>Date:</strong> <?php echo wc_format_datetime($print_order->get_date_created()); ?></p>
            <p><strong>Status:</strong> <?php echo wc_get_order_status_name($print_order->get_status()); ?></p>

            <div class="section">
                <h2>Customer Details</h2>
                <p><strong>Name:</strong> <?php echo esc_html($print_order->get_formatted_billing_full_name()); ?></p>
                <p><strong>Email:</strong> <?php echo esc_html($billing_email); ?></p>
                <p><strong>Phone:</strong> <?php echo esc_html($billing_phone); ?></p>
                <p><strong>Payment Method:</strong> <?php echo esc_html($payment_method); ?></p>
                <p><strong>Shipping Method:</strong> <?php echo esc_html($shipping_method); ?></p>
            </div>

            <div class="addresses section">
                <div class="address-box">
                    <h2>Billing Address</h2>
                    <p><?php echo wp_kses_post($print_order->get_formatted_billing_address()); ?></p>
                </div>
                <div class="address-box">
                    <h2>Shipping Address</h2>
                    <p><?php echo wp_kses_post($print_order->get_formatted_shipping_address() ?: $print_order->get_formatted_billing_address()); ?>
                    </p>
                </div>
            </div>

            <div class="section">
                <h2>Order Items</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($print_order->get_items() as $item): ?>
                            <tr>
                                <td><?php echo esc_html($item->get_name()); ?></td>
                                <td><?php echo esc_html($item->get_quantity()); ?></td>
                                <td><?php echo wc_price($item->get_total()); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2>Order Summary</h2>
                <p><strong>Subtotal:</strong> <?php echo wc_price($print_order->get_subtotal()); ?></p>
                <?php if ($print_order->get_discount_total() > 0): ?>
                    <p><strong>Discount:</strong> -<?php echo wc_price($print_order->get_discount_total()); ?></p>
                <?php endif; ?>
                <p><strong>Shipping:</strong> <?php echo wc_price($print_order->get_shipping_total()); ?></p>
                <p><strong>Tax:</strong> <?php echo wc_price($print_order->get_total_tax()); ?></p>
                <p><strong>Total:</strong> <?php echo $print_order->get_formatted_order_total(); ?></p>
            </div>

            <?php if ($print_order->get_customer_note()): ?>
                <div class="section">
                    <h2>Customer Note</h2>
                    <p><?php echo esc_html($print_order->get_customer_note()); ?></p>
                </div>
            <?php endif; ?>

            <p class="print-button">
                <button onclick="window.print();">Print</button>
            </p>

            <p class="footer-note">
                Thank you for your order!
            </p>
        </div>
    </div>
</body>

</html>