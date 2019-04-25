<?php
//
// Metabox of the POST
// Set a unique slug-like ID
//
$prefix_post_opts = 'wppay-postmeta-box';

//
// Create a metabox
//
CSF::createMetabox( $prefix_post_opts, array(
  'title'        => '付费资源设置-NEW',
  'post_type'    => 'post',
  'show_restore' => true,
  'data_type' => 'unserialize',
  'priority' => 'high',
) );

//
// Create a section
//
CSF::createSection( $prefix_post_opts, array(
  'fields' => array(

  	array(
      'id'      => 'wppay_type',
      'type'    => 'select',
      'title'   => '资源类型',
      'inline'  => true,
      'options' => array(
        '0' => '不启用',
        '1' => '付费查看全文',
        '2' => '部分内容（利用短代码[wppay]隐藏内容[/wppay]）',
        '3' => '收费下载',
		    '4' => '免费下载',
      ),
      'default' => 0,
    ),

    array(
      'id'      => 'wppay_vip_auth',
      'type'    => 'select',
      'title'   => '会员权限',
      'subtitle'   => '权限关系是包含关系，终身可查看年月',
      'inline'  => true,
      'options' => array(
	        '0' => '不启用',
            '1' => '月费-会员免费',
            '2' => '年费-会员免费',
            '3' => '终身-会员免费',
      ),
      'default' => 0,
    ),
    array(
      'id'    => 'wppay_price',
      'type'  => 'text',
      'title' => '收费价格',
      'subtitle'   => '请输入数字',
      'default'   => '0.1',
      'validate' => 'csf_validate_numeric',
    ),
    // 下载地址 新
    array(
      'id'     => 'wppay_down',
      'type'   => 'group',
      'title'  => '下载资源',
      'subtitle'  => '支持多个下载地址，支持https:,thunder:,magnet:,ed2k 开头地址',
      'accordion_title_number' => true,
      'fields' => array(
        array(
          'id'    => 'name',
          'type'  => 'text',
          'title' => '资源名称',
          'default' => '资源名称',
        ),
        array(
          'id'    => 'url',
          'type'  => 'text',
          'title' => '下载地址',
          'default' => '#',
        ),
        array(
          'id'    => 'pwd',
          'type'  => 'text',
          'title' => '下载密码',
        ),
        array(
          'id'    => 'lock',
          'type'  => 'switcher',
          'title' => '下载地址加密',
          'default' => true,
          'label' => '个别地址无法加密下载无法解析可关闭',
        ),
      ),
    ),
    array(
      'id'    => 'wppay_demourl',
      'type'  => 'text',
      'title' => '演示地址',
      'subtitle'   => '为空则不显示',
    ),
    array(
      'id'     => 'wppay_info',
      'type'   => 'repeater',
      'title'  => '资源其他信息',
      'fields' => array(
        array(
          'id'    => 'title',
          'type'  => 'text',
          'title' => '标题',
          'default' => '标题',
        ),
        array(
          'id'    => 'desc',
          'type'  => 'text',
          'title' => '描述内容',
          'default' => '这里是描述内容',
        ),
      ),
    ),
  )
) );

//
// Metabox of the PAGE and POST both.
// Set a unique slug-like ID
//
$prefix_meta_opts = '_prefix_meta_options';

//
// Create a metabox
//
CSF::createMetabox( $prefix_meta_opts, array(
  'title'     => '文章顶部背景图',
  'post_type' => array( 'post'),
  'context'   => 'side',
  'data_type' => 'unserialize',
) );

//
// Create a section
//
CSF::createSection( $prefix_meta_opts, array(
  'fields' => array(
    array(
      'id'        => 'single_header_img',
      'type'      => 'media',
      'desc'      => '图片建议尺寸统一：'.'1920*600,不设置则自动使用缩略图',
    ),
  )
) );




//
// Set a unique slug-like ID
//
$prefix = '_rizhuti_profile_options';

//
// Create profile options
//
CSF::createProfileOptions( $prefix, array(
  'data_type' => 'unserialize'
) );

//
// Create a section
//
CSF::createSection( $prefix, array(
  'title'  => '会员其他信息-Rizhuti',
  'fields' => array(

    array(
      'id'          => 'vip_type',
      'type'        => 'select',
      'title'       => '会员类型',
      'placeholder' => '选择会员类型',
      'options'     => array(
        '0'     => '普通会员',
        '31'     => '包月会员',
        '365'     => '包年会员',
        '3600'     => '终身会员',
      ),
    ),
    array(
      'id'    => 'vip_time',
      'type'  => 'text',
      'title' => '会员到期时间戳',
      'subtitle' => '时间戳转换：https://tool.lu/timestamp/',
    ),
    
   

  )
) );
