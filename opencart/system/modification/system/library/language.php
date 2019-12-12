<?php
class Language {
	private $default = 'english';
	private $directory;
	private $data = array();
    private $filename =''; #mijoshop

	public function __construct($directory = '') {
		$this->directory = $directory;
	}

	public function get($key) {

        #mijoshop-start
        $file_name  = $this->filename;
        $trace      = debug_backtrace();

        if (!empty($trace)) {
            $file_path  = $trace[0]['file'];
            $file_path  = str_replace('\\', '/', $file_path);
            $file_path  = str_replace('.php', '', $file_path);

            $as_file            = strpos($file_path, str_replace('\\','/',$file_name));

            if ($as_file === false) {
                if (strpos($file_path, 'modification') !== false){
                    $_file_name = strstr($file_path, 'modification');
                }
                else {
                    $_file_name = strstr($file_path, 'opencart');
                }

                $_path_array = explode('/', $_file_name);
                $path_array = array();
                if(count($_path_array) > 4) {
                    $path_array[0] = $_path_array[3];
                    $path_array[1] = $_path_array[4];
                    $file_name = implode('/', $path_array);
                }
            }
        }

        $string = 'COM_MIJOSHOP_'.strtoupper(str_replace('/', '_', $this->filename)).'_'.strtoupper($key);
        $text = JText::_($string);

        if ( ($text == $string or $text == '??'. $string .'??') and !JFactory::getApplication()->isAdmin() and !(isset($_GET['view']) and $_GET['view'] == 'admin') ) {
            $string = 'COM_MIJOSHOP_'.strtoupper(str_replace('/', '_', $file_name)).'_'.strtoupper($key);
            $text = JText::_($string);
        }

        if (isset($path_array[0]) and $path_array[0] == 'checkout' and ($text == $string or $text == '??'. $string .'??')) {
            $string = 'COM_MIJOSHOP_'.strtoupper(str_replace('/', '_', 'checkout/checkout')).'_'.strtoupper($key);
            $text = JText::_($string);
        }

        if ($text == $string or $text == '??'. $string .'??') {
            $string = 'COM_MIJOSHOP_'.strtoupper($key);
            $text = JText::_($string);
        }

        if (($text != $string) and ($text != '??'. $string .'??')) {
            return $text;
        }

        $lang = JFactory::getLanguage();
        $_directory =strtolower($lang->getTag());
        $_directory =str_replace('-', '_', $_directory);
        $text = $this->loadSecond($_directory, $this->filename, $key);

        if (!empty($text)) {
            return $text;
        }
        #mijoshop-end
            
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}
	

    #mijoshop-start
    public function loadSecond($directory, $filename, $key) {
        $_ = array();

        $file = DIR_LANGUAGE . $directory . '/' . $filename . '.php';
        if (file_exists($file)) {
            require(modification($file));
        }

        return (isset($_[$key]) ? $_[$key] : '');
    }
    #mijoshop-end
            

    #mijoshop-start
    public function all($data = array(), $skip = array()) {
        foreach ($this->data as $key => $value) {
            // Don't add if the key found in the skip list
            if (in_array($key, $skip)) {
                continue;
            }

            $data[$key] = $value;
        }

        return $data;
    }
    #mijoshop-end
	
	public function load($filename) {

        #mijoshop-start
        if($filename == 'english') {
            $filename = 'default';
        }

        $this->filename = $filename;
        #mijoshop-end
        
		$_ = array();

		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';

		if (file_exists($file)) {
			require(modification($file));
		}

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';

		if (file_exists($file)) {
			require(modification($file));
		}

		$this->data = array_merge($this->data, $_);

		return $this->data;
	}
}