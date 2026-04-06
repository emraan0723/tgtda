
<?php 
### BVENKAT START DECEMBER 2018 27 th
#session_start();

//ob_start();
if (!isset($_SESSION['debug_info']))
{
	$_SESSION['debug_session'] = FALSE;
	$_SESSION['debug_info'] = array(
	'benchmarks'  => TRUE,
	'config' => TRUE,
	'controller_info'  => TRUE,
	'get' => TRUE,
	'http_headers'  => TRUE,
	'memory_usage' => TRUE,
	'post'  => TRUE,
	'queries' => TRUE,
	'uri_string'  => TRUE,
	'query_toggle_count' => '25'
	);
}
if (isset($_POST['Set']))
{
	
	if (isset($_POST['debug_info']))
		$_SESSION['debug_session'] = $_POST['debug_info']==1?TRUE:FALSE;
	else
		$_SESSION['debug_session'] = FALSE;
	$_SESSION['debug_info']['benchmarks'] = $_POST['benchmarks']==1?TRUE:FALSE;
	$_SESSION['debug_info']['config'] = $_POST['config']==1?TRUE:FALSE;
	$_SESSION['debug_info']['controller_info'] = $_POST['controller_info']==1?TRUE:FALSE;
	$_SESSION['debug_info']['get'] = $_POST['get']==1?TRUE:FALSE;
	$_SESSION['debug_info']['http_headers'] = $_POST['http_headers']==1?TRUE:FALSE;
	$_SESSION['debug_info']['memory_usage'] = $_POST['memory_usage']==1?TRUE:FALSE;
	$_SESSION['debug_info']['post'] = $_POST['post']==1?TRUE:FALSE;
	$_SESSION['debug_info']['queries'] = $_POST['queries']==1?TRUE:FALSE;
	$_SESSION['debug_info']['uri_string'] = $_POST['uri_string']==1?TRUE:FALSE;
	$_SESSION['debug_info']['query_toggle_count'] = $_POST['query_toggle_count'];
	$_SESSION['on_debug'] = isset($_POST['on_debug']) ? $_POST['on_debug'] : 'no';

	

	
	//unset($_SESSION['debug_session']);
}
function WriteSelection($check_name,$check_value)
{
	$checked = "";
	if ($check_value==TRUE) $checked = " Checked ";
	$return_val="<input type='checkbox' name='$check_name' value='1' $checked>";
	return $return_val;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title> Profiling </title>
</head>
<body>
	
	   <?php echo form_open(base_url().'index.php/welcome/debug'); ?>
<form  method="post" >
	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
<table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="tableborder">
		<tr>
			<th colspan=3 align=left>Debug Session <?=WriteSelection("debug_info",$_SESSION['debug_session'])?> <input type="submit" name="Set" value="Set"></th>
		</tr>
		<tr>
			<th>Key</th>
			<th>Description</th>
			<th>Default</th>
		</tr>
		<tr>
			<td class="td"><strong>benchmarks</strong></td>
			<td class="td">Elapsed time of Benchmark points and total execution time</td>
			<td class="td"><?=WriteSelection("benchmarks",$_SESSION['debug_info']['benchmarks'])?></td>
		</tr>
		<tr>
			<td class="td"><strong>config</strong></td>
			<td class="td">CodeIgniter Config variables</td>
			<td class="td"><?=WriteSelection("config",$_SESSION['debug_info']['config'])?></td>
		</tr>
		<tr>
			<td class="td"><strong>controller_info</strong></td>
			<td class="td">The Controller class and method requested</td>
			<td class="td"><?=WriteSelection("controller_info",$_SESSION['debug_info']['controller_info'])?></td>
		</tr>
		<tr>
			<td class="td"><strong>get</strong></td>
			<td class="td">Any GET data passed in the request</td>
			<td class="td"><?=WriteSelection("get",$_SESSION['debug_info']['get'])?></td>
		</tr>
		<tr>
			<td class="td"><strong>http_headers</strong></td>
			<td class="td">The HTTP headers for the current request</td>
			<td class="td"><?=WriteSelection("http_headers",$_SESSION['debug_info']['http_headers'])?></td>
		</tr>
		<tr>
			<td class="td"><strong>memory_usage</strong></td>
			<td class="td">Amount of memory consumed by the current request, in bytes</td>
			<td class="td"><?=WriteSelection("memory_usage",$_SESSION['debug_info']['memory_usage'])?></td>
		</tr>
		<tr>
			<td class="td"><strong>post</strong></td>
			<td class="td">Any POST data passed in the request</td>
			<td class="td"><?=WriteSelection("post",$_SESSION['debug_info']['post'])?></td>
		</tr>
		<tr>
			<td class="td"><strong>queries</strong></td>
			<td class="td">Listing of all database queries executed, including execution time</td>
			<td class="td"><?=WriteSelection("queries",$_SESSION['debug_info']['queries'])?></td>
		</tr>
		<tr>
			<td class="td"><strong>uri_string</strong></td>
			<td class="td">The URI of the current request</td>
			<td class="td"><?=WriteSelection("uri_string",$_SESSION['debug_info']['uri_string'])?></td>
		</tr>
		<tr>
			<td class="td"><strong>query_toggle_count</strong></td>
			<td class="td">The number of queries after which the query block will default to hidden.</td>
			<td class="td"><input type="text" width="3" maxlength="3" name="query_toggle_count" value="<?=$_SESSION['debug_info']['query_toggle_count']?>"></td>
		</tr>

		<tr>
			<td class="td"><strong>debug wrie on/off</strong></td>
			<td class="td">The number of queries after which the query block will default to hidden.</td>
			<td class="td"><input type="checkbox" width="3" maxlength="3" name="on_debug" 
				<?php echo isset($_SESSION['on_debug']) && $_SESSION['on_debug'] =='yes' ? 'checked=checked' : '' ?>  value="yes"></td>
		</tr>
	</table>
	<?php echo form_close(); ?>
</form>
</body>
</html>