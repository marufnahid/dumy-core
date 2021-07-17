<?php
require_once __DIR__."/widgets/Widget_Two.php";
require __DIR__. '/widgets/name.php';
function register_new_widget() {
	register_widget( 'Name_Widget2' );
	register_widget( 'widget_two' );
}
add_action( 'widgets_init', 'register_new_widget' );