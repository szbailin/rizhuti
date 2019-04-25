<?php

if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in())
{
	wp_die('请先登录系统');
}
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $wppay_table_name;

$action=isset($_GET['action']) ?$_GET['action'] :false;
$id=isset($_GET['id']) && is_numeric($_GET['id']) ?intval($_GET['id']) :0;
// 清理无效订单
if ($action=="remosql" && current_user_can('administrator')) {
	$del_order = $wpdb->query("DELETE FROM $wppay_table_name WHERE status = 0 ");
	echo'<div class="updated settings-error"><p>成功清理'.$del_order.'条无效订单！</p></div>';
}





// 保存
if($action=="save" && current_user_can('administrator')){
	$result = isset($_POST['result']) && is_numeric($_POST['result']) ?intval($_POST['result']) :0;
	$update_order = $wpdb->query("UPDATE $wppay_table_name SET pay_num = '88888888', pay_time = '".time()."' ,status='".$result."' WHERE id = '".$id."'");
	if(!$update_order){
		echo '<div id="message" class="updated notice is-dismissible"><p>系统更错处理失败</p></div>';
	}
	else {
		echo '<div id="message" class="updated notice is-dismissible"><p>更新成功</p></div>';
	}
	unset($id);
}

// 内页
if($id && current_user_can('administrator'))
{
	$info=$wpdb->get_row("SELECT * FROM $wppay_table_name where id=".$id);
	if(!$info->id)
	{
		echo '<div id="message" class="updated notice is-dismissible"><p>订单ID无效</p></div>';
		exit;
	}
	?>
<div class="wrap">
<h1 class="wp-heading-inline">查看订单详情</h1>
    <hr class="wp-header-end">
<form method="post" action="<?php echo admin_url('admin.php?page=wppay_orders_page&action=save&id='.$id); ?>" >
    <table class="form-table">
        <tr>
            <td valign="top" width="30%"><strong>订单号</strong><br />
            </td>
            <td><?php echo $info->order_num?></td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>用户ID</strong><br />
            </td>
            <td><?php echo $userName = ($info->user_id != 0 ) ? get_user_by('id',$info->user_id)->user_login : '游客' ; ?></td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>商品名称</strong><br />
            </td>
            <td><?php echo get_the_title($info->post_id)?>
            </td>
        </tr>
         <tr>
            <td valign="top" width="30%"><strong>价格</strong><br />
            </td>
            <td><?php echo $info->order_price ?>
            </td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>支付状态</strong><br />
            </td>
            <td><input type="radio" name="result" id="res1" value="1" <?php if($info->status==1) echo "checked";?>/>已支付 
            <input type="radio" name="result" id="res1" value="0" <?php if($info->status==0) echo "checked";?>/>未支付
            </td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>下单时间</strong><br />
            </td>
            <td><?php echo date('Y-m-d h:i:s',$info->create_time) ?>
            </td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>支付时间</strong><br />
            </td>
            <td><?php echo $times = ($info->pay_time) ? date('Y-m-d h:i:s',$info->pay_time) : '' ; ?>
            </td>
        </tr>
        <tr>
            <td valign="top" width="30%"><strong>支付商户订单号</strong><br />
            </td>
            <td><?php echo $info->pay_num ?>
            </td>
        </tr>
	</table>
    <table> 
    	<tr>
        <td><p class="submit">
            <input type="submit" name="Submit" value="保存设置" class="button-primary"/>
            </p>
        </td>
        </tr> 
    </table>

</form>
</div>
<?php
exit;
}

$year = date("Y");$month = date("m");$day = date("d");$dayBegin = mktime(0, 0, 0, $month, $day, $year); 
$dayEnd = mktime(23, 59, 59, $month, $day, $year);
$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
$endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));

$total   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE order_type =1");
$totalfkdd   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE create_time > $beginThismonth AND create_time < $endThismonth AND status =1 ");
$totalfkje   = $wpdb->get_var("SELECT SUM(order_price) FROM $wppay_table_name WHERE create_time > $beginThismonth AND create_time < $endThismonth AND status =1");
$perpage = 20;
$pages = ceil($total / $perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $perpage*($page-1);
$list = $wpdb->get_results("SELECT * FROM $wppay_table_name WHERE order_type =1 ORDER BY create_time DESC limit $offset,$perpage");


// 今日总订单
$jrzdd   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE create_time > $dayBegin AND create_time < $dayEnd ");
// 今日付款订单
$jrzfkdd   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE create_time > $dayBegin AND create_time < $dayEnd AND status =1");
// 今日收入
$jrzfkddje   = $wpdb->get_var("SELECT SUM(order_price) FROM $wppay_table_name WHERE create_time > $dayBegin AND create_time < $dayEnd AND status =1");
?>

<!-- 默认显示页面 -->
<div class="wrap">
	<h1 class="wp-heading-inline">所有订单</h1>
  	<a href="<?php echo admin_url('admin.php?page=wppay_orders_page&action=remosql'); ?>"  onclick="javascript:if(!confirm('确定清理无效订单？')) return false;" class="page-title-action">清理无效订单</a>
    <hr class="wp-header-end">
	<!-- 统计信息 -->
	<div class="row" style="display: flex;background-color: #fff;padding: 10px;justify-content: space-around;border: 1px solid #e5e5e5;box-shadow: 0 1px 1px rgba(0,0,0,.04);">
		<div style=" width: 50%; padding: 20px; text-align: center;background-color: #f1f1f1; border: 1px solid #e5e5e5; box-shadow: 0 1px 1px rgba(0,0,0,.04); ">
			<h4>今日</h4>
			<h4>今日订单：<?php echo $jrzdd ?>&nbsp;&nbsp;&nbsp;&nbsp;已付款：<?php echo $jrzfkdd ?></h4>
			<h4>今日收入：<?php echo $jrzfkddjse = ($jrzfkddje) ? $jrzfkddje : 0 ; ?> RMB</h4>
		</div>
		<div style=" width: 50%;text-align: center;padding: 20px; background-color: #f1f1f1; border: 1px solid #e5e5e5; box-shadow: 0 1px 1px rgba(0,0,0,.04); ">
			<h4>本月总览</h4>
			<h4>总订单：<?php echo $total ?>&nbsp;&nbsp;&nbsp;&nbsp;已付款：<?php echo $totalfkdd ?></h4>
			<h4>总收入：<?php echo $totalfkje ?> RMB</h4>
		</div>
	</div>
	<!-- 统计结束 -->
	
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>订单号</th>
				<th>用户ID</th>	
				<th>商品名称</th>
				<th>价格</th>
				<th>状态</th>
				<th>下单时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
	<?php
		if($list) {
			foreach($list as $value){
				echo "<tr id=\"order-info\" data-num=\"$value->order_num\">\n";
				echo "<td>".$value->order_num."</td>";
				if($value->user_id){
					echo "<td>".get_user_by('id',$value->user_id)->user_login."</td>";
				}else{
					echo "<td>游客</td>";
				}
				echo "<td><a target='_blank' href='".get_permalink($value->post_id)."'>".get_the_title($value->post_id)."</a></td>\n";
				echo "<td>".$value->order_price."</td>\n";
				$statusno = ($value->status == 0) ? 'selected="selected"' : '' ;
				$statusyes = ($value->status == 1) ? 'selected="selected"' : '' ;
				$bgcolor = ($value->status == 1) ? '#b6ffb6' : '#ff8a81' ;
				echo '<td><select class="select" id="status" name="status" disabled style=" background-color: '.$bgcolor.'; ">
				    <option '.$statusno.' value="0">
				        未支付
				    </option>
				    <option '.$statusyes.' value="1">
				        已支付
				    </option>
				</select></td>';
				echo '<td>'.date('Y-m-d h:i:s',$value->create_time).'</td>';
				echo '<td><a href="'.admin_url('admin.php?page=wppay_orders_page&id='.$value->id).'">操作/详情</a></td>';
				
				echo "</tr>";
			}
		}
		else{
			echo '<tr><td colspan="6" align="center"><strong>没有订单</strong></td></tr>';
		}
	?>
	</tbody>
	</table>
    <?php echo c_admin_pagenavi($total,$perpage);?>
    <script>
            jQuery(document).ready(function($){

            });
	</script>
</div>
