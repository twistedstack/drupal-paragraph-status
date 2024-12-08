# drupal-paragraph-status

Drupal module for handling status codes in a paragraph component.

**Status:** `still developing`

Drupal module that shows a particular paragraph field based on the node path.



### Explanation of the Code

1. **Path Matching Logic**:
   - The `PathMatcher` service is used to determine which paragraphs to display based on the current path.

2. **Field Filtering**:
   - The code checks if the node has the paragraph field and retrieves referenced paragraphs.
   - Depending on the path, it filters or modifies the paragraphs before rendering.

3. **Rendering Paragraphs**:
   - The `entityTypeManager`'s `viewBuilder` is used to render the filtered paragraphs in the desired view mode.

---

### Enable the Module

1. Place the module in the `modules/custom` directory.
2. Enable it using Drush or the admin UI:

```bash
drush en custom_paragraph_display
drush cr
```

---

### Test the Module

1. Create or edit a node with the specified paragraph field (`field_paragraph_content`).
2. Visit the node's path (e.g., `/special-path` or `/another-path/some-page`).
3. Verify that the correct paragraph(s) are displayed based on the current path.

---

This approach dynamically alters the rendering of the paragraph field based on the current path, offering a flexible and scalable solution.
