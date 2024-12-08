<?php

namespace Drupal\custom_paragraph_display\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Drupal\Core\Path\PathMatcher;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Event subscriber to alter paragraph display based on the path.
 */
class NodeParagraphSubscriber implements EventSubscriberInterface {

  /**
   * The path matcher service.
   *
   * @var \Drupal\Core\Path\PathMatcher
   */
  protected $pathMatcher;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a NodeParagraphSubscriber object.
   *
   * @param \Drupal\Core\Path\PathMatcher $path_matcher
   *   The path matcher service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(PathMatcher $path_matcher, EntityTypeManagerInterface $entity_type_manager) {
    $this->pathMatcher = $path_matcher;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::VIEW][] = ['onView', 10];
    return $events;
  }

  /**
   * Alters the node's rendered output to show specific paragraphs.
   *
   * @param \Symfony\Component\HttpKernel\Event\ViewEvent $event
   *   The view event.
   */
  public function onView(ViewEvent $event) {
    $route_match = \Drupal::service('current_route_match');
    $node = $route_match->getParameter('node');

    // Ensure the entity is a node and has a specific paragraph field.
    if ($node instanceof \Drupal\node\NodeInterface) {
      $path = \Drupal::service('path.current')->getPath();
      $field_name = 'field_paragraph_content'; // Replace with your actual paragraph field name.

      // Check if the node has the paragraph field.
      if ($node->hasField($field_name)) {
        $paragraphs = $node->get($field_name)->referencedEntities();

        // Define path-specific logic for displaying paragraphs.
        $specific_paragraphs = [];
        if ($this->pathMatcher->matchPath($path, '/special-path')) {
          // Example: Show only the first paragraph for '/special-path'.
          $specific_paragraphs[] = $paragraphs[0] ?? null;
        }
        elseif ($this->pathMatcher->matchPath($path, '/another-path/*')) {
          // Example: Show all paragraphs for '/another-path/*'.
          $specific_paragraphs = $paragraphs;
        }

        // Replace the field's content with filtered paragraphs.
        $rendered_paragraphs = [];
        foreach ($specific_paragraphs as $paragraph) {
          if ($paragraph) {
            $rendered_paragraphs[] = $this->entityTypeManager
              ->getViewBuilder('paragraph')
              ->view($paragraph, 'default'); // Use appropriate view mode.
          }
        }

        // Attach the rendered paragraphs to the node's field.
        $node->set($field_name, $rendered_paragraphs);
      }
    }
  }

}
