<?php
namespace Thexcore\ThexElementor\Widgets;

//use Elementor\Widget_Base;
//use Elementor\Controls_Manager;
//use Elementor\Scheme_Color;
//use Elementor\Scheme_Typography;
//use Elementor\Group_Control_Typography;

 //Exit if accessed directly
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Accordion
 * @package DocyCore\Widgets
 */
class Accordion  extends  Widget_Base {

	public function get_name() {
		return 'thex-accordion';
	}

	public function get_title() {
		return esc_html__( 'Thex Accordion', 'docy-core' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	public function get_keywords() {
		return [ 'toggle' ];
	}

	public function get_categories() {
		return [ 'thex-addons' ];
	}

	protected function _register_controls() {

	}

	protected function render() {

	}
}

