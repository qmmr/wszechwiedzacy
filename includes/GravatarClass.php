<?php

/**
 * autor: Marcin Kumorek
 * data: 27.01.2010
 */
class GravatarClass {
    
    protected static $email = "john.doe@domain.com";
    protected static $size = 80;
    protected static $rating = "g";
    protected static $url = "http://www.gravatar.com/avatar/";
    protected static $image = "identicon";
    
    /**
     *	GravatarClass::get_gravatar("john.doe@domain.com", 80, "pg", "identicon");
     */
    public static function get_gravatar($email = "john.doe@domain.com", $size = 80, $rating = "pg", $image = "identicon") {
	
	$hashed_email = md5(strtolower($email));
	$gravatar_link = self::$url . $hashed_email . "?s=" . $size . "&r=" . $rating;
	if($image == "identicon") {
	    
	    $gravatar_link .= "&d=identicon";
	    
	} else {
	    
	    $gravatar_link .= $image;
	    
	}
	
	return strtolower($gravatar_link);
    }
    
    public static function get_gravatar_hashed($hash,$size = 40){
	
	return $gravatar_link = self::$url . $hash . "?s=".$size."&r=g&d=identicon";
    
    }
    
} // end of GravatarClass