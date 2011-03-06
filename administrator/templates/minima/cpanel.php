<?php
/** 
 * @package     Minima
 * @author      Marco Barbosa
 * @copyright   Copyright (C) 2010 Marco Barbosa. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$app    = JFactory::getApplication();

// Remove unnecessary inline javascript
/*$headerstuff = $this->getHeadData();
$headerstuff['script'] = array();
$this->setHeadData($headerstuff);*/

// template color parameter
$templateColor = $this->params->get('templateColor');
$darkerColor   = $this->params->get('darkerColor');
$lighterColor   = $this->params->get('lighterColor');

// Is it visiting for the first time?
//$welcome = $this->params->get('welcome_message',1);

// get the current logged in user
$currentUser = JFactory::getUser();    

?>

<!DOCTYPE html>
<html lang="<?php echo  $this->language; ?>" class="no-js" dir="<?php echo  $this->direction; ?>">

<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <jdoc:include type="head" />

    <link href="templates/<?php echo  $this->template ?>/css/template.css" rel="stylesheet">

    <!-- <link rel="stylesheet" media="handheld" href="css/handheld.css?v=1">  -->

    <style>
        #panel li a:hover,.box-top { background-color: <?php echo $templateColor; ?>; }
        #panel-tab, #panel-tab.active, #panel-wrapper,#more, #more.inactive { background-color: <?php echo $darkerColor; ?>; }
        #tophead { background: <?php echo $templateColor;?>; background: -moz-linear-gradient(-90deg,<?php echo $templateColor;?>,<?php echo $darkerColor;?>); /* FF3.6 */ background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $templateColor;?>), to(<?php echo $darkerColor;?>)); /* Saf4+, Chrome */ filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php echo $templateColor;?>, endColorstr=<?php echo $darkerColor;?>); /* IE6,IE7 */ -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo $templateColor;?>', EndColorStr='<?php echo $darkerColor;?>')"; /* IE8 */ }
        #prev, #next { border: 1px solid <?php echo $templateColor; ?>; background: <?php echo $templateColor;?>; background: -moz-linear-gradient(-90deg,<?php echo $templateColor;?>,<?php echo $darkerColor;?>); /* FF3.6 */ background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $templateColor;?>), to(<?php echo $darkerColor;?>)); /* Saf4+, Chrome */ filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php echo $templateColor;?>, endColorstr=<?php echo $darkerColor;?>); /* IE6,IE7 */ -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo $templateColor;?>', EndColorStr='<?php echo $darkerColor;?>')"; /* IE8 */ }
        #prev:active, #next:active { background-color: <?php echo $darkerColor; ?>; }
        .box:hover { -moz-box-shadow: 0 0 10px <?php echo $templateColor; ?>; -webkit-box-shadow: 0 0 10px <?php echo $templateColor; ?>; box-shadow: 0 0 10px <?php echo $templateColor; ?>; }
        #panel-pagination li { color: <?php echo $templateColor; ?>; }
        ::selection { background: <?php echo $templateColor; ?>; color:#000; /* Safari */ }
        ::-moz-selection { background: <?php echo $templateColor; ?>; color:#000; /* Firefox */ }
        body, a:link { -webkit-tap-highlight-color: <?php echo $templateColor; ?>;  }
        #logo {text-shadow: 1px 1px 0 <?php echo $darkerColor; ?>, -1px -1px 0 <?php echo $darkerColor; ?>; }
    </style>

    <script src="templates/<?php echo $this->template ?>/js/head.min.js"></script>

    <script src="http://yandex.st/raphael/1.5.2/raphael.min.js"></script>
    <script>!window.Raphael && document.write(unescape('%3Cscript src="templates/<?php echo $this->template ?>/js/raphael.min.js"%3E%3C/script%3E'))</script>
	<script>
		window.addEvent('domready', function(){
			new Sortables($$('.col'),{
				onComplete: function(widget){
					order = null;
					position = widget.getParent();
					elementsList = $(position.id).getChildren();
					elementsList.each(function(element,index){ if(element.id == widget.id){ order = index; } });
					order = parseInt(order) + 1;
					token = '<?php echo JUtility::getToken(); ?>=1';
					title =  widget.getChildren('div.box-top > span').get('html');
					new Request({
						url: 'index.php?option=com_modules&task=module.saveAjax',
						method: 'post',
						data: 'tmpl=none&jform[title]='+title+'&jform[id]='+widget.id+'&jform[position]='+position.id+'&jform[ordering]='+order+'&'+token,
						onSuccess: function(data){
							if(data == '1'){
								message = null;
								fx = null;
								$$('div.message-wrapper').each(function(div){
									message = div;
									div.set('html', '<dl id="system-message" style="display: none;"><dt class="message">Message</dt><dd class="message message"><ul><li>Module position changed!<a href="#" id="hide-system-message">hide</a></li></ul></dd></dl>');
								});
								$('system-message').reveal();
								$('hide-system-message').addEvent('click',function(){ $('system-message').dissolve({duration: 'short'}); });
							}
						}
					}).send();
					
				}
			});
		});
	</script>
	<!--[if (gte IE 6)&(lte IE 8)]>
        <script type="text/javascript" src="templates/<?php echo $this->template ?>/js/selectivizr.js" defer="defer"></script>
    <![endif]-->
</head>
<body id="minima" class="full jbg cpanel<?php if (JRequest::getInt('hidemainmenu')) echo " hiddenmenu"; ?>">
    <?php if( $this->countModules('panel') ): ?>
    <div id="panel-wrapper">
        <jdoc:include type="modules" name="panel" />
    </div>
    <?php endif; ?>
    <header id="tophead">
            <div class="title">
                <span id="logo"><?php echo $app->getCfg('sitename');?></span>
                <span class="site-link"><a target="_blank" title="<?php echo $app->getCfg('sitename');?>" href="<?php echo JURI::root();?>"><?php echo "(".JText::_('TPL_MINIMA_VIEW_SITE').")"; ?></a></span>
            </div>
            <div id="module-status">
                <jdoc:include type="modules" name="status"  />
            </div>
            <?php if( $this->countModules('panel') ): ?>
            <div id="tab-wrapper">
                <span id="panel-tab"<?php if (JRequest::getInt('hidemainmenu')) echo " class=\"disabled\""; ?>>
                    <?php echo JText::_('TPL_MINIMA_PANEL') ?>
                </span>
            </div>
            <?php endif; ?>
            <div id="list-wrapper">
                <span id="more"<?php if (JRequest::getInt('hidemainmenu')) echo " class=\"disabled\""; ?>></span>
                <div class="clr"></div>
                <nav id="list-content">
                    <dl class="first">
                        <dt>Tools</dt>
                        <?php if( $currentUser->authorize( array('core.manage','com_checkin') ) ): ?><dd><a href="index.php?option=com_checkin"><?php echo JText::_('TPL_MINIMA_TOOLS_GLOBAL_CHECKIN'); ?></a></dd><?php endif; ?>
                        <?php if( $currentUser->authorize( array('core.manage','com_cache') ) ): ?><dd><a href="index.php?option=com_cache"><?php echo JText::_('TPL_MINIMA_TOOLS_CLEAR_CACHE'); ?></a></dd><?php endif; ?>
                        <?php if( $currentUser->authorize( array('core.manage','com_cache') ) ): ?><dd><a href="index.php?option=com_cache&amp;view=purge"><?php echo JText::_('TPL_MINIMA_TOOLS_PURGE_EXPIRED_CACHE'); ?></a></dd><?php endif; ?>
                        <?php if( $currentUser->authorize( array('core.manage','com_admin') ) ): ?><dd><a href="index.php?option=com_admin&amp;view=sysinfo"><?php echo JText::_('TPL_MINIMA_TOOLS_SYSTEM_INFORMATION'); ?></a></dd><?php endif; ?>
                    </dl>
                    <dl class="last">
                    <dt>Extensions</dt>
                        <?php if( $currentUser->authorize( array('core.manage','com_languages') ) ): ?><dd><a href="index.php?option=com_languages"><?php echo JText::_('TPL_MINIMA_TOOLS_LANGUAGES'); ?></a></dd><?php endif; ?>
                        <?php if( $currentUser->authorize( array('core.manage','com_modules') ) ): ?><dd><a href="index.php?option=com_modules"><?php echo JText::_('TPL_MINIMA_TOOLS_MODULES'); ?></a></dd><?php endif; ?>
                        <?php if( $currentUser->authorize( array('core.manage','com_plugins') ) ): ?><dd><a href="index.php?option=com_plugins"><?php echo JText::_('TPL_MINIMA_TOOLS_PLUGINS'); ?></a></dd><?php endif; ?>
                        <?php if( $currentUser->authorize( array('core.manage','com_templates') ) ): ?><dd><a href="index.php?option=com_templates"><?php echo JText::_('TPL_MINIMA_TOOLS_TEMPLATES'); ?></a></dd><?php endif; ?>
                    </dl>
                </nav><!-- /#list-content -->
            </div><!-- /#list-wrapper -->
    </header><!-- /header -->

    <nav id="shortcuts">
        <jdoc:include type="modules" name="shortcuts" />
    </nav>
    <div class="message-wrapper"><jdoc:include type="message" /></div><hr class="space" />
    <div id="content-cpanel">
        <noscript><?php echo  JText::_('WARNJAVASCRIPT') ?></noscript>
        <section id="widgets-first" class="col">
            <jdoc:include type="modules" name="widgets-first" style="widget" />
        </section><!-- /#widgets-first -->
        <section id="widgets-last" class="col">
            <jdoc:include type="modules" name="widgets-last" style="widget" />
        </section><!-- /#widgets-last -->
    </div><!-- /#content-cpanel -->

    <footer>
        <p class="copyright">
            <a href="http://www.joomla.org">Joomla!</a>
            <span class="version"><?php echo  JText::_('JVERSION') ?> <?php echo  JVERSION; ?></span>
        </p>
        <jdoc:include type="modules" name="footer" style="none"  />
    </footer>
    <script>
        head.js(
            {minima: "templates/<?php echo $this->template ?>/js/minima.min.js"}
        );
    </script>
</body>
</html>
