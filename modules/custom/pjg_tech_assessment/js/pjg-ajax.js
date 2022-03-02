/**
 * $file
 * Fetch API information and display it via AJAX.
 */

(function ($, drupal){

  'use strict';

  /**
   * Get build url from root and parameters
   *
   * @param url
   *   URL for AJAX call.
   *
   * @returns {string}
   */
  const paintAjax = function (url) {

    let ajaxSettings = {
      url: url
    };

    let myAjaxObject = new Drupal.ajax(ajaxSettings);

    // Programmatically trigger the Ajax request.
     myAjaxObject.execute().done()
  };


  /**
   * Replaces content of ____ div with API response
   */
  Drupal.behaviors.FetchApiAjax = {
   attach: function (context) {

     // Prevent drupal behaviour being attached multiple times.
     if (context === document) {

       const root = window.location.origin;

       // Construct ajax links for search pager.
       $('#accessibility-button', context).once('pjg-accessibility-api').click(function (e) {
         e.preventDefault();
         paintAjax(root + '/api/accessibility')
       });
     }
   }
  };
})(jQuery, Drupal);


