<?php 

add_action('wp_ajax_verify_title', 'slsc_verify_title');
add_action('wp_ajax_nopriv_verify_title', 'slsc_verify_title');

function slsc_verify_title(){
	global $current_user, $wpdb;
	//if( check_ajax_referer( 'save_plan_security_nonce', 'security') ){
		
		$url = 'https://subject-line-analyzer-api.automizy.com/analyze-subject-line.php';
		
		// check if [] inside
		if( substr_count( $_POST['string'], ']' ) > 0 || substr_count( $_POST['string'], '[' ) > 0 ){
			echo json_encode( array( 'result' => 'error', 'html' => '<div class="empty_title"><div class="inner_text">Sorry, you can\'t use "[" or "]" in the subjectline</div> <img src="'.plugins_url( '/images/sad.svg', __FILE__ ).'" /></div>' ) );
							die();
		}
		
		
		// word array
		 
		$prefilter_title = str_replace( '</p>', "</p>|||", $_POST['string'] );
		$prefilter_title = str_replace( '</li>', "</li>|||", $prefilter_title );
		$prefilter_title = str_replace( "\n", "|||", $prefilter_title );
		
		
		$prefilter_title = str_replace( "||||||", "|||", $prefilter_title );
 	
		$prefilter_title = wp_kses( $prefilter_title, '<em><b><i>' );
 
		$all_lines = explode( '|||', $prefilter_title );
		$all_titles = array_filter( $all_lines );
		$all_titles = array_map( 'trim', $all_titles );
		$all_titles = array_map( 'stripslashes', $all_titles );
		//$all_titles = array_map( 'htmlspecialchars_decode', $all_titles );
		$all_titles = array_filter( $all_titles );
 
	 $titles_cache = get_option('title_cache');
	 
 
		$out_titles = array();
		if( count($all_titles) > 0 ){
			foreach( $all_titles as $single_title ){
		 	
		 
				
		 
				$rand_titles[] = array( 'title' => $single_title, 'points' => rand(1, 100) );
				
				$args = array(
					'body' => array(
						'sessionId' => md5( rand(1, 999999) ),
						'subjectLine' => urlencode( $single_title )
					)
				);
				$url = 'https://subject-line-analyzer-api.automizy.com/analyze-subject-line.php?sessionId='.md5( rand(1, 999999) ).'&subjectLine='.urlencode( $single_title );
				 
				$args = array(
					'timeout'     => 10, 
				);
				 
				$result = wp_Remote_get( $url , $args );
				
				 
				if( !is_wp_error( $result )   ){
					if( wp_remote_retrieve_response_code( $result ) == 200 ){
					 
						$result_json = json_decode( wp_remote_retrieve_body( $result ) );	
					 
						$points_calc = (int)( (float)$result_json->score*100 );
						
						// spec char issue
						
						 $edited_filtered_title = htmlspecialchars_decode( $single_title );
						 
					 
						$edited_filtered_title = preg_replace ('/&#x.+;/', '', $edited_filtered_title);	 
						$edited_filtered_title = str_replace("&nbsp;", '', $edited_filtered_title);
						$edited_filtered_title = str_replace(" ", '', $edited_filtered_title);
						
						$edited_filtered_title = preg_replace("/[^A-Za-z0-9 ]/", '', trim($edited_filtered_title) );
						
				 
						$out_titles[] = array( 'title' => $single_title, 'points' => $points_calc );
						$titles_cache[md5($edited_filtered_title)] = $points_calc;
						
						if( count($out_titles) == 0 ){
							echo json_encode( array( 'result' => 'error', 'html' => '<div class="empty_title"><div class="inner_text">API Error</div> <img src="'.plugins_url( '/images/sad.svg', __FILE__ ).'" /></div>' ) );
							die();
						}
					}
				}else{
					echo json_encode( array( 'result' => 'error', 'html' => '<div class="empty_title"><div class="inner_text">API Error</div> <img src="'.plugins_url( '/images/sad.svg', __FILE__ ).'" /></div>' ) );
					die();
				} 
				
				
				
			}
		}
		update_option('title_cache', $titles_cache);
		
		
		//$out_titles = $rand_titles;
		
		
		
		$out .= '
		<div class="container">
				<div class="row">
					<div class="col-6 p-3">
						<div class="left_scorer">Subject Line Scorer</div>
						<div class="scorer_result_cont">';
						if( count( $out_titles ) > 0 ){
							$cnt = 0;
							foreach( $out_titles as $single_title){
								
								// pick class
								if( $single_title['points'] < 40 ){
								
									$graph_color = '#FD7378';
								
									$style_class = ' scorer_red ';
									$item_message = 'This subject line would perform <b>poorly</b>';
								}
								if( $single_title['points'] >= 40 &&  $single_title['points'] <= 74 ){
									$style_class = ' scorer_yellow ';
									$item_message = 'This subject line would perform <b>fairly</b>';
									
									$graph_color = '#FFC959';
								}
								if( $single_title['points'] >  74   ){
									$style_class = ' scorer_green ';
									$item_message = 'This subject line would perform <b>remarkably</b>';
									
									$graph_color = '#7FC698';
								}
								
								if( $cnt == 0 ){
									$init_title = $single_title['title'];
								}
								
								$params_data = array( 'title' =>   $single_title['title']  , 'points' => $single_title['points'], 'color' => $graph_color );
								
								$out .= '
								<div class="single_title_line" data-title="'.htmlspecialchars( $single_title['title'] ).'"   data-params="'.htmlspecialchars( json_encode( $params_data ) ).'">
									<div class="single_title_text '.( $cnt == 0 ? ' active_title ' : '' ).'">'.$single_title['title'].'</div>
									<div class="single_title_score">
										<div class="scorer '.$style_class.'">'.$single_title['points'].'</div>
									</div>
								</div>';
								$cnt++;
							}
						}
					$out .= ' 
						</div>
					</div>
					<div class="col-6 p-3">
						<div class="right_top_msg">
							<div class="right_subject_title">'.$init_title.'</div>
							<div class="right_subject_subtitle">has scored</div>
						</div>
						<div class="right_top_msg">
							<div class="box">
								<div id="g2" class="gauge"></div>
							  </div>
							<div class="small_powered"><a href="https://automizy.com/" target="_blank">Powered by Automizy</a></div>
						</div>
					</div>
				</div>
				 
				
				
				
				<div class="row">
					<div class="col-6">
						<a href="https://automizy.com/tools/free-email-subject-line-tester/"  target="_blank">
						<div class="test_more_button">
							<img class="small_image" src="'.plugins_url( '/images/happy.svg', __FILE__ ).'" />
							Test More Subject Lines
						</div>
						</a>
					</div>
					<div class="col-6">
					</div>
				</div>
				
				<hr>
				<div class="row">
					<div class="col-4">
						<a href="" class="cancel_button upper_text">Cancel</a>
					</div>
					<div class="col-4">
						<div class="fake_del_button">
							<i class="fa fa-trash" aria-hidden="true"></i>
						</div>
					</div>
					<div class="col-4 text-right">
						<button type="button" class="btn btn-custom-green btn-sm extra_button_padding apply_title_button upper_text" >Insert Score</button>
					</div>
				</div>
			</div>
		';
		
		
		
		echo json_encode( array( 'result' => 'success', 'html' =>  $out ) );
		
	//}
	die();
}

 
?>