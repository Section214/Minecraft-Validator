<?php
/*
   Plugin Name: Minecraft Validator
   Description: Simple plugin to verify new WordPress accounts against the Minecraft user database. If the username doesn't show up as a valid Minecraft player, it won't let them register.
   Version: 1.4
   Author: Ghost1227
   Author URI: http://www.ghost1227.com
*/

/*
   Copyright 2011-2012 Daniel J Griffiths (Ghost1227)

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License
   as published by the Free Software Foundation; either version 3
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/* Run activation hook when plugin is activated */
register_activation_hook(__FILE__, 'get_wp_version');

/* Rewrite registration form */
function mcv_registration_form() {
	wp_enqueue_script( 'login_form', plugins_url() . '/minecraft-validator/usernamerewrite.js', array('jquery'), false, false );
}
add_action('login_head', 'mcv_registration_form');

/* Get WordPress version */
function get_wp_version() {
    global $wp_version;
    if ( version_compare ( $wp_version, '3.1', '<')) {
        exit ( "<div style='font-size: 13px; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', sans-serif;'><strong>Attention:</strong> This plugin will not work with your version of WordPress.</div>" );
    }
}

/* Register actions */
add_action('register_post', 'verify_mc_account', 10, 3);
add_action('admin_menu', 'add_mcval_options');

/* Check account on minecraft.net */
function verify_mc_account($login, $email, $errors) {
	$options = array(
        'timeout' => 5,
    );
    $mcacct = wp_remote_get('http://www.minecraft.net/haspaid.jsp?user='.rawurlencode($login), $options);
    $mcacct = $mcacct['body'];
		
    if ( $mcacct != 'true' ) {
        if ( $mcacct == 'false' ) {
            $errors->add('mc_error',__('<strong>ERROR:</strong> Minecraft account is invalid.'));
            return $errors;
        } else {
            $errors->add('mc_error',__('<strong>ERROR:</strong> Unable to contact minecraft.net.'));
            return $errors;
        }
        add_filter('registration_errors', 'verify_mc_account', 10, 3);
    }
}

/* Activation/Deactivation */
function set_mcval_options() {
    add_option('hide_me', 'false');
}

function unset_mcval_options() {
    delete_option('hide_me');
}

register_activation_hook(__FILE__, 'set_mcval_options');
register_deactivation_hook(__FILE__, 'unset_mcval_options');

/* Add admin menu */
function add_mcval_options() {
    if ( get_option('hide_me') != "true" ) {
        add_options_page('Minecraft Validator Options', 'Minecraft Validator', 8, 'mcval-options', 'mcval_options');
    }
}

/* Display options page */
function mcval_options() {

    ?>

    <div class="wrap">
        <h2>Minecraft Validator</h2>

    <?php
        if ( $_REQUEST['submit'] ) {
            update_mcval_options();
            ?>
                <script type="text/javascript">
                <!--
                    window.location = <?php echo "'options-general.php'"; ?>
                //-->
                </script>
            <?php
        }
        print_mcval_form();
    ?>

    </div>

<?php }

function update_mcval_options() {
    update_option( 'hide_me', 'true' );
}

function print_mcval_form() {
    ?>

    <style>
        <?php echo '#mcval_wrapper { background: url("' . plugins_url() . '/minecraft-validator/minecraft-validator.png");'; ?>
    </style>

    <style>
        #mcval_wrapper {
            width: 400px;
            height: 600px;
            margin: 0 auto;
        }
        #mcval_wrapper p {
            text-align: justify;
        }
        #mcval_inner {
            height: 368px;
            padding: 147px 44px 0px 44px;
        }
        #mcval_donate {
            float: left;
        }
        #mcval_submit {
            float: left;
        }
        #mcval_footer {
            height: 30px;
            margin-top: 31px;
            padding: 0 44px;
            text-align: center;
            font-size: 0.7em;
            font-weight: bold;
            line-height: 15px;
        }
        .mcval_header {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
        }
        input[type="submit"] {
            width: 157px;
            height: 36px;
            margin-left: 5px;
            margin-top: 0px;
        }
    </style>

    <div id="mcval_wrapper">
        <div id="mcval_inner">
            <div class="mcval_header">Welcome to Minecraft Validator!</div>
            <hr/>
            <p>My goal is to make this the ultimate all-around Minecraft
            plugin for WordPress, but to make that happen I need your help. If
            you have a thought for a new feature you want to see added, please
            <a href="http://wordpress.org/tags/minecraft-validator?forum_id=10#postform" target="_new">let me know</a>! 
            If you have a question or comment, <a href="http://wordpress.org/tags/minecraft-validator?forum_id=10#postform" target="_new">let me know</a>!
            If you appreciate the work I've put into this plugin, at the very
            least please take a moment to <a href="http://wordpress.org/extend/plugins/minecraft-validator/" target="_new">rate it</a>.</p>
            <p>If you really appreciate it, or are just feeling generous, I 
            would absolutely love to get a donation or two out of this (Who
            knows, I might even include donators on the thanks page and give 
            them early access to new versions or something. Assuming they
            have no objections of course.)</p>
            <p>Regardless, I sincerely hope that my contribution helps improve
            the quality of your site, as well as enriches both the Minecraft
            and WordPress communities.</p>
            <div id="mcval_donate">
                <a href='http://www.pledgie.com/campaigns/16297' target="_new"><img alt='Click here to lend your support to: Minecraft Validator WordPress Plugin and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/16297.png?skin_name=chrome' border='0' /></a>
            </div>
            <div id="mcval_submit">
                <form method="post">
                    <input type="submit" name="submit" value="Please hide this page!" />
                </form>
            </div>
        </div>
        <div id="mcval_footer">
            Copyright &copy; 2011 Daniel J Griffiths &lt;<a href="mailto:dgriffiths@ghost1227.com">dgriffiths@ghost1227.com</a>&gt;
            <br/>Released under the terms of the <a href="http://www.gnu.org/licenses/gpl.html" target="_new">GNU General Public License</a>
        </div>
    </div>

<?php }
