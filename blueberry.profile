<?php

/**
 * @file
 * Enables modules and site configuration for a Blueberry site installation.
 */

/**
 * Implements hook_install_tasks().
 */
function blueberry_install_tasks(&$install_state) {

  $tasks = [];

  if (empty($install_state['config_install_path'])) {

    $tasks['blueberry_module_configure_form'] = [
      'display_name' => t('Add optional modules'),
      'display' => TRUE,
      'type' => 'form',
      'function' => 'Drupal\blueberry\Form\ModuleConfigureForm',
    ];

    $tasks['blueberry_finish_installation'] = [
      'display_name' => t('Finish installation'),
    ];

    return $tasks;
  }
}


/**
 * Finish Blueberry installation process.
 *
 * @param array $install_state
 *    The install state.
 *
 * @throws \Drupal\Core\Entity\EntityStorageException
 */
function blueberry_finish_installation(array &$install_state) {
  \Drupal::service('config.installer')->installOptionalConfig();
}
