<?php
	 function UserIsLogged(){
		if(!isset($_SESSION["client"])){
			return false;
		}
		else{
			return true;
		}
	}
	function UserIsAdmin(){
			return true;
	}
	function checkLogin(){
	}
	function register(){
	}
?>