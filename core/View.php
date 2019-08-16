<?php 
    namespace Core;

    class View {

        protected $head, $body, $outputBuffer, $siteTitle = SITE_TITLE, $layout = DEFAULT_LAYOUT; 
        public $urlParams, $product_rating, $success = '', $danger = '';

        public function __construct() {}

        public function render($viewName) {
            $viewNameArray = explode('/', $viewName);
            $viewNamePath = implode(DS, $viewNameArray);

            if(file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $viewNamePath . '.php')) {
                include(ROOT . DS . 'app' . DS . 'views' . DS . $viewNamePath . '.php');
                include(ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->layout . '.php');
            } else {
                die('The view '.$viewNamePath.' does not exist');
            }

        }

        public function content($type) {
            if($type === 'head') {
                return $this->head;
            } elseif($type === 'body') {
                return $this->body;
            }
            return false;
        }

        public function siteTitle() {
            return $this->siteTitle;
        }

        public function setSiteTitle($title) {
            $this->siteTitle = $title;
        }

        public function setLayout($path) {
            $this->layout = $path;
        }
        
        public function start($type) {
            $this->outputBuffer = $type;
            ob_start();
        }

        public function end() {
            if($this->outputBuffer === 'head') {
                $this->head = ob_get_clean();
            } elseif($this->outputBuffer === 'body') {
                $this->body = ob_get_clean();
            } else {
                die('Run the start method first');
            }
        }

        public function components($components) {
            $componentsArray = explode('/', $components);
            $componentsPath = implode(DS, $componentsArray);
            if(file_exists(ROOT .DS. 'app' .DS. 'views' .DS. 'store' .DS. 'components' .DS. $componentsPath . '.php')) {
                include(ROOT .DS. 'app' .DS. 'views' .DS. 'store' .DS. 'components' .DS. $componentsPath . '.php');
            }
        }

        public function gComponents($components) {
            $componentsArray = explode('/', $components);
            $componentsPath = implode(DS, $componentsArray);
            if(file_exists(ROOT .DS. 'app' .DS. 'views' .DS. 'components' .DS. $componentsPath . '.php')) {
                include(ROOT .DS. 'app' .DS. 'views' .DS. 'components' .DS. $componentsPath . '.php');
            }
        }

        public function successMsg() {
            if ($this->success === '') {
                return '';
            }
            return '<span class="success-alert">'.$this->success.'</span>';
        }

        public function errorMsg($error = '') {
            $err = $error;
            if ($this->danger === '' && $error === '') {
                return '';
            } elseif ($this->danger != '') {
                $err = $this->danger;
            }
            return '<span class="error-alert">'.$err.'</span>';
        }

        public function setFormErrors($errors = []) {
            foreach($errors as $error) {
                $this->{$error[1].'_error'} = '<span class="inputError">'.$error[0].'</span>';
            }
        }

        public function rate($value) {
            switch($value) {
                case ($value === 0) :
                    return file_get_contents(ROOT.'/public/images/svg/0_star.svg');
                    break;
                
                case ($value >= 0.5 && $value < 1) :
                    return file_get_contents(ROOT.'/public/images/svg/0_5_star.svg');
                    break;
                    
                case ($value >= 1 && $value < 1.5) :
                    return file_get_contents(ROOT.'/public/images/svg/1_star.svg');
                    break;
                
                case ($value >= 1.5 && $value < 2) :
                    return file_get_contents(ROOT.'/public/images/svg/1_5_star.svg');
                    break;
                    
                case ($value >= 2 && $value < 2.5) :
                    return file_get_contents(ROOT.'/public/images/svg/2_star.svg');
                    break;
                
                case ($value >= 2.5 && $value < 3) :
                    return file_get_contents(ROOT.'/public/images/svg/2_5_star.svg');
                    break;
                    
                case ($value >= 3 && $value < 3.5) :
                    return  file_get_contents(ROOT.'/public/images/svg/3_star.svg');
                    break;
                
                case ($value >= 3.5 && $value < 4) :
                    return file_get_contents(ROOT.'/public/images/svg/3_5_star.svg');
                    break;
                    
                case ($value >= 4 && $value < 4.5) :
                    return file_get_contents(ROOT.'/public/images/svg/4_star.svg');
                    break;
                
                case ($value >= 4.5 && $value < 5) :
                    return file_get_contents(ROOT.'/public/images/svg/4_5_star.svg');
                    break;

                case ($value == 5) :
                    return file_get_contents(ROOT.'/public/images/svg/5_star.svg');
                    break;
            }
        }

    }
?>