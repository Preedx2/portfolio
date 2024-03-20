<?php

require __DIR__."/../Register/RegisterValidator.php";

class RegisterTest extends \PHPUnit\Framework\TestCase {
	public function testUsernameLength(){
		//check if true for minimum name length - 4 characters
		$result = Register\RegisterValidator::username_length("1234");
		$this->assertTrue($result);
		//check if true for maximum name length - 30 characters
		$result = Register\RegisterValidator::username_length("123456789012345678901234567890");
		$this->assertTrue($result);
		//check if false for too short name
		$result = Register\RegisterValidator::username_length("123");
		$this->assertFalse($result);
		//check if false for too long name
		$result = Register\RegisterValidator::username_length("1234567890123456789012345678901");
		$this->assertFalse($result);
		//check if false for 0 length name
		$result = Register\RegisterValidator::username_length("");
		$this->assertFalse($result);
	}
	
	public function testUsernameRegexTrue(){
		//check if true for proper name case1
		$result = Register\RegisterValidator::username_regex("asdasd");
		$this->assertTrue($result);
		//case2
		$result = Register\RegisterValidator::username_regex("123asd123");
		$this->assertTrue($result);
		//case3
		$result = Register\RegisterValidator::username_regex("123_123");
		$this->assertTrue($result);
		//case4
		$result = Register\RegisterValidator::username_regex("testowanie_123");
		$this->assertTrue($result);
		//case5
		$result = Register\RegisterValidator::username_regex("_1aA");
		$this->assertTrue($result);
	}
	
	public function testUsernameRegexFalse(){
		//check if false for improper names: case1
		$result = Register\RegisterValidator::username_regex("#asd");
		$this->assertFalse($result);
		//case 2
		$result = Register\RegisterValidator::username_regex("#$%asd");
		$this->assertFalse($result);
		//case3
		$result = Register\RegisterValidator::username_regex("@");
		$this->assertFalse($result);
		//case4
		$result = Register\RegisterValidator::username_regex("$");
		$this->assertFalse($result);
		//case5
		$result = Register\RegisterValidator::username_regex("%");
		$this->assertFalse($result);
		//case6
		$result = Register\RegisterValidator::username_regex("^()>");
		$this->assertFalse($result);
		//case7
		$result = Register\RegisterValidator::username_regex(".....");
		$this->assertFalse($result);
		//check if null string returns false
		$result = Register\RegisterValidator::username_regex("");
		$this->assertFalse($result);
	}
	
	public function testPasswordLength(){
		//check if true for minimum password length - 8 characters
		$result = Register\RegisterValidator::password_length("12345678");
		$this->assertTrue($result);
		//check if true for maximum password length - 30 characters
		$result = Register\RegisterValidator::password_length("123456789012345678911234567892");
		$this->assertTrue($result);
		//check if false for too short password
		$result = Register\RegisterValidator::password_length("1234567");
		$this->assertFalse($result);
		//check if false for too long password
		$result = Register\RegisterValidator::password_length("1234567890123456789012345678901");
		$this->assertFalse($result);
		//check if false for 0 length password
		$result = Register\RegisterValidator::password_length("");
		$this->assertFalse($result);
	}
	
	public function testPasswordCompare() {
		//check if true for identical strings case1
		$result = Register\RegisterValidator::password_compare("1","1");
		$this->assertTrue($result);
		//case2
		$result = Register\RegisterValidator::password_compare("aAbBcC","aAbBcC");
		$this->assertTrue($result);
		//case3
		$result = Register\RegisterValidator::password_compare("Test_1","Test_1");
		$this->assertTrue($result);
		//case4
		$result = Register\RegisterValidator::password_compare("","");
		$this->assertTrue($result);
		
		//check if function returns false values if strings do not match case1
		$result = Register\RegisterValidator::password_compare("2","1");
		$this->assertFalse($result);
		//case2
		$result = Register\RegisterValidator::password_compare("a","A");
		$this->assertFalse($result);
		//case3
		$result = Register\RegisterValidator::password_compare("","not empty");
		$this->assertFalse($result);
	}	
	
	public function testDateCheck() {
		//check if current time is true
		$result = Register\RegisterValidator::date_check(new DateTime());
		$this->assertTrue($result);
		//check if future time is false
		$result = Register\RegisterValidator::date_check(new DateTime("2024-10-21"));
		$this->assertFalse($result);
		//check if past time is true
		$result = Register\RegisterValidator::date_check(new DateTime("2022-10-21"));
		$this->assertTrue($result);
	}
}
