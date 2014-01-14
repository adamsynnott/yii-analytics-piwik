<?php
/**
 *  Piwik Component
 *
 * @author Philip Lawrence <philip@misterphilip.com>
 * @link http://misterphilip.com
 * @link http://tagpla.net
 * @link https://github.com/TagPlanet/yii-analytics-piwik
 * @copyright Copyright &copy; 2012 Philip Lawrence
 * @license http://tagpla.net/licenses/MIT.txt
 * @version 1.0.2
 */
class TPPiwikAnalytics extends CApplicationComponent
{
    /**
     * Site ID
     * @var string
     */
    public $siteID;
    
    /**
     * Tracker URL
     * @var string
     */
    public $trackerURL;

    /**
     * Auto render or return the JS
     * @var bool
     */
    public $autoRender = false;
    /**
     * Image tracker
     * @var bool 
     */
    public $imageTracker = false;
    /**
     * Automatically add trackPageView when render is called
     * @var bool
     */
    public $autoPageview = true;
    
    /**
     * JS Variable name
     * @var string
     */
    public $variableName = '_paq';
    /**
     * 
     */
    

    /**
     * Type of quotes to use for values
     */
    const Q = "'";

    /**
     * Available options, pulled (Oct 17, 2012) from
     * http://piwik.org/docs/javascript-tracking/#toc-list-of-all-methods-available-in-the-tracking-api
     * @var array
     */
    protected $_availableOptions = array
    (
        'addDownloadExtensions',
        'addEcommerceItem',
        'deleteCustomVariable',
        'disableCookies',
        'discardHashTag',
        'enableLinkTracking',
        'killFrame',
        'redirectFile',
        'setCampaignKeywordKey',
        'setCampaignNameKey',
        'setConversionAttributionFirstReferrer',
        'setCookieDomain',
        'setCookieNamePrefix',
        'setCookiePath',
        'setCountPreRendered',
        'setCustomUrl',
        'setCustomVariable',
        'setDoNotTrack',
        'setDocumentTitle',
        'setDomains',
        'setDownloadClasses',
        'setDownloadExtensions',
        'setHeartBeatTimer',
        'setIgnoreClasses',
        'setLinkClasses',
        'setLinkTrackingTimer',
        'setReferralCookieTimeout',
        'setReferrerUrl',
        'setRequestMethod',
        'setSessionCookieTimeout',
        'setSiteId',
        'setTrackerUrl',
        'setVisitorCookieTimeout',
        'trackEcommerceOrder',
        'trackEcommerceCartUpdate',
        'trackGoal',
        'trackPageView',
        'trackSiteSearch',
    );

    /**
     * An array of all the methods called for _gaq
     * @var array
     */
    protected $_calledOptions = array();

    /**
     * Method data to be pushed into the _gaq object
     * @var array
     */

    private $_data = array();

    /**
     * init function - Yii automaticall calls this
     */
    public function init() {
        // Verify we have the basics
        if($this->siteID == '') 
            throw new CException('Missing required parameter "Site ID" for TPPiwikAnalytics');
        if($this->trackerURL == '') 
            throw new CException('Missing required parameter "Tracker URL" for TPPiwikAnalytics');
        
        $this->setSiteId($this->siteID);
        $this->trackerURL = rtrim($this->trackerURL, '/');
        $this->setTrackerUrl($this->trackerURL . '/piwik.php');
    }

    /**
     * Render and return the Piwik code
     * @return mixed
     */
    public function render() {
        
        // tracking string
        $tracker = '';
        $option =  ($this->autoRender << 0) + ( $this->imageTracker << 1 );

        // Check to see if we need to throw in the trackPageview call
        if(!in_array('trackPageView', $this->_calledOptions) && $this->autoPageview) {
            $this->trackPageView();
        }

        // bitshift the flags
        switch( $option ) {
            case 0 :
                $tracker = $this->_getJS();
                return $tracker;
                break;
            case 1 :
                $tracker = $this->_getJs();
                Yii::app()->clientScript->registerScript('TPPiwikAnalytics', $tracker, CClientScript::POS_END);
                return;
                break;
            case 2 :
                $tracker = "<img src=\"" . $this->_getImageTracker() . "\" width=\"0\" height=\"0\" style=\"border:none;\" />";
                return $tracker;
                break;
            case 3 :
                if (preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {} else {
                    Yii::app()->clientScript->registerHTMLElement( '_piwik', 'img', ClientScript::POS_END,  array( 'src' => $this->_getImageTracker(), 'width' => 0, 'height' => 0, 'style' => 'border:none;' ), '', true );
                }
                break;
        }
        
    }

    /**
     * Magic Method for options
     * @param string $name
     * @param array  $arguments
     */
    public function __call($name, $arguments)
    {
        if(in_array($name, $this->_availableOptions))
        {
            $this->_push($name, $arguments);
            return true;
        }
        return false;
    }

    /**
     * Push data into the array
     * @param string $variable
     * @param array  $arguments
     * @protected
     */
    protected function _push($variable, $arguments)
    {
        $data = array_merge(array($variable), $arguments);
        array_push($this->_data, $data);
        $this->_calledOptions[] = $variable;
    }
    
    /**
     * _getJS
     * builds the tracking code JS
     * @return string
     */
    private function _getJS(){
        
        // Start the JS string
        $js = 'var ' . $this->variableName . ' = ' . $this->variableName . ' || [];' . PHP_EOL;
        $js.= '(function() { ' . PHP_EOL;
        
        foreach($this->_data as $data) {                
            // Clean up each item
            foreach($data as $key => $item) {
                
                if(is_string($item)){
                    $data[$key] = self::Q . preg_replace('~(?<!\\\)'. self::Q . '~', '\\' . self::Q, $item) . self::Q;
                }
                else if(is_bool($item)) {
                    $data[$key] = ($item) ? 'true' : 'false';
                }
                
                $prefixed = true;
            }

            $js.= '  ' . $this->variableName . '.push([' . implode(',', $data) . ']);' . PHP_EOL;
        }
        $js .= "var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];". PHP_EOL;
        $js .= "g.type='text/javascript'; g.defer=true; g.async=true; g.src='{$this->trackerURL}/piwik.js';". PHP_EOL;
        $js .= "s.parentNode.insertBefore(g,s);". PHP_EOL; 
        $js .= "})();";
        
        return $js;        
    }
    
    /**
     * _getImageTracker
     * source of the image element
     * @return string
     */
    public function _getImageTracker() {
        /* TODO - improve this */
        return "{$this->trackerURL}/piwik.php?idsite={$this->siteID}&amp;rec=1&amp;urlref&_cvar={'5':['Non Bot','No Javascript']}";  
    }
}