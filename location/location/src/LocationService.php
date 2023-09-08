<?php

namespace Drupal\location;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Defines an importer of location items.
 */
class LocationService {

  /**
   * The location.settings config object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs an Importer object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->config = $configFactory->get('location.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function getLocation() {
    $location = [];
    if (!empty($this->config->get('country'))) {
      $location['country'] = $this->config->get('country');
    }
    if (!empty($this->config->get('city'))) {
      $location['city'] = $this->config->get('city');
    }
    if (!empty($this->config->get('timezone'))) {
      $date = new DrupalDateTime();
      $date->setTimezone(new \DateTimeZone($this->config->get('timezone')));
      $location['time'] = $date->format('g:i a');
      $location['date'] = $date->format('l, d F Y');
      $location['total_time'] = $date->format('jS M Y - g:i A');
    }
    return $location;
  }

}
