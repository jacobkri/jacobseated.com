<?php

// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
// ✨✨✨✨✨✨✨😎 Class to handle custom modules 😎✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

// The main purpose of this class is to scan output for "[module=some_module_name]"
// and replace [] sections with whatever is returned by the custom script
// this is useful in cases where you need pages to be less "static"

// This class will dynamically load and instantiate supplied modules (classes are located in lib/modules)
// See the "settings_handler_class" for relevant paths.


class module_handler {
    private $s;

    public function __construct($settings) {
      $this->s = $settings;
    }

    public function load($input_text) {     
      preg_match_all("/\[module=([A-Za-z0-9_-]{1,100})\]/", $input_text, $matches, PREG_PATTERN_ORDER);
      $modulesArr = $matches[1]; // Save module names as an array

      $data_for_output = $input_text; // Same as input, useful in case of error or no output
      foreach($modulesArr as $module_name) { // Replace each module block with output from module
        $this->include($module_name);
        $mout = $this->run($module_name);
        if ($mout !== false) {
          // Replace [] block in the supplied text input (usually a filled HTML template)
          $data_for_output = str_replace('[module='.$module_name.']', $mout, $data_for_output);
        }
       }
       return $data_for_output;
    }

    private function include($module_name) {
      // Dynamically included module classes should only be included once hence "require_once".
      $m_file_path = $this->s->modules_dir . $module_name . '.php';
      if(file_exists($m_file_path)) {
        require_once $this->s->modules_dir . $module_name . '.php';
      } else {
        header($this->s->protocol . ' 500 Internal Server Error');
        echo 'Error: one or more modules failed loading.';exit();
      }
    }

    private function run($module_name) {
        $module = new $module_name(); // new SimpleClass()
        $mout = $module->run(); // module output (false if no output)
        return $mout;
    }

}