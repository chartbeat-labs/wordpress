<?php
/*
Plugin Name: Chartbeat
Plugin URI: http://chartbeat.com/wordpress
Description: Adds Chartbeat pinging to Wordpress.
Version: 1.2
Author: Chartbeat
Author URI: http://chartbeat.com
*/

/*
Copyright 2009 Chartbeat Inc.

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/
add_option('chartbeat_userid');
add_option('chartbeat_apikey');
add_option('chartbeat_widgetconfig');


function chartbeat_menu() {
  add_options_page('chartbeat plugin options', 'Chartbeat', 'administrator',
      'chartbeat-options', 'chartbeat_options_page');
}

function chartbeat_options_page() {
?>
<div class="wrap">
<h2>chartbeat</h2>

<form method="post" action="options.php" onsubmit="buildOptions()">
<?php 

// outputs all of the hidden fields that options.php will check, including the nonce
wp_nonce_field('update-options');
settings_fields('chartbeat-options'); ?>

<script>
function showSettings() {
  window.open('http://chartbeat.com/wordpress/?site=' + encodeURIComponent(window.location.host));
}
</script>
To enable tracking, you must enter your chartbeat user id. <a href="#" onclick="showSettings()">Find yours.</a>
<br/>
<table class="form-table">
<tr><th scope="row">User ID</th>
<td><input size="30" type="text" name="chartbeat_userid" value="<?php echo get_option('chartbeat_userid'); ?>" /></td>
</tr></table>
<br/><br/>
<script src="http://static.chartbeat.com/js/topwidgetv2.js" type="text/javascript" language="javascript"></script> 
<script type="text/javascript" language="javascript"> 
var themes = { 'doe':   { 'bgcolor': '', 'border': '#dde7d4', 'text': '#555' },
    	       'gray':  { 'bgcolor': '#e3e3e3', 'border': '#333333', 'text': '#555', 'header_bgcolor': '#999999', 'header_color': '#fff' },
               'red':   { 'bgcolor': '#ffffff', 'border': '#cc3300', 'text': '#555', 'header_bgcolor': '#f5c5be', 'header_color': '#fff' },
	       'blue':  { 'bgcolor': '#e0ecff', 'border': '#3a5db0' },
	       'green': { 'bgcolor': '#c9edcc', 'border': '#69c17d', 'text': '#555' } };
var theme = 'doe';
var limit = 10;

function changeTheme(select) {
  theme = select.options[select.selectedIndex].value;
  renderWidget();
}

function changeLimit(select) {
  limit = select.options[select.selectedIndex].value;
  renderWidget();
}

function renderWidget() {
  new CBTopPagesWidget('317a25eccba186e0f6b558f45214c0e7',
                       { 'host': 'avc.com',
                         'background': themes[theme]['bgcolor'],
                         'border': themes[theme]['border'],
                         'header_bgcolor': themes[theme]['header_bgcolor'],
                         'header_color': themes[theme]['header_color'],
                         'text': themes[theme]['text'],
                         'limit': limit });
}

function addOption(array, key, val) {
  array.push("'" + key + "': '" + val + "'");
}
function buildOptions() {
  var options = [];
  addOption(options, 'background', themes[theme]['bgcolor']);
  addOption(options, 'border', themes[theme]['border']);
  addOption(options, 'header_bgcolor', themes[theme]['header_bgcolor']);
  addOption(options, 'header_color', themes[theme]['header_color']);
  addOption(options, 'text', themes[theme]['text']);
  addOption(options, 'limit', limit);
  options = '{' + options.join(',') + '}';
  document.getElementById('chartbeat_widgetconfig').value = options;
}
renderWidget();
</script>
If your theme supports it, you can also add a widget under <tt>Appearance > Widgets</tt> to show where users currently are on your site.
<br><br>
<table cellspacing="10">
<tr><td valign="top">
Number of pages to show
<select name="metric" id="toplimit" onChange="changeLimit(this);"> 
  <option value="5">5</option>
  <option value="10" selected="selected">10</option>
  <option value="20">20</option>
  <option value="30">30</option>
</select><br/><br/>
Color scheme
<select name="theme" id="toptheme" onChange="changeTheme(this);"> 
  <option value="doe">John Doe</option>
  <option value="gray">Dorian Gray</option>
  <option value="red">Red Rum</option>
  <option value="blue">Blue Moon</option>
  <option value="green">Green Giant</option>
</select>
</td><td>&nbsp;</td><td>
Sample:<br><br>
<div id="cb_top_pages"></div>
</td></tr></table>
<br><br>
In order for the widget to work, copy your API key into the space below.
<table class="form-table">
<tr><th scope="row">API key</th>
<td><input size="30" type="text" name="chartbeat_apikey" value="<?php echo get_option('chartbeat_apikey'); ?>" /></td>
</tr></table>

<input type="hidden" name="action" value="update" />
<input type="hidden" id="chartbeat_widgetconfig" name="chartbeat_widgetconfig" value="{}" />
<input type="hidden" name="page_options" value="chartbeat_userid,chartbeat_apikey,chartbeat_widgetconfig"/>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
<?php
}

// Function to register settings and sanitize output. To be called later in add_action
function chartbeat_register_settings() {
	register_setting('chartbeat-options','chartbeat_userid');
	register_setting('chartbeat-options','chartbeat_apikey');
	register_setting('chartbeat-options','chartbeat_widgetconfig');
	
}

function add_chartbeat_head() {
  echo "\n<script type=\"text/javascript\">var _sf_startpt=(new Date()).getTime()</script>\n";
}

function add_chartbeat_footer() {
  $user_id = get_option('chartbeat_userid');
  if ($user_id) {
?>
<script type="text/javascript">
var _sf_async_config={uid:<?php print $user_id ?>};
(function(){
  function loadChartbeat() {
    window._sf_endpt=(new Date()).getTime();
    var e = document.createElement('script');
    e.setAttribute('language', 'javascript');
    e.setAttribute('type', 'text/javascript');
    e.setAttribute('src',
       (("https:" == document.location.protocol) ? "https://s3.amazonaws.com/" : "http://") +
       "static.chartbeat.com/js/chartbeat.js");
    document.body.appendChild(e);
  }
  var oldonload = window.onload;
  window.onload = (typeof window.onload != 'function') ?
     loadChartbeat : function() { oldonload(); loadChartbeat(); };
})();
</script>
<?php
  }
}

function widget_chartbeat($args) {
  extract($args);
  echo $before_widget;
  if (get_option('chartbeat_apikey')) {
?>
<div id="cb_top_pages"></div>

<script src="http://static.chartbeat.com/js/topwidgetv2.js" type="text/javascript" language="javascript"></script> 
<script type="text/javascript" language="javascript"> 
var options = { };
new CBTopPagesWidget('<?php echo get_option('chartbeat_apikey')?>',
                     <?php echo get_option('chartbeat_widgetconfig')?>);
</script>
<?php
  }
  echo $after_widget;
}


function chartbeat_widget_init() {
  register_sidebar_widget('chartbeat Widget', 'widget_chartbeat');
}

add_action('widgets_init', 'chartbeat_widget_init');
add_action('admin_menu', 'chartbeat_menu');


// If admin register settings on page that have been saved
// if not, add content to wp_head and wp_footer.
if ( is_admin() ){ 
  add_action( 'admin_init', 'chartbeat_register_settings' );
}else {
  add_action('wp_head', 'add_chartbeat_head');
  add_action('wp_footer', 'add_chartbeat_footer');
}
?>
