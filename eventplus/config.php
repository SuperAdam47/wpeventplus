<?php
/**
 * Config Class 
 * 
 * Loads config file based on path. Ability to load sys config and plugin config from apropriate folder
 *
 * @example 
 * $oConfig = new EventPlus_Config($config_name, $allowModifications,$path);
 * $config_array = $oConfig->toArray();
 */
class EventPlus_Config implements Countable, Iterator
{
    /**
     * 
     * The config values built for a class, including inheritance from its
     * parent class configs.
     * 
     * @var array
     * 
     * @see setBuild()
     * 
     * @see getBuild()
     * 
     */
    static protected $_build = array();
    
    
    /**
     * Whether in-memory modifications to configuration data are allowed
     *
     * @var boolean
     */
    protected $_allowModifications;

    /**
     * Iteration index
     *
     * @var integer
     */
    protected $_index;

    /**
     * Number of elements in configuration data
     *
     * @var integer
     */
    protected $_count;

    /**
     * Contains array of configuration data
     *
     * @var array
     */
    protected $_data;

    /**
     * Used when unsetting values during iteration to ensure we do not skip
     * the next element
     *
     * @var boolean
     */
    protected $_skipNextIteration;

    /**
     * Contains which config file sections were loaded. This is null
     * if all sections were loaded, a string name if one section is loaded
     * and an array of string names if multiple sections were loaded.
     *
     * @var mixed
     */
    protected $_loadedSection;

    /**
     * This is used to track section inheritance. The keys are names of sections that
     * extend other sections, and the values are the extended sections.
     *
     * @var array
     */
    protected $_extends = array();

    /**
     * Load file error string.
     *
     * Is null if there was no error while file loading
     *
     * @var string
     */
    protected $_loadFileErrorStr = null;

    /**
     * clsConfig provides a property based interface to
     * an array. The data are read-only unless $allowModifications
     * is set to true on construction.
     *
     * clsConfig also implements Countable and Iterator to
     * facilitate easy access to the data.
     *
     * @param  array   $array
     * @param  boolean $allowModifications
     * @return void
     */
    public function __construct($config_file, $allowModifications = false)
    {
		
        $array = $config_file;

        if(is_array($config_file) == false )
        {
            if($path == null)
            {
                $path = CORE_CONFIG_PATH;
            }

            $config_file_path = $path . "config." . $config_file . ".php";
        
            if(file_exists($config_file_path) == false)
            {
                throw new expConfig("".ucfirst($config_file)." - Configuration file Not Found");
                return;
            }

            $array = include($config_file_path);			
        }

		
	$this->_allowModifications = (boolean) $allowModifications;
        $this->_loadedSection = null;
        $this->_index = 0;
        $this->_data = array();
		
        if(is_array($array)){
                foreach ($array as $key => $value) {
                        if (is_array($value)) {
                                $this->_data[$key] = new self($value, $this->_allowModifications);
                        } else {
                                $this->_data[$key] = $value;
                        }
                }
        }
        $this->_count = count($this->_data);
        
    }
    
    public static function getSystemConfig($config_file)
    {
        return new clsConfig($config_file, false, CORE_CONFIG_PATH);
    }
	

    /**
     * Retrieve a value and return $default if there is no element set.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $result = $default;
        if (array_key_exists($name, $this->_data)) {
            $result = $this->_data[$name];
        }
        return $result;
    }

    /**
     * Magic function so that $obj->value will work.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Only allow setting of a property if $allowModifications
     * was set to true on construction. Otherwise, throw an exception.
     *
     * @param  string $name
     * @param  mixed  $value
     * @throws clsConfig_Exception
     * @return void
     */
    public function __set($name, $value)
    {
        if ($this->_allowModifications) {
            if (is_array($value)) {
                $this->_data[$name] = new self($value, true);
            } else {
                $this->_data[$name] = $value;
            }
            $this->_count = count($this->_data);
        } else {
            throw new expConfig('Config is read only');
        }
    }

    /**
     * Deep clone of this instance to ensure that nested clsConfigs
     * are also cloned.
     *
     * @return void
     */
    public function __clone()
    {
      $array = array();
      foreach ($this->_data as $key => $value) {
          if ($value instanceof clsConfig) {
              $array[$key] = clone $value;
          } else {
              $array[$key] = $value;
          }
      }
      $this->_data = $array;
    }

    /**
     * Return an associative array of the stored data.
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        $data = $this->_data;
        foreach ($data as $key => $value) {
            if ($value instanceof clsConfig) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    /**
     * Support isset() overloading on PHP 5.1
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * Support unset() overloading on PHP 5.1
     *
     * @param  string $name
     * @throws clsConfig_Exception
     * @return void
     */
    public function __unset($name)
    {
        if ($this->_allowModifications) {
            unset($this->_data[$name]);
            $this->_count = count($this->_data);
            $this->_skipNextIteration = true;
        } else {
           throw new expConfig('Config is read only');
        }

    }

    /**
     * Defined by Countable interface
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function current()
    {
        $this->_skipNextIteration = false;
        return current($this->_data);
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->_data);
    }

    /**
     * Defined by Iterator interface
     *
     */
    public function next()
    {
        if ($this->_skipNextIteration) {
            $this->_skipNextIteration = false;
            return;
        }
        next($this->_data);
        $this->_index++;
    }

    /**
     * Defined by Iterator interface
     *
     */
    public function rewind()
    {
        $this->_skipNextIteration = false;
        reset($this->_data);
        $this->_index = 0;
    }

    /**
     * Defined by Iterator interface
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->_index < $this->_count;
    }

    /**
     * Returns the section name(s) loaded.
     *
     * @return mixed
     */
    public function getSectionName()
    {
        if(is_array($this->_loadedSection) && count($this->_loadedSection) == 1) {
            $this->_loadedSection = $this->_loadedSection[0];
        }
        return $this->_loadedSection;
    }

    /**
     * Returns true if all sections were loaded
     *
     * @return boolean
     */
    public function areAllSectionsLoaded()
    {
        return $this->_loadedSection === null;
    }


    /**
     * Merge another clsConfig with this one. The items
     * in $merge will override the same named items in
     * the current config.
     *
     * @param clsConfig $merge
     * @return clsConfig
     */
    public function merge(clsConfig $merge)
    {
        foreach($merge as $key => $item) {
            if(array_key_exists($key, $this->_data)) {
                if($item instanceof clsConfig && $this->$key instanceof clsConfig) {
                    $this->$key = $this->$key->merge(new clsConfig($item->toArray(), !$this->readOnly()));
                } else {
                    $this->$key = $item;
                }
            } else {
                if($item instanceof clsConfig) {
                    $this->$key = new clsConfig($item->toArray(), !$this->readOnly());
                } else {
                    $this->$key = $item;
                }
            }
        }

        return $this;
    }

    /**
     * Prevent any more modifications being made to this instance. Useful
     * after merge() has been used to merge multiple clsConfig objects
     * into one object which should then not be modified again.
     *
     */
    public function setReadOnly()
    {
        $this->_allowModifications = false;
        foreach ($this->_data as $key => $value) {
            if ($value instanceof clsConfig) {
                $value->setReadOnly();
            }
        }
    }

    /**
     * Returns if this clsConfig object is read only or not.
     *
     * @return boolean
     */
    public function readOnly()
    {
        return !$this->_allowModifications;
    }

    /**
     * Get the current extends
     *
     * @return array
     */
    public function getExtends()
    {
        return $this->_extends;
    }

    /**
     * Set an extend for clsConfig_Writer
     *
     * @param  string $extendingSection
     * @param  string $extendedSection
     * @return void
     */
    public function setExtend($extendingSection, $extendedSection = null)
    {
        if ($extendedSection === null && isset($this->_extends[$extendingSection])) {
            unset($this->_extends[$extendingSection]);
        } else if ($extendedSection !== null) {
            $this->_extends[$extendingSection] = $extendedSection;
        }
    }

    /**
     * Throws an exception if $extendingSection may not extend $extendedSection,
     * and tracks the section extension if it is valid.
     *
     * @param  string $extendingSection
     * @param  string $extendedSection
     * @throws clsConfig_Exception
     * @return void
     */
    protected function _assertValidExtend($extendingSection, $extendedSection)
    {
        // detect circular section inheritance
        $extendedSectionCurrent = $extendedSection;
        while (array_key_exists($extendedSectionCurrent, $this->_extends)) {
            if ($this->_extends[$extendedSectionCurrent] == $extendingSection) {
                throw new expConfig('Illegal circular inheritance detected');
            }
            $extendedSectionCurrent = $this->_extends[$extendedSectionCurrent];
        }
        // remember that this section extends another section
        $this->_extends[$extendingSection] = $extendedSection;
    }

    /**
     * Handle any errors from simplexml_load_file or parse_ini_file
     *
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param integer $errline
     */
    protected function _loadFileErrorHandler($errno, $errstr, $errfile, $errline)
    {
        if ($this->_loadFileErrorStr === null) {
            $this->_loadFileErrorStr = $errstr;
        } else {
            $this->_loadFileErrorStr .= (PHP_EOL . $errstr);
        }
    }

    /**
     * Merge two arrays recursively, overwriting keys of the same name
     * in $firstArray with the value in $secondArray.
     *
     * @param  mixed $firstArray  First array
     * @param  mixed $secondArray Second array to merge into first array
     * @return array
     */
    protected function _arrayMergeRecursive($firstArray, $secondArray)
    {
        if (is_array($firstArray) && is_array($secondArray)) {
            foreach ($secondArray as $key => $value) {
                if (isset($firstArray[$key])) {
                    $firstArray[$key] = $this->_arrayMergeRecursive($firstArray[$key], $value);
                } else {
                    if($key === 0) {
                        $firstArray= array(0=>$this->_arrayMergeRecursive($firstArray, $value));
                    } else {
                        $firstArray[$key] = $value;
                    }
                }
            }
        } else {
            $firstArray = $secondArray;
        }

        return $firstArray;
    }
    
    /**
     * 
     * Sets the build config to retain for a class.
     * 
     * **DO NOT** use this unless you know what you're doing.  The only reason
     * this is here is for iceBase::_buildConfig() to use it.
     * 
     * @param string $class The class name.
     * 
     * @param array $config Configuration value overrides, if any.
     * 
     * @return void
     * 
     */
    static public function setBuild($class, $config)
    {
        clsConfig::$_build[$class] = (array) $config;
    }
    
    /**
     * 
     * Gets the retained build config for a class.
     * 
     * **DO NOT** use this unless you know what you're doing.  The only reason
     * this is here is for iceBase::_buildConfig() to use it.
     * 
     * @param string $class The class name to get the config build for.
     * 
     * @return mixed An array of retained config built for the class, or null
     * if there's no build for it.
     * 
     */
    static public function getBuild($class)
    {
        if (array_key_exists($class, clsConfig::$_build)) {
            return clsConfig::$_build[$class];
        }
    }
}