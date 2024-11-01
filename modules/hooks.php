<?php 
 add_Action('admin_footer', 'slsc_admin_footer');
 function slsc_admin_footer(){
	 echo '
	 
	 <input type="hidden" id="last_ta_id" value="" />
	 
	 <a data-fancybox href="#submission_message" id="click_submission_message"></a>
	 <div style="display:none;">
	 <div id="submission_message">
	 </div>
	 </div>
	 
	 
	 
	<style>
	#submission_message .empty_title{
		overflow:hidden;
		margin-top:30px;
	}
	#submission_message .empty_title img{
		width:25px;
		float:right;
	}
	.upper_text{
		text-transform:uppercase  !important;
	}
	#submission_message .empty_title .inner_text{
		    float: left;
		margin-right: 20px;
		margin-top: 10px;
		margin-left: 10px;
	}
	#new_score_cont{
		font-family: "Lato", sans-serif;
		border-radius:8px;
	}
	#new_score_cont a:hover{
		 
		text-decoration: none !important;
	}
	.btn-custom-green{
		color:#fff;
		background-color:#7ED321;
	}
	.btn-custom-green:hover{
		background-color:#8DE62C;
	}
	.btn-custom-green:active{
		background-color:#6DB51C;
	}
	.scorer_container{
		max-width:600px;
		background:#fff;
		margin-left:400px;
	}
	.scorer_container .left_scorer{
		font-size:20px;
		font-weight:bold;
		margin-bottom:15px;
	}	
	.scorer_container .scorer_result_cont .single_title_line .single_title_text.active_title{
		color: #2094F9;
		font-weight:600;
	}
	.scorer_container .scorer_result_cont .single_title_line{
		overflow: hidden;
		margin-bottom:10px;
		cursor:pointer;
	}
	.scorer_container .scorer_result_cont .single_title_line .single_title_text{
		float:left;
		width:80%;
		font-size:14px;
		padding: 5px 0px;
		color: #4A4A4A;
	}
	.scorer_container .scorer_result_cont .single_title_line .single_title_score{
		float:left;
		width:20%;
	}
	.scorer_container .scorer{
		    width: 25px;
		height: 25px;
		color: #fff;
		font-size: 13px;
		border-radius: 15px;
		padding: 3px 5px;
		text-align: center;
	}
	.scorer_container .scorer.scorer_yellow{
		background-color: #FFC959;
	}
	.scorer_container .scorer.scorer_red{
		background-color: #FD7378; 
	}
	.scorer_container .scorer.scorer_green{
		background-color: #7FC698;
	}
	
	.scorer_container .right_top_msg{
		text-align:center;
	}
	.scorer_container .right_top_msg .right_subject_title{
		font-size: 16px;
		font-weight: bold;
		margin-bottom: 10px;
	}
	.scorer_container .right_top_msg .right_subject_subtitle{
		font-size:14px;
		color:#ccc;
		margin-bottom:10px;
	}
	.scorer_container .fake_del_button{
		cursor:pointer;
		width: 32px;
		height: 32px;
		color: #fff;
		font-size: 15px;
		border-radius: 15px;
		background: #FF3840;
		padding: 5px 10px;
		margin: 0px auto;
	}
	.scorer_container .fake_del_button:hover{
		background: #FF4F56;
	}
	.scorer_container .fake_del_button:active{
		background: #DE2D34;
	}
	.scorer_container  .extra_button_padding{
		padding: 5px 25px !important;
	}
	.scorer_container .cancel_button{
		    font-size: 15px;
		color: #4A4A4A;
		cursor: pointer;
		margin-top: 5px;
		display: inline-block;
	}
	.fancybox-content{
		padding:10px !important;
	}
	.scorer_container .test_more_button, .test_more_button:hover{
					 
					text-transform:uppercase;
					color: #A4A4A4;
					border-radius:10px;
					padding:10px;
					text-align:center;
					-webkit-box-shadow: 2px 2px 15px 0px rgba(0,0,0,0.1);
					-moz-box-shadow: 2px 2px 15px 0px rgba(0,0,0,0.1);
					box-shadow: 2px 2px 15px 0px rgba(0,0,0,0.1);
	}
	.scorer_container  .test_more_button .small_image{
		float:left;
		width:13px;
	}
	.scorer_container .scorer_result_cont{
		max-height: 180px;
		overflow-y: auto;
	}
	.scorer_container .small_powered{
		font-size:10px;
		font-style: italic;
		border:0px !important;
	}
	.scorer_container .small_powered a, .scorer_container .small_powered a:active, .scorer_container .small_powered a:hover{
		border:0px !important;
	}
	.scorer_container .small_powered a{
		color:#CACACA;
	}
	.scorer_container .small_powered a:hover{
		color: 		#E2E0E0;
	}
	.scorer_container .small_powered a:active{
		color: 		#A7A7A7;
	}
	.color_fix{
		background:#fbfbfb;
	}
	</style>
	 
	 <div class="popup_overlaper">
	 <a href="#new_score_cont" data-fancybox id="fake_new_block" data-options=\'{"touch" : false}\'></a>
		<div id="new_score_cont" class="scorer_container tw-bs4" style="width:700px;">
			
		</div>
	 </div>
	 
	 ';
 }

?>