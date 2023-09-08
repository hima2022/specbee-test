<?php

namespace Drupal\location\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Location & Time' block.
 *
 * @Block(
 *   id = "location_time_Block",
 *   admin_label = @Translation("Location & Time")
 * )
 */
class LocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Location Service variable.
   *
   * @var servicevariable
   */
  protected $locationService;

  /**
   * Construct.
   *
   * @param array $configuration
   *   Configuration variable.
   * @param string $plugin_id
   *   Plugin variable.
   * @param mixed $plugin_definition
   *   Definition Variable.
   * @param mixed $locationService
   *   Location service variable.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $locationService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->locationService = $locationService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('location.items.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $locDetails = $this->locationService->getLocation();
    $country = $city = $time = $totaltime = $date = '';
    if (!empty($locDetails)) {
      $country = $locDetails['country'];
      $city = $locDetails['city'];
      $totaltime = $locDetails['total_time'];
      $date = $locDetails['date'];
      $time = $locDetails['time'];
    }
    return [
      '#theme' => 'location_block',
      '#loc_country' => $country,
      '#loc_city' => $city,
      '#loc_total_time' => $totaltime,
      '#loc_date' => $date,
      '#loc_time' => $time,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(
          parent::getCacheContexts(),
          ['url.path', 'url.query_args', 'user', 'url.site']
    );
  }

}
