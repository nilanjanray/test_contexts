<?php

/**
 * @file
 * Contains \Drupal\test_contexts\ContextProvider\NodeAuthorContext.
 */

namespace Drupal\test_contexts\ContextProvider;

use Drupal\node\ContextProvider\NodeRouteContext;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Plugin\Context\Context;
use Drupal\Core\Plugin\Context\ContextDefinition;
use Drupal\Core\Plugin\Context\ContextProviderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\node\Entity\Node;

/**
 * Context provider that provides the author of the node being displayed.
 */
class NodeAuthorContext extends NodeRouteContext {

  /**
   * The user storage.
   *
   * @var \Drupal\user\UserStorageInterface
   */
  protected $userStorage;  

  /**
   * Constructs a new NodeRouteContext.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match object.
   */
  public function __construct(RouteMatchInterface $route_match, EntityManagerInterface $entity_manager) {
    parent::__construct($route_match);
    $this->userStorage = $entity_manager->getStorage('user');
  }

  /**
   * {@inheritdoc}
   */
  public function getRuntimeContexts(array $unqualified_context_ids) {
    $result = [];
    $context_definition = new ContextDefinition('entity:user', $this->t('Author'), FALSE);
    $value = NULL;
    if (($route_object = $this->routeMatch->getRouteObject()) && ($route_contexts = $route_object->getOption('parameters')) && isset($route_contexts['node'])) {
      if ($node = $this->routeMatch->getParameter('node')) {
        $uid = $node->uid->value;
        $value = $this->userStorage->load($uid);
      }
    }

    $cacheability = new CacheableMetadata();
    $cacheability->setCacheContexts(['route']);

    $context = new Context($context_definition, $value);
    $context->addCacheableDependency($cacheability);
    $result['user'] = $context;

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableContexts() {
    $context = new Context(new ContextDefinition('entity:user', $this->t('Author from URL')));
    return ['author' => $context];
  }
  
  
}
