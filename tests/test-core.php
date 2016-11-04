<?php

class WPMUS_Core_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPMUS_Core') );
	}

	function test_class_access() {
		$this->assertTrue( wpmu_suite()->core instanceof WPMUS_Core );
	}
}
