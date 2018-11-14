<?php
/**
 * @file
 * Contains \Drupal\resume\Form\WorkForm.
 */
namespace Drupal\resume\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Database\Query\Merge as QueryMerge;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Connection;

class workForm extends FormBase {

  protected $entityTypeManager;
  protected $queryFactory;
  
  /**
   * {@inheritdoc}
   */

  //Esto debe devolver una cadena que sea el ID único de su formulario. Espacio de nombres el ID de formulario basado en el nombre de su módulo.


  public function getFormId() {
    return 'resume_form';
  }
  /**
   * {@inheritdoc}
   */

  /**
   * Constructs a new OpController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Entity\Query\QueryFactory $queryFactory
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, QueryFactory $queryFactory) {

    $this->entityTypeManager = $entityTypeManager;
    $this->queryFactory = $queryFactory;

    
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity.query'),
      $container->get('current_user')
    );
  }

  //Esto devuelve una matriz de API de formulario que define cada uno de los elementos de los que está compuesto su formulario.

  public function buildForm(array $form, FormStateInterface $form_state) {


    $form['series'] = [
      '#type' => 'fieldset',
      '#title' => t('<h4>★Your favorite serie:</h4>'),

      '#attributes' => array(
      'id' => 'edit-select-user',
      'style' => 'width:260px',

   ),

    ];

    $form['series']['node'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      // '#default_value' =>,

      '#attributes' => array(
      'id' => 'edit-select-user',
      'style' => 'width:220px',

   ),

      '#options' => $this->GetData(),

    ];



    $form['series']['actions']['#type'] = 'actions';
    $form['series']['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Choose'),
      '#ajax' => [
        'callback' => '::setMessage',

      ],
      '#button_type' => 'info',
    );


  
  /**
     * @RenderElement("link");
     */

  $form['link_favourites'] = [
    '#type' => 'link',
    '#title' => $this->t('Your favorites series'),
    '#url' => \Drupal\Core\Url::fromRoute('playlist_block.favourites_page'),
    ];

    
    return $form;

  }

  public function GetData() {
    
    $query = $this->entityTypeManager->getStorage('node');
    $query_result = $query->getQuery()
      ->condition('status', 1)
      ->condition('type', 'serie')
      ->sort('title', 'ASC')
      ->execute();

     $entities = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($query_result); 


     foreach ($entities as $entity) {
      $output[$entity->id()] = $entity->getTitle();
     }


    return $output;
 }

  /**
   * {@inheritdoc}
   */

  //los valores recopilados del usuario cuando se envió el formulario están en este punto y podemos asumir que ya se han validado y están listos para que los utilicemos.
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
 

  $service = \Drupal::service('custom_services_example.say_hello');

  $service->Saludar();   
  
  $connection = \Drupal::database();

  /**
  * Load the current User.
  * Use \Drupal\user\Entity\User::load.
  */

  $nids=array();
  $uid = \Drupal::currentUser()->id();
  $nids= $form_state->getValue('node');
  // var_dump($nids); die();

   
//     // /**
//     // * Query to verify if the serie is save in the list yet.
//     // */

    foreach ($nids as $nid){
    
    $result = $connection->merge('favourites')
  ->insertFields([
    'uid' => $uid,
    'nid' => $nid,
  ])
  ->key(['nid' => $nid
  ])
  ->execute();

   }

   

   if ($service->Saludar()) {
     drupal_set_message(t('estoy bien y tú?'));
   } 
 }
} 