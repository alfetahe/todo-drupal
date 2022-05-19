<?php

namespace Drupal\todo_list\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Todo entity entities.
 *
 * @ingroup todo_list
 */
interface TodoEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Todo entity name.
   *
   * @return string
   *   Name of the Todo entity.
   */
  public function getName();

  /**
   * Sets the Todo entity name.
   *
   * @param string $name
   *   The Todo entity name.
   *
   * @return \Drupal\todo_list\Entity\TodoEntityInterface
   *   The called Todo entity entity.
   */
  public function setName($name);

  /**
   * Gets the Todo entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Todo entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Todo entity creation timestamp.
   *
   * @param int $timestamp
   *   The Todo entity creation timestamp.
   *
   * @return \Drupal\todo_list\Entity\TodoEntityInterface
   *   The called Todo entity entity.
   */
  public function setCreatedTime($timestamp);

}
