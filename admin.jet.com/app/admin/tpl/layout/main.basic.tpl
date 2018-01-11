<!DOCTYPE html>
<!--
*	基础模板，所有模板的父模板 
*	@ 乔成磊 20150827
-->
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo site_title(); ?></title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="static/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="static/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<link rel="stylesheet" href="static/assets/css/jquery-ui.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="static/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="static/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="static/assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="static/assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="static/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="static/assets/js/html5shiv.js"></script>
		<script src="static/assets/js/respond.js"></script>
		<![endif]-->
		
		
		<!-- 	乔成磊 20150830	 -->
		<!--  datatables 	-->
<!-- 
		<link href="static/js/Datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
		<link href="static/js/dt1.10.9/union/jquery.dataTables.min.css" rel="stylesheet" >
		<link href="static/js/dt1.10.9/union/buttons.dataTables.min.css" rel="stylesheet" >
		<link href="static/js/dt1.10.9/union/select.dataTables.min.css" rel="stylesheet" >
		<link href="static/js/dt1.10.9/e1.5.1/css/editor.dataTables.min.css" rel="stylesheet" >
 -->
		
		
		<link href="static/js/dt1.10.10/jquery.dataTables.min.css" rel="stylesheet" >
		<link href="static/js/dt1.10.10/buttons.dataTables.min.css" rel="stylesheet" >
		<link href="static/js/dt1.10.10/select.dataTables.min.css" rel="stylesheet" >
		<link href="static/js/dt1.10.10/editor.dataTables.min.css" rel="stylesheet" >
		
		
		<link href="static/js/DataTablesExt/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet" >


		<!-- 以下两个文件同名，虽有重复，但前者不能去（选中时蓝色显示效果） -->
		<link href="static/js/dt1.10.9/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" >
		<link href="static/js/dt1.10.9/css/dataTables.bootstrap.css" rel="stylesheet">	
	
		<style>
			
/* 
			.DTTT_button_text ,.DTTT_button_print{
				background-color: #FFFFFF !important;
			}
 */
			.btn-group > .btn, .btn-group + .btn {
				border-width: 1px;
			}
			
			/*整个表格的边界*/
			table.dataTable.no-footer, table.dataTable thead th, table.dataTable thead td{
				border:1px solid #DDDDDD;
			}
			/*表头排序时样式*/
			.dataTable > thead > tr > th.sorting_desc, .dataTable > thead > tr > th.sorting_asc{
				background-image: linear-gradient(to bottom, #FFFFFF 0%, #EEEEEE 0%);
			}
			/*表头其它*/
			table.dataTable thead th, table.dataTable thead td, table.dataTable thead tr{
				padding: 5px 10px 5px 5px;
				background-image: linear-gradient(to bottom, #FFFFFF 0%, #EEEEEE 0%);
				border:1px solid #DDDDDD;
				border-bottom:0px ;
				border-width: 1px;
			}
			
			.dataTable > thead > tr > th.sorting_disabled{
				color:#777777;
			}
		</style>
	</head>
	
	<?php echo $dorisContent;?>



		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='static/assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='static/assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='static/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="static/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="static/assets/js/excanvas.js"></script>
		<![endif]-->
		 
		<script src="static/assets/js/jquery-ui.custom.js"></script>
		<script src="static/assets/js/jquery.ui.touch-punch.js"></script>
		<script src="static/assets/js/jquery.easypiechart.js"></script>
		<script src="static/assets/js/jquery.sparkline.js"></script>
		<script src="static/assets/js/flot/jquery.flot.js"></script>
		<script src="static/assets/js/flot/jquery.flot.pie.js"></script>
		<script src="static/assets/js/flot/jquery.flot.resize.js"></script>


		
		
		<!-- ace scripts -->
		<script src="static/assets/js/ace/elements.scroller.js"></script>
		<script src="static/assets/js/ace/elements.colorpicker.js"></script>
		<script src="static/assets/js/ace/elements.fileinput.js"></script>
		<script src="static/assets/js/ace/elements.typeahead.js"></script>
		<script src="static/assets/js/ace/elements.wysiwyg.js"></script>
		<script src="static/assets/js/ace/elements.spinner.js"></script>
		<script src="static/assets/js/ace/elements.treeview.js"></script>
		<script src="static/assets/js/ace/elements.wizard.js"></script>
		<script src="static/assets/js/ace/elements.aside.js"></script>
		<script src="static/assets/js/ace/ace.js"></script>
		<script src="static/assets/js/ace/ace.ajax-content.js"></script>
		<script src="static/assets/js/ace/ace.touch-drag.js"></script>
		<script src="static/assets/js/ace/ace.sidebar.js"></script>
		<script src="static/assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="static/assets/js/ace/ace.submenu-hover.js"></script>
		<script src="static/assets/js/ace/ace.widget-box.js"></script>
		<script src="static/assets/js/ace/ace.settings.js"></script>
		<script src="static/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="static/assets/js/ace/ace.settings-skin.js"></script>
		<script src="static/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="static/assets/js/ace/ace.searchbox-autocomplete.js"></script>
		

		<!-- page specific plugin scripts -->
		<!-- 	乔成磊 20150830	  jquery.dataTables.min.js 要跟着新版本升级，不然，会有编辑时转菊花的BUG	 -->
		<!-- <script src="static/js/dt1.10.9/union/jquery.dataTables.min.js"></script> -->
		<script src="static/js/dt1.10.10/jquery.dataTables.min.js"></script>
		
		<script src="static/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
		<script src="static/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
		

<!-- 

		<script src="static/js/dt1.10.9/union/dataTables.buttons.min.js"></script>
		<script src="static/js/dt1.10.9/union/dataTables.select.min.js"></script>
		<script src="static/js/dt1.10.9/union/dataTables.editor.min.js"></script>
 -->
		
		<script src="static/js/dt1.10.10/moment.min.js"></script>
		<script src="static/js/dt1.10.10/dataTables.buttons.min.js"></script>
		<script src="static/js/dt1.10.10/dataTables.select.min.js"></script>
		<script src="static/js/dt1.10.10/dataTables.editor.min.js"></script>
 		<script src="static/js/Datetimepicker/bootstrap-datetimepicker.min.js"></script>
		


		<!--	其它  	-->
		<script src="static/js/jquery/jquery.cookie.js"></script>
		<script src="static/js/DataTablesExt/dataTables.fnFindCellRowIndexes.js"  type="text/javascript" charset="utf-8" ></script>
		<script src="static/js/DataTablesExt/DataTables_PagingExt.js" type="text/javascript" ></script>
		
		
		
		<script src="static/js/lingua/jquery.lingua.ex.js" type="text/javascript"></script>
		<script src="static/js/lingua/lan.lingua.js" type="text/javascript"></script>
		
		<!-- 	Datetimepicker	 -->
<!-- 

		<link rel="stylesheet" href="static/assets/css/bootstrap-datetimepicker.css" />
		<script src="static/assets/js/date-time/moment.js " />
		<script src="static/assets/js/date-time/bootstrap-datetimepicker.js " /> 
 -->

 		<link href="static/js/datetime/bootstrap-datetimepicker.min.css"  rel="stylesheet"/>
		<script src="static/js/datetime/bootstrap-datetimepicker.js" />
		<script src="static/js/datetime/locales/bootstrap-datetimepicker.zh-CN.js " />
		

		<script src="/static/js/cas_menu.js"></script>
		<script src="/static/js/md5.js"></script>
		<script src="/static/js/main.js"></script>


	
		<?php
		
			//加载主页面中内嵌JS文件模板 
			if(isset($mainPageJsContent))
				echo $mainPageJsContent;
				
			//加载当前面中内嵌JS文件模板
			if(isset($currentPageJsContent))
				echo $currentPageJsContent;
		?>
		
</html>

