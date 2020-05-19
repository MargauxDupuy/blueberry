<?php

namespace Drupal\blueberry\Form;


use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for selecting optional modules during install.
 */
class ModuleConfigureForm extends FormBase {

  /**
   * The module installer service.
   *
   * @var \Drupal\Core\Extension\ModuleInstallerInterface
   */
  protected $moduleInstaller;

  /**
   * Construct a new configurable profile form.
   *
   * @param \Drupal\Core\Extension\ModuleInstallerInterface $module_installer
   *   The module installer service.
   */
  public function __construct(ModuleInstallerInterface $module_installer) {
    $this->moduleInstaller = $module_installer;
  }

  /*
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('module_installer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'blueberry_module_configure_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [];
  }


  /**
   *
   * Provides a form to select the modules to be installed.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $form['#title'] = $this->t('Add optional modules');

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Please select which module you want to activate :'),
    ];

    $buildInfo = $form_state->getBuildInfo();

    $install_state = $buildInfo['args'][0];

    if (!empty($install_state['profile_info']['configurable_modules'])) {
      $form['install_modules'] = [
        '#type' => 'container',
        '#tree' => TRUE,
      ];

      foreach ($install_state['profile_info']['configurable_modules'] as $module => $info) {
        $form['install_modules'][$module] = [
          '#type' => 'checkbox',
          '#title' => $info['label'],
          '#description' => !empty($info['description']) ? $info['description'] : '',
          '#default_value' => $info['enabled'],
        ];
      }
    }
    else {
      $form['#suffix'] = $this->t('There are no available modules at this time.');
    }

    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save and finish installation'),
      '#button_type' => 'primary',
      '#submit' => ['::submitForm'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    //Array of modules to install, filtered by if they have been enabled.
    $modules_to_install = array_filter($form_state->getValue('install_modules'), function ($enabled) {
      return (bool) $enabled;
    });

    $this->moduleInstaller->install(array_keys($modules_to_install));
  }
}
