<?php
date_default_timezone_set("Asia/Bangkok");
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'');
define('DB_NAME', 'test');
define('URL_SERV', $_SERVER['HTTP_HOST']."/test/");
class db_conn{
	var $DB;
	function __construct(){
		$conDB = mysqli_connect(DB_SERVER,DB_USER,DB_PASS) or die('localhost connection problem'.mysqli_error(mysqli_connect(DB_SERVER,DB_USER,DB_PASS)));
		$this->DB = $conDB;
		mysqli_select_db($conDB,DB_NAME);
		mysqli_query($this->DB,"SET NAMES utf8");
	}
	public function sqlQuery($strSQL){
		mysqli_query($this->DB,"SET NAMES utf8");
		$objQuery = mysqli_query($this->DB, $strSQL);
		return $objQuery;
	}
	public function sqlEscapestr($value){
		if($value != ''){
			$obj = mysqli_real_escape_string($this->DB,$value);
		}else{
			$obj = $value;
		}
		return $obj;
	}
	public function sqlNumrows($strSQL){
		$objQuery = mysqli_query($this->DB,$strSQL);
		$numRows = mysqli_num_rows($objQuery);
		return $numRows;
	}

	public function escape($value) {
        return $this->DB->real_escape_string($value);
    }

}
class numtobahtthai{
	public function tothai($number){
		$numberformat = number_format($number,2);
		$explode = explode('.' , $numberformat);
		$baht = $explode[0];
		$stang = $explode[1];
		if($stang == '00'){
			return $this->thai($baht).'บาทถ้วน';
		}else{
			return $this->thai($baht).'บาท'.$this->thai($stang).'สตางค์';
		}
	}
	public function thai($num){   
		$num = str_replace(',','',$num);
		$num_decimal = explode('.',$num);
		$num = $num_decimal[0];
		$returnNumWord = '';   
		$lenNumber = strlen($num);   
		$lenNumber2 = $lenNumber - 1;   
		$kaGroup = array('' , 'สิบ' ,  'ร้อย' , 'พัน' , 'หมื่น' , 'แสน' , 'ล้าน' , 'สิบ' , 'ร้อย' , 'พัน' , 'หมื่น' , 'แสน' , 'ล้าน');   
		$kaDigit = array('' , 'หนึ่ง' , 'สอง' , 'สาม' , 'สี่' , 'ห้า' , 'หก' , 'เจ็ด' , 'แปด' , 'เก้า');   
		$kaDigitDecimal = array('ศูนย์' , 'หนึ่ง' , 'สอง' , 'สาม' , 'สี่' , 'ห้า' , 'หก' , 'เจ็ด' , 'แปด' , 'เก้า');   
		$ii = 0;   
		for($i = $lenNumber2;$i >= 0;$i--){   
			$kaNumWord[$i] = substr($num,$ii,1);   
			$ii++;   
		}   
		$ii = 0;   
		for($i = $lenNumber2;$i >= 0;$i--){   
			if(($kaNumWord[$i] == 2 && $i ==1) || ($kaNumWord[$i] == 2 && $i == 7)){   
				$kaDigit[$kaNumWord[$i]]='ยี่';   
			}else{   
				if($kaNumWord[$i] == 2){   
					$kaDigit[$kaNumWord[$i]] = 'สอง';        
				}   
				if(($kaNumWord[$i] == 1 && $i <= 2 && $i == 0) || ($kaNumWord[$i] == 1 && $lenNumber > 6 && $i == 6)){   
					if($kaNumWord[$i + 1] == 0){   
						$kaDigit[$kaNumWord[$i]] = 'หนึ่ง';      
					}else{   
						$kaDigit[$kaNumWord[$i]] = 'เอ็ด';       
					}   
				}else if(($kaNumWord[$i] == 1 && $i <= 2 && $i == 1) || ($kaNumWord[$i] == 1 && $lenNumber >6 && $i == 7)){   
					$kaDigit[$kaNumWord[$i]] = '';   
				}else{   
					if($kaNumWord[$i] == 1){   
			$kaDigit[$kaNumWord[$i]] = 'หนึ่ง';   
					}   
				}   
			}   
			if($kaNumWord[$i] == 0){   
			if($i != 6){
				$kaGroup[$i] = '';   
				}
			}   
			$kaNumWord[$i] = substr($num,$ii,1);   
			$ii++;   
			$returnNumWord.= $kaDigit[$kaNumWord[$i]].$kaGroup[$i];   
		}
		return $returnNumWord;
	}
	public function txtSMonth($m){
		switch($m){
			case "1":
			$m = " ม.ค.";
			break;
			case "2":
			$m = "ก.พ.";
			break;
			case "3":
			$m = "มี.ค.";
			break;
			case "4":
			$m = "เม.ย.";
			break;
			case "5":
			$b = "พ.ค.";
			break;
			case "6":
			$m = "มิ.ย.";
			break;
			case "7":
			$m = "ก.ค.";
			break;
			case "8":
			$m = "ส.ค.";
			break;
			case "9":
			$m = "ก.ย.";
			break;
			case "10":
			$m = "ต.ค.";
			break;
			case "11":
			$m = "พ.ย.";
			break;
			case "12":
			$m = "ธ.ค.";
			break;
		}
		return $m;
	}
	public function txtMonth($m){
		switch($m){
			case "01":
			$m = "มกราคม";
			break;
			case "02":
			$m = "กุมภาพันธ์";
			break;
			case "03":
			$m = "มีนาคม";
			break;
			case "04":
			$m = "เมษายน";
			break;
			case "05":
			$b = "พฤษภาคม";
			break;
			case "06":
			$m = "มิถุนายน";
			break;
			case "07":
			$m = "กรกฎาคม";
			break;
			case "08":
			$m = "สิงหาคม";
			break;
			case "09":
			$m = "กันยายน";
			break;
			case "10":
			$m = "ตุลาคม";
			break;
			case "11":
			$m = "พฤศจิกายน";
			break;
			case "12":
			$m = "ธันวาคม";
			break;
			}
		return $m;
	}
} 
?>