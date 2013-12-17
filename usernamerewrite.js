jQuery(document).ready(function ($) {
'use strict';
/*global document: false */
/*global $, jQuery */
    $('label').each(
        function () {
            $(this).html($(this).html().replace('Username', 'Minecraft Username'));
        }
    );
    $('.message').each(
        function () {
            $(this).html($(this).html().replace('username', 'Minecraft username'));
        }
    );
});