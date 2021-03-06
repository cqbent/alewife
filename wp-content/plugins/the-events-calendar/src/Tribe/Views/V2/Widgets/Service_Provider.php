<?php
/**
 * The main service provider for the version 2 of the Widgets.
 *
 * @since   5.2.1
 *
 * @package Tribe\Events\Views\V2\Widgets
 */

namespace Tribe\Events\Views\V2\Widgets;

use Tribe\Events\Views\V2\Views\Widgets\Widget_List_View;

/**
 * Class Service_Provider
 *
 * @since   5.2.1
 *
 * @package Tribe\Events\Views\V2\Widgets
 */
class Service_Provider extends \tad_DI52_ServiceProvider {

	/**
	 * Variable that holds the name of the widgets being created.
	 *
	 * @since 5.2.1
	 *
	 * @var array<string>
	 */
	protected $widgets = [
		'tribe_events_list_widget',
	];

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 5.2.1
	 */
	public function register() {
		// Determine if V2 views are loaded.
		if ( ! tribe_events_views_v2_is_enabled() ) {
			return;
		}

		// Determine if V2 widgets should load.
		if ( ! tribe_events_widgets_v2_is_enabled() ) {
			return;
		}

		// These hooks always run to provide widget compatibility for v1 to v2 and reverse.
		$this->register_compatibility();

		$this->hook();
	}

	/**
	 * Registers the provider handling for compatibility hooks.
	 *
	 * @since 5.3.0
	 */
	protected function register_compatibility() {
		$compatiblity = new Compatibility();
		$this->container->singleton( Compatibility::class, $compatiblity );
		$this->container->singleton( 'events.views.v2.widgets.compatibility', $compatiblity );

		add_action( 'tribe_plugins_loaded', [ $compatiblity, 'switch_compatibility' ] );
		add_filter( 'option_sidebars_widgets', [ $compatiblity, 'remap_list_widget_id_bases' ] );
	}

	/**
	 * Function used to attach the hooks associated with this class.
	 *
	 * @since 5.2.1
	 */
	public function hook() {
		add_filter( 'tribe_widgets', [ $this, 'register_widget' ] );
		add_filter( 'tribe_events_views', [ $this, 'add_views' ] );
		add_action( 'widgets_init', [ $this, 'unregister_list_widget' ], 95 );
		add_filter( 'tribe_events_views_v2_view_widget-events-list_template_vars', [ $this, 'filter_template_vars' ], 20 );
	}

	/**
	 * Add the widgets to register with WordPress.
	 *
	 * @since 5.2.1
	 *
	 * @param array<string,string> $widgets An array of widget classes to register.
	 *
	 * @return array<string,string> An array of registered widget classes.
	 */
	public function register_widget( $widgets ) {
		$widgets['tribe_events_list_widget'] = Widget_List::class;

		return $widgets;
	}

	/**
	 * Add the widget views to the view manager.
	 *
	 * @since 5.2.1
	 *
	 * @param array<string,string> $views An associative array of views in the shape `[ <slug> => <class> ]`.
	 *
	 * @return array<string,string> $views The modified array of views in the shape `[ <slug> => <class> ]`.
	 */
	public function add_views( $views ) {
		$views['widget-events-list'] = Widget_List_View::class;

		return $views;
	}

	/**
	 * Unregister the existing List Widget.
	 *
	 * @since 5.3.0
	 */
	public function unregister_list_widget() {
		unregister_widget( 'Tribe__Events__List_Widget' );
	}

	/**
	 * Filters the template vars for widget-specific items.
	 *
	 * @since 5.3.0
	 *
	 * @param array<string,mixed> $template_vars The current template variables.
	 *
	 * @return array<string,mixed> The modified template variables.
	 */
	public function filter_template_vars( $template_vars ) {
		return tribe( Widget_List::class )->disable_json_data( $template_vars );
	}
}
