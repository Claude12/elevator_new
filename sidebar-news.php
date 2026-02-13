<?php
if (is_active_sidebar('news-sidebar')) : ?>
    <aside id="sidebar" class="widget-area news-sidebar">
        <span class="sidebar-wrap">
        <?php dynamic_sidebar('news-sidebar'); ?>
        </span>
    </aside>
<?php endif; ?>