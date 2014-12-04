<?php

namespace Syra\ApiContainer\Mixin;

trait Pagination {
	protected $__page = 1;
	protected $__perPage = 10;

	protected function getPage() {
		return $this->__page;
	}

	protected function setPage($value) {
		$this->__page = $value;
		return $this;
	}

	protected function setPerPage($num) {
		$this->__perPage = $num;
		return $this;
	}

	protected function getPerPage() {
		return $this->__perPage;
	}

	public function paginationListener() {
		$request = $this->getRequest();
		if ($request->has('page')) {
			$this->setPage($request->get('page'));
		}
		if ($request->has('perPage')) {
			$this->setPage($request->get('perPage'));
		}
	}

}
