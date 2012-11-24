<?php
/**
 * @package		Joomla.Site
 * @copyright	Mario Onekanda novemver 2012
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
?>
<?php echo '<?'; ?>xml version="1.0" encoding="<?php echo $this->_charset ?>"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		<jdoc:include type="head" />
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/template.js"></script>

	<body>
		<div id="top">
			<jdoc:include type="modules" name="top" />
			<a href="<?php echo $this->baseurl; ?>"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/logo.png" class="logo" /></a>
			
		</div>
		<!-- ENDS #TOP-->
		<div id="wrapper">
			
				<div id="image-sliders">
					<jdoc:include type="modules" name="sliders" />				
			<jdoc:include type="component" />
				</div>
			<!-- ENDS #MENU -->
			</div>
			<!-- ENDS #CONTAINER-->
		</div>		
		<!-- ENDS #WRAPPER-->
		
	</body>
</html>
