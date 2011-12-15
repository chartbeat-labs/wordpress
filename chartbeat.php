<?php
/*
Plugin Name: Chartbeat
Plugin URI: http://chartbeat.com/wordpress/
Description: Adds Chartbeat pinging to Wordpress.
Version: 1.3
Author: Chartbeat
Author URI: http://chartbeat.com/
*/

/*
Copyright 2009-2011 Chartbeat Inc.

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
add_option('chartbeat_trackadmins'); // Add trackadmin option
add_option('chartbeat_enable_newsbeat');


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
</tr>

<tr><th scope="row"><?php _e('Track visits by Site Admins?','chartbeat'); ?><br /><small>Administrators must be logged in to avoid tracking.</small></th>
<td><input type="radio" name="chartbeat_trackadmins" value="1" <?php checked( get_option('chartbeat_trackadmins'), 1 ); ?> /> Yes <input type="radio" name="chartbeat_trackadmins" value="0" <?php checked( get_option('chartbeat_trackadmins'), 0 ); ?> /> No</td>
</tr>

<tr>
    <th scope="row"><?php _e('Enable newsbeat?','chartbeat'); ?><br /><small>Sign up for <a href="http://chartbeat.com/newsbeat/">newsbeat</a>.</small></th>
    <td><input type="radio" name="chartbeat_enable_newsbeat" value="1" <?php checked( get_option('chartbeat_enable_newsbeat'), 1 ); ?> /> Yes <input type="radio" name="chartbeat_enable_newsbeat" value="0" <?php checked( get_option('chartbeat_enable_newsbeat'), 0 ); ?> /> No</td>
</tr>

</table>
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
<input type="hidden" name="page_options" value="chartbeat_userid,chartbeat_apikey,chartbeat_widgetconfig,chartbeat_trackadmins,chartbeat_enable_newsbeat"/>

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
    register_setting('chartbeat-options','chartbeat_trackadmins'); // add trackadmin setting
    register_setting('chartbeat-options','chartbeat_enable_newsbeat');
}

function add_chartbeat_head() {
  echo "\n<script type=\"text/javascript\">var _sf_startpt=(new Date()).getTime()</script>\n";
}

function add_chartbeat_footer() {
  $user_id = get_option('chartbeat_userid');
  if ($user_id) {
	if (current_user_can('manage_options') && get_option('chartbeat_trackadmins') == 0) {  // if visitor is admin AND tracking is off
		// do not load chartbeat
	} else {
		// load chartbeat js
?>

<!-- /// LOAD CHARTBEAT /// -->
<script type="text/javascript">
var _sf_async_config={};
_sf_async_config.uid = <?php print $user_id ?>;
<?php $enable_newsbeat = get_option('chartbeat_enable_newsbeat');
if ($enable_newsbeat) { ?>
_sf_async_config.domain = '<?php echo esc_attr($_SERVER['HTTP_HOST']); ?>';
<?php 
// Only add these values on blog posts use the queried object in case there
// are multiple Loops on the page.
if (is_single()) {
    global $wp_query;
    $post = $wp_query->get_queried_object();

    // Use the author's display name 
    $author = esc_attr(get_the_author_meta('display_name', $post->post_author)); 
    printf("_sf_async_config.authors = '%s';\n", $author);

    // Use the post's categories as sections
    $cats = get_the_terms($post->ID, 'category');
    if ($cats) {
        $cat_names = array();
        foreach ($cats as $cat) {
            $cat_names[] = '"' . esc_attr($cat->name) . '"';
        }
    }
    if ( count( $cat_names ) ) {
        printf("_sf_async_config.sections = [%s];\n", 
            implode(', ', $cat_names));
    }
}
?>
<?php } // if $enable_newsbeat ?>

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

// Dashboard Widget

function chartbeat_dashboard_widget_function() {
?>
<script type="text/javascript" language="javascript"> 
	jQuery.getJSON('http://api.chartbeat.com/live/quickstats/?host=<?php echo esc_attr($_SERVER['HTTP_HOST']); ?>&apikey=<?php echo get_option('chartbeat_apikey')?>',
		function(data) {
			jQuery("#chartbeat_dashboard_widget #vts").after(data.visits);
			jQuery("#chartbeat_dashboard_widget #dr").after(data.direct);
			jQuery("#chartbeat_dashboard_widget #in").after(data.internal);
			jQuery("#chartbeat_dashboard_widget #so").after(data.social);
			jQuery("#chartbeat_dashboard_widget #sr").after(data.search);
			jQuery("#chartbeat_dashboard_widget #pl").after(data.domload);
		}
	);
</script>
<div id="cb_stats">
<span id="vts" class="tl">Active Vists: </span><br/>
<span id="dr" class="tl">Direct Traffic: </span> <br/>
<span id="in" class="tl">Internal Traffic: </span> <br/>
<span id="so" class="tl">Social Traffic: </span> <br/>
<span id="sr" class="tl">Search Traffic </span> <br/>
<span id="pl" class="tl">Average Page Load: </span> <br/>
</div>
<script type="text/javascript" language="javascript">
	google.load('visualization', '1', {'packages':['annotatedtimeline']});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
		jQuery.getJSON('http://api.chartbeat.com/historical/dashapi/data_series/?host=<?php echo esc_attr($_SERVER['HTTP_HOST']); ?>&apikey=<?php echo get_option('chartbeat_apikey')?>&days=2&minutes=20&type=summary&val=people',
			function(cb_data) { 
				var post_dates = [<?php
				// Create a new filtering function that will add our where clause to the query
				function filter_where( $where = '' ) {
					$where .= " AND post_date > '" . date('Y-m-d', strtotime('-2 days')) . "'";
					return $where;
				}
				add_filter( 'posts_where', 'filter_where' );
				$args = array('post_type'=>array('post'),'post_status'=>'any','orderby' => 'date', 'order' => 'ASC' );
				$the_query = new WP_Query( $args );
				remove_filter( 'posts_where', 'filter_where' );
				$i = 1;
				while( $the_query->have_posts() ): $the_query->the_post(); 
				if ($i++ > 1): echo ','; endif; ?> [new Date(<?php echo the_time('Y,n-1,j,G,i'); ?>),'<?php echo the_title(); ?>']
			<?php endwhile; 
				wp_reset_postdata();?>
				];
				var strdate = new Date(cb_data.dates[0]*1000);
				while(post_dates[0][0] < strdate ) post_dates.shift();
				var data = new google.visualization.DataTable();
					data.addColumn('datetime', 'Time');
					data.addColumn('number', 'People');
					data.addColumn('string', 'Title');
					data.addColumn('string', 'Note');
				var rows = [];
					jQuery.each(cb_data.dates,function(i,utime) {
						var row = [new Date(utime*1000), cb_data.people[i],undefined,undefined];
						if(post_dates.length > 0 && row[0] >= post_dates[0][0]) row[2] = post_dates.shift()[1];
						rows.push(row);
					});
					data.addRows(rows);
				
				console.debug(rows);
				var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('cb_graph'));
				chart.draw(data, {displayAnnotations: true, dateFormat:'HH:mm MMMM dd, yyyy', legendPosition:'newRow', scaleType:'maximized', zoomEndTime: new Date()});
			}
		);
	};
</script>
<div id="cb_graph" style='width: 600px; height: 240px;'></div>
<?php
} 

function chartbeat_add_dashboard_widgets() {
	wp_enqueue_script( 'charttools' );
	wp_add_dashboard_widget('chartbeat_dashboard_widget', 'Chartbeat Dashboard Widget', 'chartbeat_dashboard_widget_function');	
} 

function chartbeat_plugin_admin_init() {
    wp_register_script( 'charttools','https://www.google.com/jsapi');
}

add_action('wp_dashboard_setup', 'chartbeat_add_dashboard_widgets' );
add_action( 'admin_init', 'chartbeat_plugin_admin_init' );

// Add Column to Blog Manager
add_filter('manage_posts_columns', 'chartbeat_columns');
function chartbeat_columns($defaults) {
	add_action('wp_enqueue_scripts', 'ensurejquery_method');
    $defaults['cb_visits'] = __('Active Vists');
    return $defaults;
}
add_action('manage_posts_custom_column', 'chartbeat_custom_columns', 10, 2);
function chartbeat_custom_columns($column_name, $id) {
    if( $column_name == 'cb_visits' ) {
		$post_url = parse_url(get_permalink( $id )); ?>

<script type="text/javascript" language="javascript">
	jQuery.getJSON('http://api.chartbeat.com/live/quickstats/?host=<?php echo esc_attr($_SERVER['HTTP_HOST']); ?>&apikey=<?php echo get_option('chartbeat_apikey')?>&path=<?php echo urlencode ($post_url["path"])?>',
		function(data) {
			if ( !data.visits ) data.visits = 0;
			jQuery('#post-<?php echo $id; ?> .cb_visits').append(data.visits);
		}
	);
</script>
<?php
    }
}


// If admin register settings on page that have been saved
// if not, add content to wp_head and wp_footer.
if ( is_admin() ){ 
  add_action( 'admin_init', 'chartbeat_register_settings' );
}else {
  add_action('wp_head', 'add_chartbeat_head');
  add_action('wp_footer', 'add_chartbeat_footer');
}
?>
