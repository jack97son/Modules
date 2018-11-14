<?php

namespace Drupal\Module\Controller;

use Drupal\Core\Controller\ControllerBase;


/**
 * Provides route response from Module_block module.
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

		$result = $connection->query("SELECT * FROM module WHERE uid = :uid" , array('uid' => $uid));

		while ($nodes = $result->fetchAssoc()) {
			$nids[] = $nodes['nid'];
		}

		$node_type = 'node';

		$entities = \Drupal::entityTypeManager()->getStorage($node_type)->loadMultiple($nids);

		$output = '<ul>';

		
		foreach ($entities as $entity) {

			$output .= '<h4><li>' . $entity->getTitle() . '</li></h4>';


		}

		$output .= '</ul>';

		return array(
			'#type' => 'markup',
			'#markup' => $output,
			'#title' => 'Your Favourites Series',
		);

	}

}