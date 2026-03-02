<?php
if (is_active_sidebar('primary-sidebar')) : ?>
    <aside id="sidebar" class="widget-area">
        <span class="sidebar-wrap">
        <?php dynamic_sidebar('primary-sidebar'); ?>
        </span>
    </aside>
<?php endif; ?>