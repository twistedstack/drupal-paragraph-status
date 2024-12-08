# drupal-paragraph-status

Drupal module for handling status codes in a paragraph component.

## The Code

**Path Matching Logic:**

The PathMatcher service is used to determine which paragraphs to display based on the current path.

**Field Filtering:**

The code checks if the node has the paragraph field and retrieves referenced paragraphs.
Depending on the path, it filters or modifies the paragraphs before rendering.

**Rendering Paragraphs:**

The entityTypeManager's viewBuilder is used to render the filtered paragraphs in the desired view mode.


drush en custom_paragraph_display
drush cr
