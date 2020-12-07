<?php

// See if wordpress is properly installed
defined( 'ABSPATH' ) || die( 'Wordpress is not installed properly.' );

// Variables
$htaccess = ABSPATH . '.htaccess';
$startString = '# START OF KLYP SCRIPT INJECTOR';
$endString = '# END OF KLYP SCRIPT INJECTOR';

/**
 * Get access to htaccess
 * 
 * @param string
 * @return boolean
 */
function get_htaccess($htaccess = null) {
    if (! $htaccess) {
        display_message('nohtaccess');
        return false; 
    }

    // Open htaccess and attempt to create one
    if (!$f = @fopen($htaccess, 'a+')) {
        // Change permission
        @chmod($htaccess, 0644);
        // If still can't access/create display error
        if (!$f = @fopen($htaccess, 'a+')) {
            display_message('accesstohtaccess');
            return false;
        }
    }
    return true;
}

/**
 * Get htaccess file content
 * 
 * @param string
 * @return string
 */
function get_htaccess_content($htaccess = null) {
    if (! get_htaccess($htaccess)) { return false; }

    return $htcontent = file_get_contents($htaccess);
}

/**
 * Get htaccess redirect content
 * 
 * @param string
 * @param string
 * @param string
 * @return boolean|string
 */
function get_htaccess_redirects($htaccess = null, $startString, $endString) {
    if (! get_htaccess($htaccess)) { return false; }

    // Add a space to make sure the start of string is not at the start of file
    $htcontent = ' ' . get_htaccess_content($htaccess);
    $startOf = strpos($htcontent, $startString);

    if ($startOf == 0) {
        return false;
    }

    $startOf += strlen($startString);
    $lenOf = strpos($htcontent, $endString, $startOf) - $startOf;

    return substr($htcontent, $startOf, $lenOf);
}

/**
 * Get htaccess content into array
 * 
 * @param string
 * @param string
 * @param string
 * @return boolean|array
 */
function get_htaccess_into_array($htaccess = null, $startString, $endString) {
    if (! get_htaccess($htaccess)) { return false; }

    $htcontent = get_htaccess_redirects($htaccess, $startString, $endString);

    if (! empty($htcontent)) {
        return explode(PHP_EOL, trim($htcontent));
    }

    return false;
}

/**
 * Trim htaccess content
 * 
 * @param string
 * @return array;
 */
function trim_redirect($string = null) {
    if (! $string) { return false; }

    $redirects = explode(' ', $string);
    return array_slice($redirects, -2, 2, false);
}

/**
 * Display messages
 * 
 * @param string
 * @return void
 */
function display_message($type) {
    switch ($type) {
        case 'noredirects':
            echo '
            <div class="notice notice-info is-dismissible">
                <p>No redirects found.</p>
            </div>';
            break;

        case 'nohtaccess':
            echo '
            <div class="notice notice-error is-dismissible">
                <p>.htaccess file is not found. Make sure you have access to .htaccess file located on the root of your server.</p>
            </div>';
            break;

        case 'accesstohtaccess':
            echo '
            <div class="notice notice-error is-dismissible">
                <p>Unable to read .htaccess file. Make sure you have the right access permissions to .htaccess.</p>
            </div>';
            break;

        case 'error':
            echo '
            <div class="notice notice-error is-dismissible">
                <p>There was an error while saving redirects. Please try again. If the problem persists, please contact the administrator.</p>
            </div>';
            break;

        case 'success':
            echo '
            <div class="notice notice-success is-dismissible">
                <p>301 redirects have been updated.</p>
            </div>';
            break;
    }
}

/**
 * Save posted redirects
 * 
 * @param string
 * @param string
 * @param string
 * @return void
 */
function save_redirects($htaccess = null, $startString, $endString) {
    if (! get_htaccess($htaccess)) { return false; }

    // Get htaccess content
    $htcontent = get_htaccess_content($htaccess);
    // Remove everything between markers
    $htcontent = preg_replace('/' . $startString . '[\s\S]+?' . $endString . '/', '', $htcontent);

    // Start of redirect content
    $htredirecContent[] = $startString;

    // Get posts
    if (! isset($_POST['redirectFrom']) && ! isset($_POST['redirectTo'])) {
        display_message('success');
        return true;
    }

    $redirectFroms = $_POST['redirectFrom'];
    $redirectTos = $_POST['redirectTo'];

    // Generate redirects
    for($i = 0; $i < count($redirectFroms); $i++) {
        // Make sure from and to are set
        $redirectValidated = validate_redirects($redirectFroms[$i], $redirectTos[$i]);

        if ($redirectValidated) {
            $htredirecContent[] = 'Redirect 301 ' . $redirectValidated['from'] . ' ' . $redirectValidated['to'];
        }
    }
    // End of redirects
    $htredirecContent[] = $endString;
    $newRedirects = implode("\n", $htredirecContent);

    // Add redirects to top of file
    $htcontent = $newRedirects . $htcontent;

    // Write into htaccess
    $newHtaccess = fopen($htaccess, "w");
    fwrite($newHtaccess, $htcontent);

    if (fclose($newHtaccess)) {
        display_message('success');
        return true;
    }

    display_message('error');
    return false;
}

/**
 * Validate redirects
 * 
 * @param string
 * @param string
 * @return boolean|array
 */
function validate_redirects($from = null, $to = null) {
    if (! empty($from) && 
        ! empty($to) &&
        $from != $to) 
    {
        $redirect['from'] = $from;
        $redirect['to'] = $to;
        return $redirect;
    }

    return false;
}

// If POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    save_redirects($htaccess, $startString, $endString);
}

// Get all redirects
$htredirects = get_htaccess_into_array($htaccess, $startString, $endString);

// If there's no redirect created, display message
if (! is_array($htredirects)) {
    display_message('noredirects');
}
?>
<div id="welcome-panel" class="welcome-panel">
    <form method="post" action="">
        <h2 class="title">301 Redirects</h2>
        <table class="form-table klyp-htaccess">
            <tbody>
                <tr>
                    <th>From</th>
                    <th>To</th>
                </tr>
                <?php
                    if (is_array($htredirects)) {
                        $count = 0;
                        foreach ($htredirects as $htredirect) {
                            if (! empty($htredirect)) {
                                $count++;
                                $redirect = trim_redirect($htredirect);
                                echo '
                                <tr>
                                    <td style="padding: 5px;">
                                        <input type="checkbox" name="redirectItem"> <input type="text" name="redirectFrom[]" value="'. $redirect[0] . '" class="regular-text code" placeholder="http://">
                                    </td>
                                    <td style="padding: 5px;">
                                        <input type="text" name="redirectTo[]" value="'. $redirect[1] . '" class="regular-text code" placeholder="http://">
                                    </td>
                                </tr>';
                            }
                        }
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <input type="button" class="button button-primary add-row" value="Add Row">
                        <input type="button" class="button delete-row" value="Remove Selected">
                    </td>
                    <td>
                        <?= submit_button(); ?>
                    </td>
            </tfoot>
        </table>
    </form>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        // When add row button clicked
        jQuery('.add-row').on('click', function () {
            var markup = '<tr>\
                            <td style="padding: 5px;">\
                                <input type="checkbox" name="redirectItem"><input type="text" name="redirectFrom[]" value="" class="regular-text code" placeholder="http://">\
                            </td>\
                            <td style="padding: 5px;">\
                                <input type="text" name="redirectTo[]" value="" class="regular-text code" placeholder="http://">\
                            </td>\
                        </tr>';
            jQuery('table.klyp-htaccess tbody').append(markup);
        });

        // When remove button clickedd
        jQuery('.delete-row').on('click', function () {
            jQuery('table.klyp-htaccess tbody').find('input[name="redirectItem"]').each(function () {
                if (jQuery(this).is(':checked')) {
                    jQuery(this).parents('tr').remove();
                }
            });
        });

        // Disable space on keypress
        jQuery(document).on('input', '.regular-text', function () {
            jQuery(this).val(function (_, v) {
                return v.replace(/\s+/g, '');
            });
        });
    });
</script>
