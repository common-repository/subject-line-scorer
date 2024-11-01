<?php  



add_shortcode( 'title_replaced', 'slsc_title_replaced' );
function slsc_title_replaced( $atts, $content = null ){
	
 
	if( !is_Admin() ){
		 
	$settings = get_option('wta_options');
		 
		 
	$title = stripslashes( trim( $atts['title'] ) );
 
	/* modify string */
 
	$edited_filtered_title = htmlspecialchars_decode( $title );
	$edited_filtered_title = preg_replace ('/&#x.+;/', '', $edited_filtered_title);	 
	$edited_filtered_title = str_replace("&nbsp;", '', $edited_filtered_title);
	$edited_filtered_title = str_replace(" ", '', $edited_filtered_title);
 
	$edited_filtered_title = preg_replace("/[^A-Za-z0-9 ]/", '', $edited_filtered_title);
	/* modui  end */
	
 	
	$titles_cache = get_option('title_cache');
 
	$points = $titles_cache[md5( $edited_filtered_title )];
	
	
	if( $points < 40 ){
		$graph_color = '#FD7378';
		$style_class = ' scorer_red ';
		$item_message = 'Based on the results of 100k email campaigns, this subject line would perform <b>poorly</b>';
	}
	if( $points >= 40 &&  $points <= 74 ){
		$style_class = ' scorer_yellow ';
		$graph_color = '#FFC959';
		$item_message = 'Based on the results of 100k email campaigns, this subject line would perform  <b>fairly</b>';
	}
	if( $points >  74   ){
		$style_class = ' scorer_green ';
		$graph_color = '#7FC698';
		$item_message = 'Based on the results of 100k email campaigns, this subject line would perform  <b>remarkably</b>';
	}
	$rand = rand(1, 999999);
	$out .= '
	<style>
	.checked_title_block{
		display:block;
	}
	.checked_title_block  .scorer_yellow{
		background-color: #FFC959;
	}
	.checked_title_block .scorer_red{
		background-color: #FD7378; 
	}
	.checked_title_block .scorer_green{
		background-color: #7FC698;
	}
	.checked_title_block .title_pop{
		
		padding: 4px;
		border-radius: 15px;
		margin-left: 7px;
		color: #fff;
		cursor: pointer;
		font-size: 11px;
		width: 25px;
		height: 25px;
		display: inline-block;
		text-align: Center;
		
		Font-size:13px;
		Font-weight:600;
		display: inline-flex;
		align-items: center;
		justify-content: center;

		
	} 
	.pop_container{
		overflow: hidden;
		padding:10px;
		display: block;
		min-width: 250px;
		font-family: "Lato";
		color:#000;
	}
	.pop_container .col_left{
		float:left;
		width:80%;
		display: block;
	}
	.pop_container 	.col_right{
		    width: 20%;
			float: left;
			display: block;
			text-align: center;
			padding-top: 20px;
	}
	.pop_container .block_title{
		font-size: 14px !important;
		display: block;
		margin-bottom: 10px;
		font-weight: bold;
	}
	.pop_container .block_message{
		    Font-size:12px;
			Line-height:12px;

		display: block;
		margin-bottom: 10px;
	}
	.pop_container .block_more{
		text-transform: uppercase;
		display:block;
		margin-top:10px;
	}
	.pop_container .block_more a{
		color:#3aa0f9;
		font-weight:bold;
		font-size: 12px;
	}
	 .title_content{
		display:none;
	}
	</style>
	<span class="checked_title_block">
		<span class="title_content content_'.$rand.'">
			<span class="pop_container">
				<span class="col_left">
					<span class="block_title">Mizy scored this subject line</span>
					<span class="block_message">'.$item_message.'</span>';
					if( $settings['show_link'] == 'on' ){
						$out .= '
						<span class="block_more"><a href="https://automizy.com/tools/free-email-subject-line-tester/"  target="_blank">'.__('Test your subject line').'</a></span>';
					}
					$out .= '
				</span>
				<span class="col_right">
					<img src="'.plugins_url( '/images/Mizy-in-cirlce.svg', __FILE__ ).'" />
				</span>
			</span>
		</span>
		<span class="title_block">'.$title.'</span>
		<span class="title_pop '.$style_class.'" data-id="'.$rand.'">'.$points.'</span>
	</span>';
	
	return str_replace( "\n", "", $out );	
	}
}

  
 
?>