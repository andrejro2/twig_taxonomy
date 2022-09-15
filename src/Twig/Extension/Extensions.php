<?php

namespace Drupal\twig_taxonomy\Twig\Extension;

use Twig\TwigFilter;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Twig\Extension\AbstractExtension;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Twig extension for the taxonomy terms.
 */
class Extensions extends AbstractExtension {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Extensions constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity Type Manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    $filters = [];
    $filters[] = new TwigFilter('parent_url', [
      $this,
      'parentUrl',
    ]);
    $filters[] = new TwigFilter('parent_name', [
      $this,
      'parentName',
    ]);
    return $filters;
  }

  /**
   * Returns parent's taxonomy term  url.
   * @code
   * {{term.id | parent_url }}
   * @endcode
   *
   * @param string $id
   *   Taxonomy term ID.
   *
   * @return string
   *   URL string.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function parentUrl(string $id): string {
    if (is_numeric($id)) {
      $parentElement = $this->entityTypeManager
        ->getStorage('taxonomy_term')
        ->loadParents($id);
      if ($parentElement != NULL) {
        foreach ($parentElement as $value) {
          $taxonomy_id = $value->id();
        }
        $options = ['absolute' => TRUE];
        return Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $taxonomy_id], $options)->toString();
      }
      else {
        $options = ['absolute' => TRUE];
        return Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $id], $options)->toString();
      }
    }
    else {
      return '';
    }
  }

  /**
   * Returns parent taxonomy name.
   * @code
   * {{term.id | parent_name }}
   * @endcode
   *
   * @param string $id
   *   Taxonomy ID.
   *
   * @return string
   *   Parent Taxonomy term name.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function parentName(string $id): string {
    $name = '';
    $parentElement = $this->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadParents($id);
    if ($parentElement != NULL) {
      foreach ($parentElement as $value) {
        $name = $value->getName();
      };
    }
    else {
      $name = Term::load($id)->getName();
    }
    return $name;
  }

}
