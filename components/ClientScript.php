<?php

/**
 *  ClientScript extens CClientScript
 *  adds a imagetracker functionality to CClientScript
 *  
 * 
 */
class ClientScript extends CClientScript {
    /**
     * @var array the registered image trackers.
     */
    public $HTMLElements;
    
    public function init() {
        
    }
    
    public function registerHTMLElement( $id, $elemType, $position, $attributes, $body = '', $selfClosing = false ) {

        // Can't go in read or load
        if($position > 2) {
            $position = 2;
        }

        // Build the element
        $html = "<$elemType ";
        foreach( $attributes as $attribute => $value ) {
            $html .= "$attribute=\"$value\"";
        }
        $html .= ">";
        
        // close it
        if(!$selfClosing) {
            $html .= "$body</$elem>";
        } else {
            $html .= " />";
        }
        
        // register it
        $this->HTMLElements[$position][$id] = $html;
        $this->hasScripts = true;
        $params = func_get_args();
        $this->recordCachingAction( 'clientScript','registerHTMLlement', $params );
        return $this;
    }
    
    public function renderBodyEnd( &$output ) {
        if(isset($this->HTMLElements[self::POS_END])) {
            foreach( $this->HTMLElements[self::POS_END] as $id => $element) {
                $output .= $element . PHP_EOL;
            }
        }
        parent::renderBodyEnd($output);
    }
    
    /**
     * Inserts the scripts in the head section.
     * @param string $output the output to be inserted with scripts.
     */
    public function renderHead(&$output) {
        if(isset($this->HTMLElements[self::POS_HEAD])) {
            foreach( $this->HTMLElements[self::POS_HEAD] as $id => $element) {
                $output .= $element . PHP_EOL;
            }
        }
        parent::renderHead($output);
    }

    /**
     * Inserts the scripts at the beginning of the body section.
     * @param string $output the output to be inserted with scripts.
     */
    public function renderBodyBegin(&$output)
    {
        $html='';
        if(isset($this->scriptFiles[self::POS_BEGIN]))
        {
                foreach($this->scriptFiles[self::POS_BEGIN] as $scriptFile)
                        $html.=CHtml::scriptFile($scriptFile)."\n";
        }
        if(isset($this->scripts[self::POS_BEGIN]))
                $html.=CHtml::script(implode("\n",$this->scripts[self::POS_BEGIN]))."\n";

        if(isset($this->HTMLElements[self::POS_BEGIN])) {
            foreach($this->HTMLElements[self::POS_BEGIN] as $element) {
                $html .= $element . PHP_EOL;
            }
        }

        if($html!=='')
        {
                $count=0;
                $output=preg_replace('/(<body\b[^>]*>)/is','$1<###begin###>',$output,1,$count);
                if($count)
                        $output=str_replace('<###begin###>',$html,$output);
                else
                        $output=$html.$output;
        }
    }

}
