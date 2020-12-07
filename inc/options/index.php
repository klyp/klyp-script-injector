<?php

// See if wordpress is properly installed
defined( 'ABSPATH' ) || die( 'Wordpress is not installed properly.' );

?>
<div class="wrap">
     
    <h2>Klyp Script Injector</h2>

    <?php
        if (isset($_GET['tab'])) {
            $active_tab = $_GET['tab'];
        } else {
            $active_tab = 'scripts';
        }
    ?>
     
    <h2 class="nav-tab-wrapper">
        <a href="?page=klyp-script-injector&tab=scripts" class="nav-tab <?php echo $active_tab == 'scripts' ? 'nav-tab-active' : ''; ?>">Inject Scripts</a>
        <a href="?page=klyp-script-injector&tab=htaccess" class="nav-tab <?php echo $active_tab == 'htaccess' ? 'nav-tab-active' : ''; ?>">.htaccess</a>
    </h2>
     
    <?php
        include( KLYP_SCRIPT_INJECTOR_INC_PATH . 'options/' . $active_tab . '.php' );
    ?>
</div>