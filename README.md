yii-analytics-piwik
===================

The Piwik Analytics component for Yii. See our [Wiki](https://github.com/TagPlanet/yii-analytics-piwik/wiki) for more information.

added functionality to register image tracker - requires CClientScript overloading. Components config as follows

'clientScript'=>array(
    'class'=>'ext.yii-analytics.components.ClientScript',
),
'piwik' => array(
    'class' =>'ext.yii-analytics.components.TPPiwikAnalytics',
    'siteID' => '2',
    'trackerURL' => 'udonjs.net/analytics',
    'imageTracker' => true,
    'autoRender' => true,
), 