(function ($, Drupal) {
  Drupal.behaviors.tableau_accessibility_block_init = {
    attach: function (context, settings) {
        accessibilityCheck($('.tableau-accessibility-block-content'));
    }
  };

  function accessibilityCheck($elem) {
    $elem.once('button').each(function(i) {
      let $button = $elem.find('button');
      $button.on('click', function(e) {
        e.preventDefault();
        $button.attr('disabled','disabled');
        let pathToCheck = window.location.pathname;
        $.ajax({
          url: '/api/accessibility?url=' + pathToCheck,
          cache: false,
        }).done(function(res) {
          let $responseData = $('<ul />', {
            'id' : 'js-tab-response-data-' + i,
            'class' : 'js-tab-data-list',
          });
          $elem.append($responseData);
          if(res.data && res.status == 200) {
            // output the results, if we could get them.
            // loop through the results and assign them classes depending on good/bad results per instructions
            // assign <li> elements with 'js-tab-success' class to make them green.
          } else {
            // no data, or API error
            let $dataError = $('<li />', {
              'class' : 'js-tab-error',
              'text' : 'Error getting data from the API',
            });
            $responseData.append($dataError);
          }
        });
        // not going to use fetch until I can get the API working, to be able to test properly.
        /*
        fetch('/api/accessibility?url=' + window.location.href, {
          method: "GET",
          headers: headers,
        }).then(res => {
          if(!res.ok) {
            $elem.attr('data-message','Network response was not ok.');
          } else {
            let $responseData = $('<ul />', {
              'id' : 'js-tab-response-data-' + i,
            });
            $elem.append($responseData);
            console.log(res);
          }
          //res.json();
        }).catch((error) => {
          console.error('Error: ', error);
        });
        */
      });
    });
  }

})(jQuery, Drupal);
