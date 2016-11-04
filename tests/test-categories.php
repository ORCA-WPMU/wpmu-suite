<?php

class WPMU_Categories_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPMU_Categories') );
	}

	function test_class_access() {
		$this->assertTrue( wpmu_suite()->categories instanceof WPMU_Categories );
	}
}
