<?php
if (is_active_sidebar('sidebar-news')) : ?>
    <aside id="sidebar" class="widget-area news-sidebar">
        <span class="sidebar-wrap">
        <?php dynamic_sidebar('sidebar-news'); ?>
        </span>
    </aside>
<?php endif; ?>