<?php

class AdminController extends BaseController {

	protected $layout = 'layouts.master';
	
	public function getIndex()
	{
		$this->layout->content = View::make('admin.index');
	}

}