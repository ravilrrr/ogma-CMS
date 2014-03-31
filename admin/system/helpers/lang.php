<?php 

 /**
 *  OGMA CMS Pages Module
 *
 *  @package ogmaCMS
 *  @author Mike Swan / n00dles101
 *  @copyright 2013 Mike Swan / n00dles101
 *  @since 1.0.0
 *
 */

class Lang{
	
	public static $language = array(); 

	public static $dateformats = array(
		'F j, Y' 						=> 'January 1, 2103',
		'Y/m/d' 						=> '2013/12/01',
		'm/d/Y' 						=> '12/01/2013',
		'd/m/Y' 						=> '01/12/2013',
		'Y:m:d' 						=> '2013:12:01',
		'm:d:Y' 						=> '12:01:2013',
		'd:m:Y' 						=> '01:12:2013'
	);

	public static $timezones = array(
	    'Pacific/Midway'       => "(GMT-11:00) Midway Island",
	    'US/Samoa'             => "(GMT-11:00) Samoa",
	    'US/Hawaii'            => "(GMT-10:00) Hawaii",
	    'US/Alaska'            => "(GMT-09:00) Alaska",
	    'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
	    'America/Tijuana'      => "(GMT-08:00) Tijuana",
	    'US/Arizona'           => "(GMT-07:00) Arizona",
	    'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
	    'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
	    'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
	    'America/Mexico_City'  => "(GMT-06:00) Mexico City",
	    'America/Monterrey'    => "(GMT-06:00) Monterrey",
	    'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
	    'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
	    'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
	    'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
	    'America/Bogota'       => "(GMT-05:00) Bogota",
	    'America/Lima'         => "(GMT-05:00) Lima",
	    'America/Caracas'      => "(GMT-04:30) Caracas",
	    'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
	    'America/La_Paz'       => "(GMT-04:00) La Paz",
	    'America/Santiago'     => "(GMT-04:00) Santiago",
	    'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
	    'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
	    'Greenland'            => "(GMT-03:00) Greenland",
	    'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
	    'Atlantic/Azores'      => "(GMT-01:00) Azores",
	    'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
	    'Africa/Casablanca'    => "(GMT) Casablanca",
	    'Europe/Dublin'        => "(GMT) Dublin",
	    'Europe/Lisbon'        => "(GMT) Lisbon",
	    'Europe/London'        => "(GMT) London",
	    'Africa/Monrovia'      => "(GMT) Monrovia",
	    'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
	    'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
	    'Europe/Berlin'        => "(GMT+01:00) Berlin",
	    'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
	    'Europe/Brussels'      => "(GMT+01:00) Brussels",
	    'Europe/Budapest'      => "(GMT+01:00) Budapest",
	    'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
	    'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
	    'Europe/Madrid'        => "(GMT+01:00) Madrid",
	    'Europe/Paris'         => "(GMT+01:00) Paris",
	    'Europe/Prague'        => "(GMT+01:00) Prague",
	    'Europe/Rome'          => "(GMT+01:00) Rome",
	    'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
	    'Europe/Skopje'        => "(GMT+01:00) Skopje",
	    'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
	    'Europe/Vienna'        => "(GMT+01:00) Vienna",
	    'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
	    'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
	    'Europe/Athens'        => "(GMT+02:00) Athens",
	    'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
	    'Africa/Cairo'         => "(GMT+02:00) Cairo",
	    'Africa/Harare'        => "(GMT+02:00) Harare",
	    'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
	    'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
	    'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
	    'Europe/Kiev'          => "(GMT+02:00) Kyiv",
	    'Europe/Minsk'         => "(GMT+02:00) Minsk",
	    'Europe/Riga'          => "(GMT+02:00) Riga",
	    'Europe/Sofia'         => "(GMT+02:00) Sofia",
	    'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
	    'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
	    'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
	    'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
	    'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
	    'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
	    'Asia/Tehran'          => "(GMT+03:30) Tehran",
	    'Europe/Moscow'        => "(GMT+04:00) Moscow",
	    'Asia/Baku'            => "(GMT+04:00) Baku",
	    'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
	    'Asia/Muscat'          => "(GMT+04:00) Muscat",
	    'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
	    'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
	    'Asia/Kabul'           => "(GMT+04:30) Kabul",
	    'Asia/Karachi'         => "(GMT+05:00) Karachi",
	    'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
	    'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
	    'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
	    'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
	    'Asia/Almaty'          => "(GMT+06:00) Almaty",
	    'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
	    'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
	    'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
	    'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
	    'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
	    'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
	    'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
	    'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
	    'Australia/Perth'      => "(GMT+08:00) Perth",
	    'Asia/Singapore'       => "(GMT+08:00) Singapore",
	    'Asia/Taipei'          => "(GMT+08:00) Taipei",
	    'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
	    'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
	    'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
	    'Asia/Seoul'           => "(GMT+09:00) Seoul",
	    'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
	    'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
	    'Australia/Darwin'     => "(GMT+09:30) Darwin",
	    'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
	    'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
	    'Australia/Canberra'   => "(GMT+10:00) Canberra",
	    'Pacific/Guam'         => "(GMT+10:00) Guam",
	    'Australia/Hobart'     => "(GMT+10:00) Hobart",
	    'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
	    'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
	    'Australia/Sydney'     => "(GMT+10:00) Sydney",
	    'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
	    'Asia/Magadan'         => "(GMT+12:00) Magadan",
	    'Pacific/Auckland'     => "(GMT+12:00) Auckland",
	    'Pacific/Fiji'         => "(GMT+12:00) Fiji",
	);
    public static $langnames = array(
	    'aa' => 'Afar',
	    'ab' => 'Abkhaz',
	    'ae' => 'Avestan',
	    'af' => 'Afrikaans',
	    'ak' => 'Akan',
	    'am' => 'Amharic',
	    'an' => 'Aragonese',
	    'ar' => 'Arabic',
	    'as' => 'Assamese',
	    'av' => 'Avaric',
	    'ay' => 'Aymara',
	    'az' => 'Azerbaijani',
	    'ba' => 'Bashkir',
	    'be' => 'Belarusian',
	    'bg' => 'Bulgarian',
	    'bh' => 'Bihari',
	    'bi' => 'Bislama',
	    'bm' => 'Bambara',
	    'bn' => 'Bengali',
	    'bo' => 'Tibetan Standard, Tibetan, Central',
	    'br' => 'Breton',
	    'bs' => 'Bosnian',
	    'ca' => 'Catalan; Valencian',
	    'ce' => 'Chechen',
	    'ch' => 'Chamorro',
	    'co' => 'Corsican',
	    'cr' => 'Cree',
	    'cs' => 'Czech',
	    'cu' => 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic',
	    'cv' => 'Chuvash',
	    'cy' => 'Welsh',
	    'da' => 'Danish',
	    'de' => 'German',
	    'dv' => 'Divehi; Dhivehi; Maldivian;',
	    'dz' => 'Dzongkha',
	    'ee' => 'Ewe',
	    'el' => 'Greek, Modern',
	    'en' => 'English',
	    'eo' => 'Esperanto',
	    'es' => 'Spanish; Castilian',
	    'et' => 'Estonian',
	    'eu' => 'Basque',
	    'fa' => 'Persian',
	    'ff' => 'Fula; Fulah; Pulaar; Pular',
	    'fi' => 'Finnish',
	    'fj' => 'Fijian',
	    'fo' => 'Faroese',
	    'fr' => 'French',
	    'fy' => 'Western Frisian',
	    'ga' => 'Irish',
	    'gd' => 'Scottish Gaelic; Gaelic',
	    'gl' => 'Galician',
	    'gn' => 'GuaranÃ­',
	    'gu' => 'Gujarati',
	    'gv' => 'Manx',
	    'ha' => 'Hausa',
	    'he' => 'Hebrew (modern)',
	    'hi' => 'Hindi',
	    'ho' => 'Hiri Motu',
	    'hr' => 'Croatian',
	    'ht' => 'Haitian; Haitian Creole',
	    'hu' => 'Hungarian',
	    'hy' => 'Armenian',
	    'hz' => 'Herero',
	    'ia' => 'Interlingua',
	    'id' => 'Indonesian',
	    'ie' => 'Interlingue',
	    'ig' => 'Igbo',
	    'ii' => 'Nuosu',
	    'ik' => 'Inupiaq',
	    'io' => 'Ido',
	    'is' => 'Icelandic',
	    'it' => 'Italian',
	    'iu' => 'Inuktitut',
	    'ja' => 'Japanese (ja)',
	    'jv' => 'Javanese (jv)',
	    'ka' => 'Georgian',
	    'kg' => 'Kongo',
	    'ki' => 'Kikuyu, Gikuyu',
	    'kj' => 'Kwanyama, Kuanyama',
	    'kk' => 'Kazakh',
	    'kl' => 'Kalaallisut, Greenlandic',
	    'km' => 'Khmer',
	    'kn' => 'Kannada',
	    'ko' => 'Korean',
	    'kr' => 'Kanuri',
	    'ks' => 'Kashmiri',
	    'ku' => 'Kurdish',
	    'kv' => 'Komi',
	    'kw' => 'Cornish',
	    'ky' => 'Kirghiz, Kyrgyz',
	    'la' => 'Latin',
	    'lb' => 'Luxembourgish, Letzeburgesch',
	    'lg' => 'Luganda',
	    'li' => 'Limburgish, Limburgan, Limburger',
	    'ln' => 'Lingala',
	    'lo' => 'Lao',
	    'lt' => 'Lithuanian',
	    'lu' => 'Luba-Katanga',
	    'lv' => 'Latvian',
	    'mg' => 'Malagasy',
	    'mh' => 'Marshallese',
	    'mi' => 'Maori',
	    'mk' => 'Macedonian',
	    'ml' => 'Malayalam',
	    'mn' => 'Mongolian',
	    'mr' => 'Marathi (Mara?hi)',
	    'ms' => 'Malay',
	    'mt' => 'Maltese',
	    'my' => 'Burmese',
	    'na' => 'Nauru',
	    'nb' => 'Norwegian BokmÃ¥l',
	    'nd' => 'North Ndebele',
	    'ne' => 'Nepali',
	    'ng' => 'Ndonga',
	    'nl' => 'Dutch',
	    'nn' => 'Norwegian Nynorsk',
	    'no' => 'Norwegian',
	    'nr' => 'South Ndebele',
	    'nv' => 'Navajo, Navaho',
	    'ny' => 'Chichewa; Chewa; Nyanja',
	    'oc' => 'Occitan',
	    'oj' => 'Ojibwe, Ojibwa',
	    'om' => 'Oromo',
	    'or' => 'Oriya',
	    'os' => 'Ossetian, Ossetic',
	    'pa' => 'Panjabi, Punjabi',
	    'pi' => 'Pali',
	    'pl' => 'Polish',
	    'ps' => 'Pashto, Pushto',
	    'pt' => 'Portuguese',
	    'qu' => 'Quechua',
	    'rm' => 'Romansh',
	    'rn' => 'Kirundi',
	    'ro' => 'Romanian, Moldavian, Moldovan',
	    'ru' => 'Russian',
	    'rw' => 'Kinyarwanda',
	    'sa' => 'Sanskrit (Sa?sk?ta)',
	    'sc' => 'Sardinian',
	    'sd' => 'Sindhi',
	    'se' => 'Northern Sami',
	    'sg' => 'Sango',
	    'si' => 'Sinhala, Sinhalese',
	    'sk' => 'Slovak',
	    'sl' => 'Slovene',
	    'sm' => 'Samoan',
	    'sn' => 'Shona',
	    'so' => 'Somali',
	    'sq' => 'Albanian',
	    'sr' => 'Serbian',
	    'ss' => 'Swati',
	    'st' => 'Southern Sotho',
	    'su' => 'Sundanese',
	    'sv' => 'Swedish',
	    'sw' => 'Swahili',
	    'ta' => 'Tamil',
	    'te' => 'Telugu',
	    'tg' => 'Tajik',
	    'th' => 'Thai',
	    'ti' => 'Tigrinya',
	    'tk' => 'Turkmen',
	    'tl' => 'Tagalog',
	    'tn' => 'Tswana',
	    'to' => 'Tonga (Tonga Islands)',
	    'tr' => 'Turkish',
	    'ts' => 'Tsonga',
	    'tt' => 'Tatar',
	    'tw' => 'Twi',
	    'ty' => 'Tahitian',
	    'ug' => 'Uighur, Uyghur',
	    'uk' => 'Ukrainian',
	    'ur' => 'Urdu',
	    'uz' => 'Uzbek',
	    've' => 'Venda',
	    'vi' => 'Vietnamese',
	    'vo' => 'VolapÃ¼k',
	    'wa' => 'Walloon',
	    'wo' => 'Wolof',
	    'xh' => 'Xhosa',
	    'yi' => 'Yiddish',
	    'yo' => 'Yoruba',
	    'za' => 'Zhuang, Chuang',
	    'zh' => 'Chinese',
	    'zu' => 'Zulu',
	);

    public function __construct() {
   		// nothing
    }

    public static function loadLanguage($file){
		$language = Core::$site['language'];
		$lang = Lang::$langnames;
		if (file_exists($file)){
			require_once($file);
			Lang::$language[$language] = array();
			foreach ($lang as $code => $text) {
			    if (!array_key_exists($code, Lang::$language[$language])) {
			    	 Lang::$language[$language][$code] = $text;
			    }
			  }
		}

	}

	public static function mergeLanguage($file){
		$language = Core::$site['language'];
		$lang = Lang::$langnames;
		if (file_exists($file)){
			require_once($file);
			foreach ($lang as $code => $text) {
			    if (!array_key_exists($code, Lang::$language[$language])) {
			    	 Lang::$language[$language][$code] = $text;
			    }
			  }
		}

	}


	/**
	 * Get Current Language
	 *
	 * Returns the current system Language
	 * 	
	 * <code>
	 * 		$lang = Lang::getCurrentLanguage();
	 * </code>
	 *
	 * @return string Current System Language
	 */
	public static function getCurrentLanguage(){
		if (User::getLanguage()!=""){
			return Lang::$langnames[User::getLanguage()];
		} else {
			return Lang::$langnames[Core::$site['language']];
		}
		
	}


	/**
	 * Get a list of installed Languages
	 *
	 * Returns an array of currently installed languages
	 * 	
	 * <code>
	 * 		$lang = Lang::getCurrentLanguage();
	 * </code>
	 *
	 * @return array Current installed Languages
	 */
	public static function getInstalledLanguages(){
		$file_arr = array();
		$files = Core::getFiles(Core::getRootPath().'/addins/languages/','php');
		foreach ($files as $file){
			$file_arr[] = substr($file,0,2);
		}
		return $file_arr;
	}

	public static function showLanguageDropdown(){
		$languages = Lang::getInstalledLanguages();
		foreach ($languages as $lang){
			$uri = Core::curPageURL(); 
			if (strstr($uri, '?')){
				$setlang = '&setlang=';
			} else {
				$setlang = '?setlang=';
			}
			echo '<li value="'.$lang.'" ><a href="'.Core::curPageURL().$setlang.$lang.'">'.Lang::$langnames[$lang].'</a></li>';
		}
	}


	/**
	 * Language Translation
	 *
	 * Return a translated string 
	 * 	
	 * <code>
	 * 		$lang = Lang::langDisplay('TEST');
	 * </code>
	 *
	 * @return string Translated String or '** $name **'' if it does not exist 
	 */
	public static function langDisplay($name){
		if (array_key_exists(Core::$site['language'],Lang::$language ) && array_key_exists($name, Lang::$language[Core::$site['language']])){
			return Lang::$language[Core::$site['language']][$name] ;
			//return "&&&";
		} else {
			return "** ".$name." **";
		}
	}


}