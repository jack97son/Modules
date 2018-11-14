<?php
/**
 * @file
 * Contains \Drupal\Playlist\Form\WorkForm.
 */
namespace Drupal\Playlist\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\Element;


class workForm extends FormBase {

  protected $entityTypeManager;
  protected $queryFactory;
	
  /**
   * {@inheritdoc}
   */

  //Esto debe devolver una cadena que sea el ID único de su formulario. Espacio de nombres el ID de formulario basado en el nombre de su módulo.


  public function getFormId() {
    return 'Playlist_form';
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
  public function __construct(EntityTypeManagerInterface $entityTypeManager,
                              QueryFactory $queryFactory) {

    $this->entityTypeManager = $entityTypeManager;
    $this->queryFactory = $queryFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity.query')
    );
  }

  //Esto devuelve una matriz de API de formulario que define cada uno de los elementos de los que está compuesto su formulario.

  public function buildForm(array $form, FormStateInterface $form_state) {



    $form['default_value'] = array(
      '#title' => $this->t('<h3>★Your favorite serie:</h3>'),
      '#type' => 'select',
      '#multiple' => TRUE,
      
      
      '#attributes' => array(
      'id' => 'edit-select-user',
      'style' => 'width:220px',

   ),


      '#options' => $this->GetData(), 
 
  );
    /**
     * @RenderElement("link");
     */

    // $form['actions']['#type'] = 'actions';
    // $form['actions']['submit'] = array(
    //   '#type' => 'submit',
    //   '#value' => $this->t('Choose'),
    //   '#button_type' => 'info',
    // );

    /**
     * @RenderElement("link");
     */

    $form['link_favourites'] = [
      '#type' => 'link',
      '#title' => $this->t('Elegir'),
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

   $connection = \Drupal::database();

   /**
   * Load the current User.
   * Use \Drupal\user\Entity\User::load.
   */

   $nids=array();
   $uid = \Drupal::currentUser()->id();
   $nids= $form_state->getValue('default_value');
   // var_dump($nids); die();

   
//     // /**
//     // * Query to verify if the serie is save in the list yet.
//     // */

    foreach ($nids as $nid){
     $result = $connection->insert('favourites')->fields([
     'uid' => $uid,
     'nid' => $nid,
     ])->execute(); 

    
   }

   if ($result) {
     drupal_set_message(t('Serie added to the list!'));
   } else {
     drupal_set_message(t('Error, Please try it again later'));
   }    
 }
} 