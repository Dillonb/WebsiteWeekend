<?php

class Tutorial{
	// if we ended up here it is because we called a page with no associated model. 
	public $view;
	public $vars;

	function __construct($query){
		$this->view = $query;

	}

	public function addTutorial($url,$title,$cat){
		$info = array('tableName' => 'tblTutorials','pkTutorialId'=>date('U'),'fldURL' => $url, 'fldTitle' => $title,'fldCategory'=>$cat);
		$dbWrapper = new InteractDB('insert',$info);	}

	public function getTutorials(){
		//info is an array with pkid in 0, day in 1, hour in 2
		$query = "SELECT fldURL as url, fldTitle as title, fldCategory as cat FROM tblTutorials WHERE fldPublished = 1 ORDER BY fldCategory";
		$dbWrapper = new InteractDB();
		$dbWrapper->customStatement($query);
		//PreProcess the returned rows into their categories
		$tuts = $dbWrapper->returnedRows;
		$tutorials = array();
		foreach ($tuts as $tut) {
			# code...
		}
		return $dbWrapper->returnedRows;

	}

	public function getView(){
		if($this->view){
			return $this->view;
		}else{return false;}
	}
}



?>