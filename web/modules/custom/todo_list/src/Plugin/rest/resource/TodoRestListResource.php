<?php

namespace Drupal\todo_list\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Http\RequestStack;
use Drupal\Core\Session\AccountProxy;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "todo_rest_resource",
 *   label = @Translation("Todo rest resource list"),
 *   uri_paths = {
 *     "canonical" = "/api/todo-list/all",
 *   }
 * )
 */
class TodoRestListResource extends ResourceBase {

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface|mixed|object
   */
  private $todoStorage;

  /**
   * @var \Symfony\Component\HttpFoundation\Request|null
   */
  private ?\Symfony\Component\HttpFoundation\Request $request;

  /**
   * TodoRestListResource constructor.
   *
   * @param  array  $configuration
   * @param  string  $plugin_id
   * @param $plugin_definition
   * @param  array  $serializer_formats
   * @param  \Psr\Log\LoggerInterface  $logger
   * @param  \Drupal\Core\Session\AccountProxy  $accountProxy
   * @param  \Drupal\Core\Entity\EntityTypeManager  $entityTypeManager
   * @param  \Drupal\Core\Http\RequestStack  $requestStack
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(
    array $configuration,
    string $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxy $accountProxy,
    EntityTypeManager $entityTypeManager,
    RequestStack $requestStack
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->currentUser = $accountProxy;
    $this->todoStorage = $entityTypeManager->getStorage('todo_entity');
    $this->request = $requestStack->getCurrentRequest();
  }

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('request_stack')
    );
  }

  /**
   * Responds to GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get() {
    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    return new ResourceResponse($this->getTodoItems());
  }

  /**
   * @return array
   */
  protected function getTodoItems() {
    $todoItemsIds = $this->todoStorage
      ->getQuery()
      ->condition('status', '1')
      ->execute();

    $todoItems = $this->todoStorage
      ->loadMultiple($todoItemsIds);

    $formattedTodoItems = [];
    foreach ($todoItems as $todoItem) {
      /*** @var \Drupal\todo_list\Entity\TodoEntity $todoItem */

      $formattedTodoItems[] = [
        'id' => $todoItem->id(),
        'name' => $todoItem->getName(),
        'description' => $todoItem->getDescription(),
        'due_date' => $todoItem->getDueDate()
      ];
    }

    return $formattedTodoItems;
  }

}
