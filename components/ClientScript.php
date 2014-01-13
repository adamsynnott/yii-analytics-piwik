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

        // can't go in read or load
        if($position > 2) {
            $position = 2;
        }

        // build the element
        $html = "<$elemType ";
        foreach( $attributes as $attribute => $value ) {
            $html .= "$attribute=\"$value\"";
        }
        if(!$selfClosing) {
            $html .= ">$body</$elem>";
        } else {
            $html .= " />";
        }
        

        $this->HTMLElements[$position][$id] = $html;
        $this->hasScripts=true;
        $params = func_get_args();
        $this->recordCachingAction('clientScript','registerHTMLlement',$params);
        return $this;
    }
    
    
    
    public function renderBodyEnd(&$output) {
        
        // check for HTMLElements and add them
        foreach( $this->HTMLElements[self::POS_END] as $id => $element) {
            $output .= $element . PHP_EOL;
        }
        parent::renderBodyEnd($output);
    }
    
    /**
     * Inserts the scripts in the head section.
     * @param string $output the output to be inserted with scripts.
     */
    public function renderHead(&$output) {
        $html='';
        foreach($this->metaTags as $meta)
            $html.=CHtml::metaTag($meta['content'],null,null,$meta)."\n";
        foreach($this->linkTags as $link)
            $html.=CHtml::linkTag(null,null,null,null,$link)."\n";
        foreach($this->cssFiles as $url=>$media)
            $html.=CHtml::cssFile($url,$media)."\n";
        foreach($this->css as $css)
            $html.=CHtml::css($css[0],$css[1])."\n";
        if($this->enableJavaScript)
        {
            if(isset($this->scriptFiles[self::POS_HEAD]))
            {
                foreach($this->scriptFiles[self::POS_HEAD] as $scriptFile)
                    $html.=CHtml::scriptFile($scriptFile)."\n";
            }

            if(isset($this->scripts[self::POS_HEAD]))
                $html.=CHtml::script(implode("\n",$this->scripts[self::POS_HEAD]))."\n";
        }
        // check for HTMLElements and add them
        if(isset($this->HTMLElements[self::POS_HEAD])) {
            foreach($this->HTMLElements[self::POS_HEAD] as $element) {
                $html .= $element . PHP_EOL;
            }
        }

        if($html!=='')
        {
            $count=0;
            $output=preg_replace('/(<title\b[^>]*>|<\\/head\s*>)/is','<###head###>$1',$output,1,$count);
            if($count)
                $output=str_replace('<###head###>',$html,$output);
            else
                $output=$html.$output;
        }
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