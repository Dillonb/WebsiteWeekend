<?php

class Hours{
	// if we ended up here it is because we called a page with no associated model. 
	public $view;
	public $vars;

	function __construct($query){
		$this->view = $query;

	}

	public function getAllHours(){
		//Gets Hours without any expertise search
		$query = "SELECT fkUserID, fldFirstName, fldLastName, day , hour,endHour ";
		$query .= "FROM tblUserProfile tbp, tblHours th,tblUserAccount tbu WHERE ";
		$query .= "tbp.fkUserID = th.fkCrewID AND tbp.fkUserID = tbu.pkUserID AND tbu.active=1 ORDER BY hour;";
		$dbWrapper = new InteractDB();
		$dbWrapper->customStatement($query);
		$this->vars['hours'] =  $dbWrapper->returnedRows;
		return $this->vars['hours'];
	}

	public function addHours($id,$hour,$day,$endHour){
		$dbWrapper = new InteractDB();
		$query = "INSERT INTO `CSCREW_Website`.`tblHours` (`fkCrewID`, `day`, `hour`,`endHour`) VALUES ";
		$query .= "(':id', ':day', ':hour',':endHour');";
		$arr = array(':id'=>$id, ':day'=>$day, ':hour'=>$hour,':endHour'=>$endHour);
		$dbWrapper->customStatement($query, $arr);

	}

	public function getActiveMembers(){
		$query = "SELECT fkUserID, fldFirstName, fldLastName FROM tblUserProfile tbp, tblUserAccount tbu ";
		$query .= "WHERE tbp.fkUserID = tbu.pkUserID AND tbu.active=1";
		$dbWrapper = new InteractDB();
		$dbWrapper->customStatement($query);
		$this->vars['members'] =  $dbWrapper->returnedRows;
		return $this->vars['members'];	
	}

	public function getAllHoursByExpertise($expertise=0){
		if($expertise=="Show+all"){
			return $this->getAllHours();
		}
		$query = "SELECT tbp.fkUserID, fldFirstName, fldLastName, day , hour, endHour ";
		$query .= "FROM tblUserProfile tbp, tblHours th,tblUserAccount tbu,tblExpertise tl WHERE ";
		$query .= "tbp.fkUserID = th.fkCrewID AND tbp.fkUserID = tbu.pkUserID AND tbu.active=1 AND ";
		$query .= "tl.fkLangID= :expertise AND tl.fkUserID = tbp.fkUserID ORDER BY hour;";
		$dbWrapper = new InteractDB();
		$arr = array(':expertise'=>$expertise);
		$dbWrapper->customStatement($query, $arr);
		$this->vars['hours'] =  $dbWrapper->returnedRows;
		return $this->vars['hours'];	
	}

	public function updateHours($id,$newHour,$newEndHour){
		//Id is an array with the fkCrewID in 0th, the day in the 1st,  then the begin and end hour in the next slots
		$query = "UPDATE tblHours SET `hour` = $newHour, `endHour` = $newEndHour WHERE ";
		$query .= "fkCrewID = :id0 AND day = ':id1' AND hour = ':id2' LIMIT 1;";
		$dbWrapper = new InteractDB();
		$arr = array(':id0'=>$id[0], ':id1'=>$id[1], ':id2'=>$id[2]);
		$dbWrapper->customStatement($query, $arr);
		if($dbWrapper->errorCondition->errorInfo[1] == 2053){
			//A 2053 means it worked... weird but true
			$this->vars['success'] = true;
		}else{
			$this->vars['success'] = false;
			return false;
		}
		return $this->vars['success'];	
	}

	public function deleteHours($info){
		//info is an array with pkid in 0, day in 1, hour in 2
		$query = "DELETE FROM tblHours WHERE fkCrewID=':info0' AND day=':info1' AND hour=':info2'; ";
		$dbWrapper = new InteractDB();
		$arr = array(':info0'=>$info[0], ':info1'=>$info[1], ':info2'=>$info[2]);
		$dbWrapper->customStatement($query, $arr);
		if($dbWrapper->errorCondition->errorInfo[1] == 2053){
			//A 2053 means it worked... weird but true
			$this->vars['success'] = true;
		}else{
			$this->vars['success'] = false;
			return false;
		}
		return $this->vars['success'];

	}

	public function getLanguages(){
		$dbWrapper = new InteractDB('select',array('tableName'=>'tblLanguages'));
		$this->vars['languages'] =  $dbWrapper->returnedRows;
		return $this->vars['languages'];
	}

	public function getView(){
		if($this->view){
			return $this->view;
		}else{return false;}
	}
}



?>