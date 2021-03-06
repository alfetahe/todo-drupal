<?php

namespace Drupal\todo_list\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Todo entity entities.
 */
class TodoEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
