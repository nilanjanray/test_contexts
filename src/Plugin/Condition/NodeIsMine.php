<?php

/**
 * @file
 * Contains \Drupal\test_contexts\Plugin\Condition\NodeType.
 */

namespace Drupal\test_contexts\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManagerInterface;

/**
 * Provides a 'Node is Mine' condition.
 *
 * @Condition(
 *   id = "node_is_mine",
 *   label = @Translation("Node is Mine"),
 *   context = {
 *     "node" = @ContextDefinition(
 *       "entity:node",
 *        label = @Translation("Node"),
 *        description = @Translation("Detect if a node and a user share the same UID")
 *        ),
 *     "user" = @ContextDefinition(
 *       "entity:user",
 *       label = @Translation("Author"),
 *       description = @Translation("The user to compare with the node for ownership")
 *       )
 *   }
 * )
 *
 */
class NodeIsMine extends ConditionPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The entity storage.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Creates a new NodeType instance.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity storage.
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(EntityManagerInterface $entity_manager, array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('entity.manager'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }


  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    $node = $this->getContextValue('node');
    $user = $this->getContextValue('user');
    $node_author_id = $node->entity->uid->value;
    $user_uid = $user->id();
    return ($user_uid == $node_author_id);
  }
  
  /**
   * {@inheritdoc}
   */
  public function summary() {
    $this->t("just wing it!");
  }


}
