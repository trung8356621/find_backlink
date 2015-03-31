<?php

//Class duyệt toàn bộ link trên web
class Check_Link_In {

    private $link;
//list ipv4 except
    private $ipv4;
//list ipv4 except
    private $ipv4_dad;
//danh sách toàn bộ link từ domain(thô)
    private $list_Link;
//danh sach backlink
    public $list_bl;
    public $options = [
        'http' => [
            'method' => 'GET',
            'timeout' => '1000'
        ]
    ];
    private $check_info;
    private $bl_tit;

    function __construct($link, $ipv4) {
        $this->ipv4 = $ipv4;
        $seg2 = explode('/', $link);
        if (isset($seg2[2])) {
            $this->check_info = $this->split_domain($seg2[2]);
            $this->link = $link;
            $this->get_Link();
            $this->filter_LinkChild();
        }
    }

    public function get_Link() {
//set time and method
        libxml_set_streams_context(stream_context_create($this->options));
//crate new DOM
        $dom = new DOMDocument('1.1', 'utf-8');
//kill error
        libxml_use_internal_errors(true);
//load new 
        @$dom->loadHTMLFile($this->link);
//create new xPath
        $xPath = new DOMXPath($dom);
//Lấy danh sách result trừ domain được search bing
        $lst_Pro = $xPath->query('//a');
        if ($lst_Pro->length > 0) {
            $this->bl_tit = @$xPath->query('//title')->item(0)->nodeValue;
            foreach ($lst_Pro as $key => $value) {
//tên
                $img = $xPath->query('.//img', $value);
                if ($img->length <= 0) {
                    $this->list_Link[$key]['name'] = @$xPath->query('.//text()', $value)->item(0)->nodeValue;
                } else {
                    $this->list_Link[$key]['name'] = 'txt_img';
                }
//link
                $this->list_Link[$key]['link'] = @$xPath->query('.//@href', $value)->item(0)->nodeValue;
                $this->list_Link[$key]['follow'] = !empty(@$xPath->query('.//@ref', $value)->item(0)->nodeValue) ? 1 : 0;
            }
        }
    }

    public function filter_LinkChild() {
        if (count($this->list_Link) > 0) {
            foreach ($this->list_Link as $value) {
                if (strpos($value['link'], 'http://') !== false || strpos($value['link'], 'https://') !== false) {
                    $res = explode('/', $value['link']);
                    if (isset($res[2])) {
                        $ipv4 = gethostbyname(Check_Link::remove_http($res[2]));
                        if ($ipv4 == $this->ipv4) {
                            $this->list_bl[] = 'INSERT INTO backlink VALUES (CURDATE(),"' . $this->link . '","' . $this->bl_tit . '","' . $value['link'] . '","' . $value['name'] . '",' . $this->check_info['type'] . ',"' . $this->check_info['con'] . '",' . $this->check_info['edu'] . ',0,' . $value['follow'] . ',"' . $this->ipv4 . '")';
                        }
                    }
                }
            }
            $s_l = new s_link();
            $s_l->save_bl_spec($this->list_bl);
        }
    }

//tách domain ra để lấy từ khóa so sánh
    function split_domain($main_domain) {
        $exp_domain = explode('.', $main_domain);
        $len_domain = count($exp_domain);
        /*
         * 1 - 4 duo
         */
        $a_result = array();
        if ($len_domain == 4) {
            //trường hợp 1 đầy đủ 3 thành phần
            $a_result['con'] = $this->check_country($exp_domain[3]);
            $a_result['edu'] = $this->check_edu($exp_domain[2]);
            $a_result['type'] = $a_result['con'] != 0 ? 7 : $this->check_public($exp_domain[3]);
        } else if ($len_domain == 3) {
            if (isset($this->list_public[strtolower($exp_domain[1])])) {
                //có 2 thành phần (đuôi gộp)
                $a_result['con'] = $this->check_country($exp_domain[2]);
                $a_result['edu'] = $this->check_edu($exp_domain[1]);
                $a_result['type'] = $a_result['con'] != 0 ? 7 : $this->check_public($exp_domain[2]);
            } else {
                $a_result['con'] = $this->check_country($exp_domain[2]);
                $a_result['edu'] = 0;
                $a_result['type'] = $a_result['con'] != 0 ? 7 : $this->check_public($exp_domain[2]);
            }
        } else if ($len_domain == 2) {
            $a_result['con'] = $this->check_country($exp_domain[1]);
            $a_result['edu'] = 0;
            $a_result['type'] = $a_result['con'] != 0 ? 7 : $this->check_public($exp_domain[1]);
        }
        return $a_result;
    }

    function check_country($val) {
        if (isset($this->list_country[strtoupper($val)])) {
            return $this->list_country[strtoupper($val)];
        } else {
            return isset($this->list_public_end[strtolower($val)]) ? $this->list_public_end[strtolower($val)] : 0;
        }
    }

    function check_public($val) {
        if (isset($this->list_public_end[strtolower($val)])) {
            return $this->list_public_end[strtolower($val)];
        } else {
            return 8;
        }
    }

    function check_edu($val) {
        return $val == 'edu' ? 1 : 0;
    }

    private $list_public_end = array(
        'com' => 1,
        'net' => 2,
        'org' => 3,
    );
    private $list_public = array(
        'com' => 1,
        'net' => 2,
        'org' => 3,
        'info' => 4,
        'edu' => 5,
        'mobi' => 6
    );
    private $list_country = array(
        '' => '',
        'AF' => 'AFGHANISTAN',
        'AX' => 'ÅLAND ISLANDS',
        'AL' => 'ALBANIA',
        'DZ' => 'ALGERIA',
        'AS' => 'AMERICAN SAMOA',
        'AD' => 'ANDORRA',
        'AO' => 'ANGOLA',
        'AI' => 'ANGUILLA',
        'AQ' => 'ANTARCTICA',
        'AG' => 'ANTIGUA AND BARBUDA',
        'AR' => 'ARGENTINA',
        'AM' => 'ARMENIA',
        'AW' => 'ARUBA',
        'AU' => 'AUSTRALIA',
        'AT' => 'AUSTRIA',
        'AZ' => 'AZERBAIJAN',
        'BS' => 'BAHAMAS',
        'BH' => 'BAHRAIN',
        'BD' => 'BANGLADESH',
        'BB' => 'BARBADOS',
        'BY' => 'BELARUS',
        'BE' => 'BELGIUM',
        'BZ' => 'BELIZE',
        'BJ' => 'BENIN',
        'BM' => 'BERMUDA',
        'BT' => 'BHUTAN',
        'BO' => 'BOLIVIA',
        'BA' => 'BOSNIA AND HERZEGOVINA',
        'BW' => 'BOTSWANA',
        'BV' => 'BOUVET ISLAND',
        'BR' => 'BRAZIL',
        'IO' => 'BRITISH INDIAN OCEAN TERRITORY',
        'BN' => 'BRUNEI DARUSSALAM',
        'BG' => 'BULGARIA',
        'BF' => 'BURKINA FASO',
        'BI' => 'BURUNDI',
        'KH' => 'CAMBODIA',
        'CM' => 'CAMEROON',
        'CA' => 'CANADA',
        'CV' => 'CAPE VERDE',
        'KY' => 'CAYMAN ISLANDS',
        'CF' => 'CENTRAL AFRICAN REPUBLIC',
        'TD' => 'CHAD',
        'CL' => 'CHILE',
        'CN' => 'CHINA',
        'CX' => 'CHRISTMAS ISLAND',
        'CC' => 'COCOS (KEELING) ISLANDS',
        'CO' => 'COLOMBIA',
        'KM' => 'COMOROS',
        'CG' => 'CONGO',
        'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
        'CK' => 'COOK ISLANDS',
        'CR' => 'COSTA RICA',
        'CI' => 'CÔTE DIVOIRE',
        'HR' => 'CROATIA',
        'CU' => 'CUBA',
        'CY' => 'CYPRUS',
        'CZ' => 'CZECH REPUBLIC',
        'DK' => 'DENMARK',
        'DJ' => 'DJIBOUTI',
        'DM' => 'DOMINICA',
        'DO' => 'DOMINICAN REPUBLIC',
        'EC' => 'ECUADOR',
        'EG' => 'EGYPT',
        'SV' => 'EL SALVADOR',
        'GQ' => 'EQUATORIAL GUINEA',
        'ER' => 'ERITREA',
        'EE' => 'ESTONIA',
        'ET' => 'ETHIOPIA',
        'FK' => 'FALKLAND ISLANDS (MALVINAS)',
        'FO' => 'FAROE ISLANDS',
        'FJ' => 'FIJI',
        'FI' => 'FINLAND',
        'FR' => 'FRANCE',
        'GF' => 'FRENCH GUIANA',
        'PF' => 'FRENCH POLYNESIA',
        'TF' => 'FRENCH SOUTHERN TERRITORIES',
        'GA' => 'GABON',
        'GM' => 'GAMBIA',
        'GE' => 'GEORGIA',
        'DE' => 'GERMANY',
        'GH' => 'GHANA',
        'GI' => 'GIBRALTAR',
        'GR' => 'GREECE',
        'GL' => 'GREENLAND',
        'GD' => 'GRENADA',
        'GP' => 'GUADELOUPE',
        'GU' => 'GUAM',
        'GT' => 'GUATEMALA',
        'GN' => 'GUINEA',
        'GW' => 'GUINEA-BISSAU',
        'GY' => 'GUYANA',
        'HT' => 'HAITI',
        'HM' => 'HEARD ISLAND AND MCDONALD ISLANDS',
        'VA' => 'HOLY SEE (VATICAN CITY STATE)',
        'HN' => 'HONDURAS',
        'HK' => 'HONG KONG',
        'HU' => 'HUNGARY',
        'IS' => 'ICELAND',
        'IN' => 'INDIA',
        'ID' => 'INDONESIA',
        'IR' => 'IRAN, ISLAMIC REPUBLIC OF',
        'IQ' => 'IRAQ',
        'IE' => 'IRELAND',
        'IL' => 'ISRAEL',
        'IT' => 'ITALY',
        'JM' => 'JAMAICA',
        'JP' => 'JAPAN',
        'JO' => 'JORDAN',
        'KZ' => 'KAZAKHSTAN',
        'KE' => 'KENYA',
        'KI' => 'KIRIBATI',
        'KP' => 'KOREA, DEMOCRATIC PEOPLE`S REPUBLIC OF',
        'KR' => 'KOREA, REPUBLIC OF',
        'KW' => 'KUWAIT',
        'KG' => 'KYRGYZSTAN',
        'LA' => 'LAO PEOPLE`S DEMOCRATIC REPUBLIC',
        'LV' => 'LATVIA',
        'LB' => 'LEBANON',
        'LS' => 'LESOTHO',
        'LR' => 'LIBERIA',
        'LY' => 'LIBYAN ARAB JAMAHIRIYA',
        'LI' => 'LIECHTENSTEIN',
        'LT' => 'LITHUANIA',
        'LU' => 'LUXEMBOURG',
        'MO' => 'MACAO',
        'MK' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',
        'MG' => 'MADAGASCAR',
        'MW' => 'MALAWI',
        'MY' => 'MALAYSIA',
        'MV' => 'MALDIVES',
        'ML' => 'MALI',
        'MT' => 'MALTA',
        'MH' => 'MARSHALL ISLANDS',
        'MQ' => 'MARTINIQUE',
        'MR' => 'MAURITANIA',
        'MU' => 'MAURITIUS',
        'YT' => 'MAYOTTE',
        'MX' => 'MEXICO',
        'FM' => 'MICRONESIA, FEDERATED STATES OF',
        'MD' => 'MOLDOVA, REPUBLIC OF',
        'MC' => 'MONACO',
        'MN' => 'MONGOLIA',
        'MS' => 'MONTSERRAT',
        'MA' => 'MOROCCO',
        'MZ' => 'MOZAMBIQUE',
        'MM' => 'MYANMAR',
        'NA' => 'NAMIBIA',
        'NR' => 'NAURU',
        'NP' => 'NEPAL',
        'NL' => 'NETHERLANDS',
        'AN' => 'NETHERLANDS ANTILLES',
        'NC' => 'NEW CALEDONIA',
        'NZ' => 'NEW ZEALAND',
        'NI' => 'NICARAGUA',
        'NE' => 'NIGER',
        'NG' => 'NIGERIA',
        'NU' => 'NIUE',
        'NF' => 'NORFOLK ISLAND',
        'MP' => 'NORTHERN MARIANA ISLANDS',
        'NO' => 'NORWAY',
        'OM' => 'OMAN',
        'PK' => 'PAKISTAN',
        'PW' => 'PALAU',
        'PS' => 'PALESTINIAN TERRITORY, OCCUPIED',
        'PA' => 'PANAMA',
        'PG' => 'PAPUA NEW GUINEA',
        'PY' => 'PARAGUAY',
        'PE' => 'PERU',
        'PH' => 'PHILIPPINES',
        'PN' => 'PITCAIRN',
        'PL' => 'POLAND',
        'PT' => 'PORTUGAL',
        'PR' => 'PUERTO RICO',
        'QA' => 'QATAR',
        'RE' => 'RÉUNION',
        'RO' => 'ROMANIA',
        'RU' => 'RUSSIAN FEDERATION',
        'RW' => 'RWANDA',
        'SH' => 'SAINT HELENA',
        'KN' => 'SAINT KITTS AND NEVIS',
        'LC' => 'SAINT LUCIA',
        'PM' => 'SAINT PIERRE AND MIQUELON',
        'VC' => 'SAINT VINCENT AND THE GRENADINES',
        'WS' => 'SAMOA',
        'SM' => 'SAN MARINO',
        'ST' => 'SAO TOME AND PRINCIPE',
        'SA' => 'SAUDI ARABIA',
        'SN' => 'SENEGAL',
        'CS' => 'SERBIA AND MONTENEGRO',
        'SC' => 'SEYCHELLES',
        'SL' => 'SIERRA LEONE',
        'SG' => 'SINGAPORE',
        'SK' => 'SLOVAKIA',
        'SI' => 'SLOVENIA',
        'SB' => 'SOLOMON ISLANDS',
        'SO' => 'SOMALIA',
        'ZA' => 'SOUTH AFRICA',
        'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
        'ES' => 'SPAIN',
        'LK' => 'SRI LANKA',
        'SD' => 'SUDAN',
        'SR' => 'SURINAME',
        'SJ' => 'SVALBARD AND JAN MAYEN',
        'SZ' => 'SWAZILAND',
        'SE' => 'SWEDEN',
        'CH' => 'SWITZERLAND',
        'SY' => 'SYRIAN ARAB REPUBLIC',
        'TW' => 'TAIWAN, PROVINCE OF CHINA',
        'TJ' => 'TAJIKISTAN',
        'TZ' => 'TANZANIA, UNITED REPUBLIC OF',
        'TH' => 'THAILAND',
        'TL' => 'TIMOR-LESTE',
        'TG' => 'TOGO',
        'TK' => 'TOKELAU',
        'TO' => 'TONGA',
        'TT' => 'TRINIDAD AND TOBAGO',
        'TN' => 'TUNISIA',
        'TR' => 'TURKEY',
        'TM' => 'TURKMENISTAN',
        'TC' => 'TURKS AND CAICOS ISLANDS',
        'TV' => 'TUVALU',
        'UG' => 'UGANDA',
        'UA' => 'UKRAINE',
        'AE' => 'UNITED ARAB EMIRATES',
        'GB' => 'UNITED KINGDOM',
        'US' => 'UNITED STATES',
        'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
        'UY' => 'URUGUAY',
        'UZ' => 'UZBEKISTAN',
        'VU' => 'VANUATU',
        'VA' => 'Vatican City State',
        'VE' => 'VENEZUELA',
        'VN' => 'VIỆT NAM',
        'VG' => 'VIRGIN ISLANDS, BRITISH',
        'VI' => 'VIRGIN ISLANDS, U.S.',
        'WF' => 'WALLIS AND FUTUNA',
        'EH' => 'WESTERN SAHARA',
        'YE' => 'YEMEN',
        'ZM' => 'ZAMBIA',
        'ZW' => 'ZIMBABWE',
    );

}
