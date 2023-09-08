<?php

namespace Drupal\location\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Language settings for this site.
 */
class LocationSettingsForm extends ConfigFormBase {

  /**
   * Location settings Variable.
   *
   * @var string
   * Config settings
   */
  const SETTINGS = 'location.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'location_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config(static::SETTINGS);

    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $config->get('city'),
    ];

    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#options' => [
        'America/Chicago' => $this->t('America/Chicago'),
        'America/New_York' => $this->t('America/New_York'),
        'Asia/Tokyo' => $this->t('Asia/Tokyo'),
        'Asia/Dubai' => $this->t('Asia/Dubai'),
        'Asia/Kolkata' => $this->t('Asia/Kolkata'),
        'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
        'Europe/Oslo' => $this->t('Europe/Oslo'),
        'Europe/London' => $this->t('Europe/London'),
      ],
      '#default_value' => $config->get('timezone'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $conf = $this->configFactory->getEditable(static::SETTINGS);
    $conf->set('country', $form_state->getValue('country'));
    $conf->set('city', $form_state->getValue('city'));
    $conf->set('timezone', $form_state->getValue('timezone'));
    $conf->save();

    parent::submitForm($form, $form_state);
  }

}
