<?php

namespace Drupal\resume\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Drupal\Core\Link;



/**
 * Provides route response from resume_block module.
 */

class FavouritesPage extends ControllerBase {

	/**
	 * Returns a page with a query of all series related to favourites series of current user.
	 *
	 * @return array
	 *	a simple renderable array.
	 */

	public function PageController() {

		$connection = \Drupal::database();

		$user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

    $uid = $user->get('uid')->value;
    

    /**
    	* Get the ID's of nodes.
    	*/

		$nids = [];

		$result = $connection->query("SELECT * FROM favourites WHERE uid = :uid" , array('uid' => $uid));

		while ($nodes = $result->fetchAssoc()) {
			$nids[] = $nodes['nid'];
		}

		$node_type = 'node';

		$entities = \Drupal::entityTypeManager()->getStorage($node_type)->loadMultiple($nids);

		$output = array();

		
		foreach ($entities as $entity) {

			// $output .=  '<h4><li>' . $entity->getTitle() . '</li></h4>';
			$remove = Url::fromUserInput('/user/favourites/'. $entity->id() . '/remove');

			$output[]= array (
				'title' => link::fromTextAndUrl($entity->getTitle(), $entity->toUrl()),
				'link' => Link::fromTextAndUrl(' Eliminar  ', $remove)
			);

		}

		$encabezado = array(
			'title' => t('Título'),
			'title2' => t('Elegir'),

		);


		return array(
			'#type' => 'table',
			'#header' => $encabezado,
			'#rows' => $output,
			'#title' => 'Your Favourites Series',
		);

	}

}