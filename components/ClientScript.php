<?php
<<<<<<< HEAD
=======

>>>>>>> c5a48ea4c77095fec75aac0720c3aaa9f183793a
/**
 *  ClientScript extens CClientScript
 *  adds a imagetracker functionality to CClientScript
 *  
 * 
 */
<<<<<<< HEAD
class ClientScript extends CClientScript {
=======
class ClientScript extends CClientScript  {
>>>>>>> c5a48ea4c77095fec75aac0720c3aaa9f183793a
    /**
     * @var array the registered image trackers.
     */
    public $HTMLElements;
    
    public function init() {
        
    }
    
    public function registerHTMLElement( $id, $elemType, $position, $attributes, $body = '', $selfClosing = false ) {
<<<<<<< HEAD

        // can't go in read or load
=======
        
        // Can't go in read or load
>>>>>>> c5a48ea4c77095fec75aac0720c3aaa9f183793a
        if($position > 2) {
            $position = 2;
        }

<<<<<<< HEAD
        // build the element
=======
        // Build the element
>>>>>>> c5a48ea4c77095fec75aac0720c3aaa9f183793a
        $html = "<$elemType ";
        foreach( $attributes as $attribute => $value ) {
            $html .= "$attribute=\"$value\"";
        }
<<<<<<< HEAD
        if(!$selfClosing) {
            $html .= ">$body</$elem>";
=======
        
        // close it
        if(!$selfClosing) {
            $html .= "$body</$elem>";
>>>>>>> c5a48ea4c77095fec75aac0720c3aaa9f183793a
        } else {
            $html .= " />";
        }
        
<<<<<<< HEAD

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
=======
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
>>>>>>> c5a48ea4c77095fec75aac0720c3aaa9f183793a
        }
        parent::renderBodyEnd($output);
    }
    
    /**
     * Inserts the scripts in the head section.
     * @param string $output the output to be inserted with scripts.
     */
    public function renderHead(&$output) {
<<<<<<< HEAD
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
=======
        
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
>>>>>>> c5a48ea4c77095fec75aac0720c3aaa9f183793a
        }

        if($html!=='')
        {
<<<<<<< HEAD
            $count=0;
            $output=preg_replace('/(<title\b[^>]*>|<\\/head\s*>)/is','<###head###>$1',$output,1,$count);
            if($count)
                $output=str_replace('<###head###>',$html,$output);
            else
                $output=$html.$output;
        }
    }
        
        /**
=======
                $count=0;
                $output=preg_replace('/(<title\b[^>]*>|<\\/head\s*>)/is','<###head###>$1',$output,1,$count);
                if($count)
                        $output=str_replace('<###head###>',$html,$output);
                else
                        $output=$html.$output;
        }
        
        if(isset($this->HTMLElements[self::POS_HEAD])) {
            foreach( $this->HTMLElements[self::POS_HEAD] as $id => $element) {
                $output .= $element . PHP_EOL;
            }
        }
    }

    /**
>>>>>>> c5a48ea4c77095fec75aac0720c3aaa9f183793a
     * Inserts the scripts at the beginning of the body section.
     * @param string $output the output to be inserted with scripts.
     */
    public function renderBodyBegin(&$output)
    {
        $html='';
        if(isset($this->scriptFiles[self::POS_BEGIN]))
        {
<<<<<<< HEAD
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
=======
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
>>>>>>> c5a48ea4c77095fec75aac0720c3aaa9f183793a
