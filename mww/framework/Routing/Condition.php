<?php

namespace MWW\Routing;

class Condition {
	protected $conditions;

	public function __construct( ...$conditions ) {
		$this->conditions = array_shift( $conditions );
	}

	public function getConditions() {
		return $this->conditions;
	}

	public static function match( ...$conditions ) {
		return new self( $conditions );
	}
}
