# Tableau Take Home Assessment

Take home assessment completed by Christopher Lemaire. A custom module that adds a custom block - retrieving accessibility analysis results for the given node via AJAX.

## Table of contents

- [Installation](#installation)
- [Notes](#notes)


## Installation

Feel free to install & enable the Tableau Take Home Assessment module using conventional means - either thru the admin UI via /admin/modules or using Drush, `drush en tableau`.


## Notes

The value of the authentication header is configurable and can be updated; settings can be accessed via /admin/tableau/settings. The internal API endpoint lives at /api/accessibility/{nodeID}.