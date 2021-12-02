The goal is to build a Drupal 8 module. It should:
  - Define a custom block that contains a HTML <button>.
  - This block should be placed on every node.
  - When a person clicks the button, make an AJAX request to an internal API
    endpoint (defined below) to retrieve the accessibility analysis for the
    page
    - There are 3 nodes provided in the test database
  - For the retrieved violations, please enumerate them by category and show
    the count per category below the HTML <button>
    - Style each category and count with CSS. Red = bad, green = good. A count
      less-than-or-equal to 2 is considered good. Feel free to use whatever
      hex / color name you'd like :)
  - Build a controller that provides an internal API endpoint for the AJAX
    request to consume. It should live at /api/accessibility
    - The controller should forward a call to this Google cloud function, it
      is available via HTTP at

      https://us-central1-api-project-30183362591.cloudfunctions.net/axe-puppeteer-test

    - It accepts a GET parameter of url={page_url}, so an example call would
      look like:

      https://us-central1-api-project-30183362591.cloudfunctions.net/axe-puppeteer-test?url=https://dev-tech-homework.pantheonsite.io/node/1

    - We realize this is odd to do for a local dev url, so please use

      https://dev-tech-homework.pantheonsite.io/node/1

    - The call should be made with an authentication header: x-tableau-auth
    - The value of the header should be:

    AOaxT3DBGfyXtR68PgFzcZma4bfzLeuLFaLuX9jGHC

    - Please make it so that the value of the header is configurable and can
      be updated by an admin user in a config form
