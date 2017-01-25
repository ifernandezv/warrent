<div class="wrap settings-wrap" id="page-settings">
    <h2>Settings</h2>
    <div id="option-tree-header-wrap">
        <ul id="option-tree-header">
            <li id=""><a href="" target="_blank"></a>
            </li>
            <li id="option-tree-version"><span>Webriti SMTP Mail Plugin </span>
            </li>
        </ul>
    </div>
    <div id="option-tree-settings-api">
    <div id="option-tree-sub-header"></div>
        <div class = "ui-tabs ui-widget ui-widget-content ui-corner-all">
            <ul >
                <li id="tab_create_setting"><a href="#section_smtp_mail">SMTP Mail Settings</a>
                </li>
				<li id="tab_create_setting"><a href="#section_errors">Common Errors</a>
                </li>
                <li id="tab_import"><a href="#section_newsletter" >Join Newsletter</a>
                </li>
                <li id="tab_export"><a href="#section_themes">WordPress Themes</a>
                </li>
				<li id="tab_typo"><a href="#section_others" >Other Resources</a>
                </li>
              
            </ul>
    <div id="poststuff" class="metabox-holder">
        <div id="post-body">
			<div id="post-body-content">
                
   <!--most 1 tabs webriti_option_settings --> 
		<?php require_once('webriti_smtp_settings.php'); ?>	
	<!--most 2 tabs common errors --> 
		<?php require_once('common_errors.php'); ?>		
	<!--most 3 tabs subscriber --> 
		<?php require_once('subscriber.php'); ?>
	<!--most 4 tabs free wordpress themes --> 
		<?php require_once('free_wordpress_themes.php'); ?>
	<!--most 5 tabs other resources --> 
		<?php require_once('other_resources.php'); ?>
	
	
    </div>
    </div>
    </div>
    <div class="clear"></div>
    </div>
    </div>
</div>
