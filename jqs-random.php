<?php
/*
Plugin Name: jqs-random
Version: 0.1.1
Plugin URI: http://iridani.com/projects/wordpress-plugins/jqs-random
Description: This is a quick and dirty random plugin that works with WP 2.5
Author: James Q. Stansfield
Author URI: http://iridani.com/authors/jqs
*/

//Instantiate the class ((Must be a unique variable name))
$jqs_random = new jqs_random_class();
class jqs_random_class {
	//Class Variables
	var $classname = 'jqs_random';
	var $title = 'JQS - Random';
	var $options;
	
	//PHP4 constructor
	function jqs_template_class() {
		register_shutdown_function(array(&$this, '__destruct'));
		$this->__construct();
	}
	
	//Main constructor
	function __construct() {
		//Make sure we have options set
		if (!get_option($this->classname )) {
			$this->setDefaultOptions();
		}else{
			$this->options = get_option($this->classname);
		}
		//Add any and all actions and or filters
		add_action('admin_menu', array(&$this, 'admin_setup'));
		add_shortcode('jqs-random', array($this, 'sc_parse_content'));
		add_action('activate_'.plugin_basename(__FILE__), array(&$this, 'activate_plugin'));
		add_action('deactivate_'.plugin_basename(__FILE__), array(&$this, 'deactivate_plugin'));
		add_action('wp_ajax_jqs_random_add', array(&$this, 'ajax_add'));
		add_action('wp_ajax_jqs_random_del', array(&$this, 'ajax_del'));
	}
	
	//Main desctructor
	function __destruct() {
		//Empty
	}
	
	function setDefaultOptions() {
		$this->options['version'] = '0.0.1';
		$this->options['random'] = array("Are you feeling lucky punk?");
		$this->options['defaults'] = array("stripwhitespace" => 0, "addslashes" => 0, "htmlspecialchars" =>0);
		update_option($this->classname , $this->options);
	}
	
	function admin_setup() {
		if (function_exists('add_options_page')) {
			$page = add_options_page($this->classname , $this->classname , 8, basename(__FILE__), array(&$this, 'option_page'));
			add_action('admin_print_scripts-' . $page, array(&$this, 'add_admin_head'));
		}
	}
	
	function add_admin_head() {
		wp_enqueue_script('jqeury');
		wp_enqueue_script('jqs-random', get_bloginfo('wpurl') . '/wp-content/plugins/jqs-random/js/admin.js', 'jquery', '1.0');
		?>
			<script type="text/javascript">
			//<![CDATA[
				ajaxpost = "<?php echo bloginfo('wpurl') . '/wp-admin/admin-ajax.php' ?>";
			//]]>
			</script>
			<?php
	}
	
	function ajax_add() {
		header("Content-type: text/xml");
		if (isset($_POST['data'])) {
			$this->options['random'][] = $_POST['data'];
			update_option($this->classname, $this->options);
			die("<r><v>" . sizeof($this->options['random']) -1 . "</v></r>");
		} else {
			die("<r><err>???</err></r>");
		}
	}
	
	function ajax_del() {
		header("Content-type: text/xml");
		if (isset($_POST['data'])) {
			//$this->options['random'][] = $_POST['data'];
			unset($this->options['random'][$_POST['data']]);
			$this->options['random'] = array_values($this->options['random']);
			update_option($this->classname, $this->options);
			die("<r><v>" . $_POST['data'] . "</v></r>");
		} else {
			die("<r><err>???</err></r>");
		}
	}
	
	function option_page() {
		//Conditional based on POSTBACK
		if ($_POST['action'] == $this->classname  . '_save_options') {
			//Handle potback
			$this->options['defaults']['stripwhitespace'] = (array_key_exists('stripwhitespace', $_POST)) ? 1 : 0;
			$this->options['defaults']['addslashes'] = (array_key_exists('addslashes', $_POST)) ? 1 : 0;
			$this->options['defaults']['htmlspecialchars'] = (array_key_exists('htmlspecialchars', $_POST)) ? 1 : 0;
			update_option($this->classname, $this->options);
			?>
			<div class="updated"><p><strong>Options saved.</strong></p></div>
			<?php
		}elseif ($_POST['action'] == $this->classname . '_save_words') {
			//parse form
			
			//save data (if needed)
			update_option($this->classname , $this->options);
			//return success or errors.
			
			return $xml;
		}elseif ($_POST['action'] == $this->classname  . '_reset') {
			$this->setDefaultOptions();
			?>
			<div class="updated"><p><strong>Options reset.</strong></p></div>
			<?php
		}
		?>
		<div class="wrap" id="<?php echo $this->classname ?>">
			<h2><?php echo $this->title ?></h2>
			<div id="jqs-desc" style="float:left; width:75%;">
				<fieldset class="options">
					<legend>Description</legend>
				
				</fieldset>
			</div>
			<div id="jqs-donate" style="float:right; width:25%">
				<fieldset class="options">
					<legend>Donations</legend>
				
				</fieldset>
			</div>
			<div id="wrap2" style="clear:both">
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>">
					<input type="hidden" name="action" value="<?php echo $this->classname  . '_save_options'; ?>" />
					<fieldset class="options">
						<legend>Default Options</legend>
						<table width="100%" class="form-table">
						<tr>
							<th width="33%" valign="top" scope="row">
								<label for="stripwhitespace">Strip White Space</label>
							</th>
							<td>
								<input type="checkbox" name="stripwhitespace" id="stripwhitespace"<?php if ($this->options['defaults']['stripwhitespace'] == true) echo ' checked'; ?> />
							</td>
							<th width="33%" valign="top" scope="row">
								<label for="addslashes">Add Slashes</label>
							</th>
							<td>
								<input type="checkbox" name="addslashes" id="addslashes"<?php if ($this->options['defaults']['addslashes'] == true) echo ' checked'; ?> />
							</td>
							<th width="33%" valign="top" scope="row">
								<label for="htmlspecialchars">Escape HTML Entities</label>
							</th>
							<td>
								<input type="checkbox" name="htmlspecialshars" id="htmlspecialchars"<?php if ($this->options['defaults']['htmlspecialchars'] == true) echo ' checked'; ?> />
							</td>
						</tr>
						</table>
						<p class="submit">
							<input type="submit" name="submit" value="Update Options&raquo;" />
						</p>
					</fieldset>
				</form>
				
				<fieldset class="options">
					<legend>Random Words</legend>
					<table width="100%">
					<tr>
						<td>
							<input id="w-add" style="width: 300px;" value="Add new phrase..." />
							<input class="button-secondary" type="submit" id="w-add-button" value="Add" />
						</td>
					</tr>
					</table>
					<table id="words" width="100%" class="form-table">
					<?php foreach ($this->options['random'] as $key => $item) { ?>
					<tr>
						<td>
							<input type="text" name="words" id="w-<?php echo $key; ?>" value="<?php echo $item; ?>" style="width: 300px;"/>
						</td>
					</tr>	
					<?php } ?>
					</table>
					<br />
				</fieldset>

				<form method="post" action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>">
					<input type="hidden" name="action" value="<?php echo $this->classname  . '_reset'; ?>" />
					<p class="submit">
						<input type="submit" name="submit" value="Reset Plugin&raquo;" />
						<p><small>Resetting Options Will delete your saved random words and set the default options!</small></p>
					</p>
				</form>
			</div>
		</div>
		<?php
	}
		
	function sc_parse_content($atts = '') {
		$atts = shortcode_atts((array)$this->options['defaults'], $atts);
		return $this->parse_content($atts);
	}
	
	function parse_content($atts = '') {
		$r = wp_parse_args($atts, $this->options['defaults']);
		extract($r, EXTR_SKIP);
		$data = $this->options['random'][rand(0, sizeof($this->options['random'])-1)];
		$data = ($stripwhitespace) ? str_replace(" ", "_", $data) : $data;
		$data = ($addslashes) ? addslashes($data) : $data;
		$data = ($htmlspecialchars) ? htmlspecialchars($data) : $data;
		return $data;
	}

}
function jqs_random($atts = '') {
	global $jqs_random;
	echo $jqs_random->parse_content($atts);
}
function jqs_get_random($atts = '') {
	global $jqs_random;
	return $jqs_random->parse_content($atts);
}
?>
