<?php

namespace Drupal\Module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'Favourites Block' Block.
 *
 * @Block(
 * 	 id = "Module_created",
 * 	 admin_label = @Translation("Module created"),
 * 	 category = @Translation("Blocks"),
 *  )
 */

class PlaylistBlock extends BlockBase {

	/*
	 *  {@inheritdoc}
	 */
	public function build(){

		$form = \Drupal::formBuilder()->getForm('Drupal\Module\Form\WorkForm');
		return $form;

	}
}