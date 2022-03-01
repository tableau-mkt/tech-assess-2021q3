This module, pjg_tech_assessment, adds a button to the ___ block that will access the ____ function to analyze the
current pages accessibility.

####HOW DOES IT WORK?
--------------------------------

This module does not have any configuration associated with it, so once
the module is installed, you will need to do some minor configuration.

The block associated with the pjg_tech_assessment module is the Accessibility Api Block. When creating the module, I
placed the block in the Sidebar first region. When users click the "Check Accessibility" button, a javascript function
makes a call to the /api/accessibility route. The Ajax Controller then uses guzzle to trigger the ___ function and
return the page's accessibility information. The accessibility information is formatted and stripped of unnecessary
information and returned as an ajax response. The ajax response then inserts a twig template file that displays the
accessibility information.

Per instructions, the header authorization can be updated at: /admin/config/pjg-tech-assessment/admin-settings. It's set
to the default value of AOaxT3DBGfyXtR68PgFzcZma4bfzLeuLFaLuX9jGHC if it is not changed.

####WHAT WOULD I IMPROVE WITH ADDITIONAL TIME?
--------------------------------
At the moment, there is not much error handling. I would certainly update that so that there's better monitoring as to
when/how the ajax function is falling over.

The http trigger for the ___ function is rather slow. I would update the guzzle request to be asynchronous so
that other functionality does not halt while we wait for a response.

A throbber would also be a nice touch to communicate that the button has been clicked but is awaiting a response.

I would also look into modifying the Accessibility Api block into an ajax form instead of a custom javascript ajax
function.
