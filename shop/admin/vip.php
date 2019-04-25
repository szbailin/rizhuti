<?php

if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in())
{
	wp_die('请先登录系统');
}
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $wppay_table_name;
?>

<script type="text/javascript">
　　window.location.href="<?php echo admin_url('/users.php'); ?>";
</script>


<?php

exit();
$action=isset($_GET['action']) ?$_GET['action'] :false;
$id=isset($_GET['id']) && is_numeric($_GET['id']) ?intval($_GET['id']) :0;
// 保存
if($action=="save" && current_user_can('administrator'))
{
	$viptype = isset($_POST['viptype']) && is_numeric($_POST['viptype']) ?intval($_POST['viptype']) :0;
	$daysnum = isset($_POST['daysnum']) && is_numeric($_POST['daysnum']) ?intval($_POST['daysnum']) :0;
	$usersuid = isset($_POST['usersuid']) && is_numeric($_POST['usersuid']) ?intval($_POST['usersuid']) :0;
	// // 写入usermeta
	if ($usersuid) {
		if ( true ) {
			//更新等级 
			if (update_user_meta( $usersuid, 'vip_type', $viptype )) {
				echo '<div id="message" class="updated notice is-dismissible"><p>会员类型更新成功</p></div>';
			}
		}

		if ($daysnum !=0) {
			$this_vip_time=get_user_meta($usersuid,'vip_time',true); //当前时间
		    $time_stampc = intval($this_vip_time)-time();// 到期时间减去当前时间
		    if ($time_stampc > 0) {
		        $nwetimes= intval($this_vip_time);
		    }else{
		        $nwetimes= time();
		    }
			update_user_meta( $usersuid, 'vip_time', $nwetimes+$daysnum*24*3600 );   //更新到期时间
			echo '<div id="message" class="updated notice is-dismissible"><p>更新成功，会员到期时间为：'.round($nwetimes+($daysnum*24*3600)).'</p></div>';
		}
	}
	
	unset($id);
}

// 内页
if($id && current_user_can('administrator'))
{
	$info=$wpdb->get_row("SELECT * FROM $wpdb->users WHERE ID = '".$id."'");

	if(!$info->ID)
	{
		echo '<div id="message" class="updated notice is-dismissible"><p>会员ID无效</p></div>';
		exit;
	}
	?>
	<div class="wrap">
   	<h2>查看/修改会员详情</h2>
<form method="post" action="<?php echo admin_url('admin.php?page=wppay_vip_page&action=save&id='.$id); ?>" style="width:70%;float:left;background-color: #fff;padding: 20px;">

        <table class="form-table">
            <tr>
                <td valign="top" width="30%"><strong>用户ID</strong><br />
                </td>
                <td><?php echo $info->user_login?></td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>会员邮箱</strong><br />
                </td>
                <td><?php echo $info->user_email?>
                </td>
            </tr>
             <tr>
                <td valign="top" width="30%"><strong>会员昵称</strong><br />
                </td>
                <td><?php echo $info->display_name ?>
                </td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>会员类型（当前：<?php echo vip_type_name($info->ID) ?>）</strong><br />
                </td>
                <td>
                	<input type="radio" name="viptype" id="viptype" value="0" <?php if(vip_type($info->ID)==0) echo "checked";?>/>普通会员 
                	<input type="radio" name="viptype" id="viptype" value="31" <?php if(vip_type($info->ID)==31) echo "checked";?>/>包月会员
                	<input type="radio" name="viptype" id="viptype" value="365" <?php if(vip_type($info->ID)==365) echo "checked";?>/>包年会员
                	<input type="radio" name="viptype" id="viptype" value="3600" <?php if(vip_type($info->ID)==3600) echo "checked";?>/>终身会员
                </td>
            </tr>
            
            <!-- 到期时间 -->
            <tr>
                <td valign="top" width="30%"><strong>会员到期时间</strong><br /></td>
                <td><?php echo vip_time($info->ID); ?> （从未开通过则显示当前时间）</td>
            </tr>

            <tr>
            <td valign="top" width="30%"><strong>增加会员天数</strong><br />
                </td>
                <td>
                	<input type="number" name="daysnum" id="daysnum" value="0" /> 可以按天数,-1则等于减去一天
                </td>
            </tr>

    </table>
        <br /> <br />
        <table> <tr>
        <td><p class="submit">
        	<input type="hidden" name="usersuid" id="usersuid" value="<?php echo $info->ID ?>" />
            <input type="submit" name="Submit" value="保存设置" class="button-primary"/>
            </p>
        </td>

        </tr> </table>

</form>
			</div>
	<?php
	exit;
}

?>

