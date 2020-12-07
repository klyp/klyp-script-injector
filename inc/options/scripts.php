<?php

// See if wordpress is properly installed
defined( 'ABSPATH' ) || die( 'Wordpress is not installed properly.' );
?>

<form method="post" action="<?= admin_url( 'options.php' ); ?>">
    <?php
        settings_fields(SCRIPT_GROUP);
        do_settings_sections(SCRIPT_GROUP);
    ?>
    <section>
        <div id="welcome-panel" class="welcome-panel">
            <h2 class="title">Head Section</h2>
            <label for="klyp_head">Your script will be injected inside &lt;head&gt;&lt;/head&gt;</label>
            <textarea name="klyp_head" id="klyp_head" class="large-text code" rows="10"><?= esc_attr(get_option('klyp_head')); ?></textarea>
        </div>
    </section>

    <section>
        <div id="welcome-panel" class="welcome-panel">
            <h2 class="title">Body Section</h2>
            <label for="klyp_body">Your script will be injected inside &lt;body&gt;&lt;/body&gt;</label>
            <textarea name="klyp_body" id="klyp_body" class="large-text code" rows="10"><?= esc_attr(get_option('klyp_body')); ?></textarea>
        </div>
    </section>

    <section>
        <div id="welcome-panel" class="welcome-panel">
            <h2 class="title">Footer Section</h2>
            <label for="klyp_footer">Your script will be injected on the footer</label>
            <textarea name="klyp_footer" id="klyp_footer" class="large-text code" rows="10"><?= esc_attr(get_option('klyp_footer')); ?></textarea>
        </div>
    </section>

    <?= submit_button(); ?>
</form>
