<?php
   namespace Register;

   class RegisterValidator{
	   public static function username_length($name){
		   if(strlen($name)<4 || strlen($name)>30)
			   return false;
		   else
			   return true;
	   }
	   
	   public static function username_regex($name){
		   if(!preg_match("/^[\w]+$/",$name) || preg_match("/^[\w]+$/",$name)==0)
			   return false;
		   else
			   return true;
	   }
	   
	   public static function password_length($pwd){
		   if(strlen($pwd)<8 || strlen($pwd)>30)
			   return false;
		   else
			   return true;
	   }
	   
	   public static function password_compare($pwd1, $pwd2){
		   if($pwd1 != $pwd2)
			   return false;
		   else
			   return true;
	   }
	   
	   public static function date_check($date){
		   if($date >= new \DateTime())
			   return false;
		   else
			   return true;
	   }
   }