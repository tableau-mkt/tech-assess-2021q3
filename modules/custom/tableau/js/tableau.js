/**
 * @file
 * Tableau.js - AJAX functionality
 */

(function(Drupal, $, drupalSettings) {

    'use strict';

    /**
   * Main function to handle AJAX functionality.
   * @type {Drupal~behavior}
   */
    Drupal.behaviors.tableauAjax = {
        attach: function(context) {
            // On button click, make an AJAX request to an internal API endpoint.
            $('.js-ajax-button').once().bind('click', function(e) {
                e.preventDefault();
                // show spinner.
                $('.spinner__container').show();
                // clear table.
                $('.a-tableau__table tr').remove();
                // ajax call to api/accessibility.
                $.ajax({
                    url: drupalSettings.path.baseUrl + 'api/accessibility/' + drupalSettings.nodeId,
                    type: 'get',
                    dataType: "json",
                    success: function(result) {
                        // hide spinner.
                        $('.spinner__container').hide();
                        // loop thru violations.
                        $.each(result.data.violations, function(i, val) { 
                            // set pass/fail classes.               
                            if (Object.keys(val.nodes).length <= 2) {
                                var class_name = "pass";
                            } else {
                                var class_name = "fail";
                            }
                            // build table elements with data.
                            $('<tr>').html('<td class="' + class_name + '">' + val.id + '</td><td class="' + class_name + '">' + Object.keys(val.nodes).length + '</td>').appendTo('.a-tableau__table');
                        });
                    },
                });
            });
        }
    };
})(Drupal, jQuery, drupalSettings);
