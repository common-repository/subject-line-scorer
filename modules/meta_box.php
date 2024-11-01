<?php 
if( !class_exists( 'vooMetaBox1' ) ){
	class vooMetaBox1{
		
		private $metabox_parameters = null;
		private $fields_parameters = null;
		private $data_html = null;
		
		function __construct( $metabox_parameters , $fields_parameters){
			$this->metabox_parameters = $metabox_parameters;
			$this->fields_parameters = $fields_parameters;
 
			add_action( 'add_meta_boxes', array( $this, 'add_custom_box' ) );
			add_action( 'save_post', array( $this, 'save_postdata' ) );
		}
		
		function add_custom_box(){
			add_meta_box( 
				'custom_meta_editor_'.rand( 100, 999 ),
				$this->metabox_parameters['title'],
				array( $this, 'custom_meta_editor' ),
				$this->metabox_parameters['post_type'] , 
				$this->metabox_parameters['position'], 
				$this->metabox_parameters['place']
			);
		}
		function custom_meta_editor(){
			global $post;
			
			$out = '

			<div class="tw-bs4">
				<div class="form-horizontal ">';
			
			foreach( $this->fields_parameters as $single_field){
			 
				switch( $single_field['type'] ){
					
					case "shortcode":
					$out .= '<div class="form-group">  
						<label class="control-label" for="input01">'.$single_field['title'].'</label>  
						 
						  <input type="text" class="form-control input-xlarge" name="'.$single_field['name'].'" id="'.$single_field['name'].'" 
						  value="['.$single_field['name'].' id=\''.$post->ID.'\']"
						  
						  >  
						  
					  </div> ';	
					break;
					
					
					case "textarea":
					$out .= '<div class="form-group">  
						<label class="control-label" for="input01">'.$single_field['title'].'</label>  
						 
						  <textarea type="text" class="form-control input-xlarge" style="'.$single_field['style'].'" name="'.$single_field['name'].'" id="'.$single_field['name'].'" >'.htmlentities( get_post_meta( $post->ID, $single_field['name'], true ) ).'</textarea>  
						  
					  </div> ';	
					break;
					case "text":
					$out .= '<div class="form-group">  
						<label class="control-label" for="input01">'.$single_field['title'].'</label>  
						 
						  <input type="text" class="form-control input-xlarge" name="'.$single_field['name'].'" id="'.$single_field['name'].'" value="'.get_post_meta( $post->ID, $single_field['name'], true ).'">  
						  
					  </div> ';	
					break;
					case "checkbox":
					$out .= '<div class="form-group">  
						<label class="control-label" for="input01">'.$single_field['title'].'</label>  
					  
						  <input type="checkbox" class="form-control "  name="'.$single_field['name'].'" id="'.$single_field['name'].'" value="on" '.( get_post_meta( $post->ID, $single_field['name'], true ) == 'on' ? ' checked ' : '' ).' >  
						  
					  </div> ';	
					break;
					case "select":
					$out .= '<div class="form-group">  
						<label class="control-label" for="input01">'.$single_field['title'].'</label>';

							$out .= '<select class="form-control " name="'.$single_field['name'].'">';
							foreach( $single_field['value'] as $key => $value ){
								$out .= '<option '.( get_post_meta( $post->ID, $single_field['name'], true ) == $key ? ' selected ' : '' ).' value="'.$key.'">'.$value;
							}
							$out .= '</select>';
						 
					$out .= '
						
					  </div> ';	
					break;
					
					case "wide_editor":
					$out .= '<div class="form-group">  
						<label class="control-label" for="input01">'.$single_field['title'].'</label>
						<div class="form-control">
						';  
						 
						ob_start();
						wp_editor( get_post_meta( $post->ID, $single_field['name'], true ), $single_field['name'] );
						$editor_contents = ob_get_clean();	
						
						$out .= $editor_contents;  
					$out .= '
						</div>
					  </div> ';	 
					 
					break;
					case "custom_text":
					$url = str_replace( '%post_id%', $post->ID,  $single_field['text']);
					$out .= '<div class="form-group">  
						<label class="control-label" for="input01">&nbsp;</label>
						<div class="form-control">
						'.$url.'
						</div>
					  </div> ';	 
					 
					break;
	 
					case "file":
						$out .= '
						<div class="form-group">  
							<label class="control-label" for="'.$single_field['id'].'">'.$single_field['title'].'</label>  
				 
							<input type="file" class="form-control-file '.$single_field['class'].'" name="'.$single_field['name'].''.( $single_field['multi'] ? '[]' : '' ).'" id="'.$single_field['id'].'" '.( $single_field['multi'] ? ' multiple ' : '' ).' >
							  
							  <p class="help-block">'.$single_field['sub_text'].'</p> 
						 
						  </div> 
						';
					break;
					case "mediafile_single":
					
					// get attachment src
					
					$attach_url = wp_get_attachment_image_src( get_post_meta( $post->ID, $single_field['name'], true ) );
					
					$out .= '<div class="form-group media_upload_block">  
						<label class="control-label" for="input01">'.$single_field['title'].'</label>  
						 
						  <input type="hidden" class="form-control input-xlarge mediafile_single item_id" name="'.$single_field['name'].'" id="'.$single_field['name'].'" value="'.get_post_meta( $post->ID, $single_field['name'], true ).'"> 
						  
					 
						  <input type="button" class="btn btn-success upload_image" data-single="1" value="'.$single_field['upload_text'].'" />
						  <div class="image_preview">'.( $attach_url[0] ? '<img src="'.$attach_url[0].'" />' : '' ).'</div>
					  </div> ';	
					break;
					
					case "mediafile_multi":
					
					// get attachment src
					
					$attach_url = wp_get_attachment_image_src( get_post_meta( $post->ID, $single_field['name'], true ) );
					
					$out .= '<div class="form-group media_upload_block">  
						<label class="control-label" for="input01">'.$single_field['title'].'</label>  
						 
						  <input type="hidden" class="form-control input-xlarge mediafile_single item_id" name="'.$single_field['name'].'" id="'.$single_field['name'].'" value=""> 
						  
					 
						  <input type="button" class="btn btn-success upload_image" data-single="0" value="'.$single_field['upload_text'].'" />
						 
					  </div> ';	
					break;
					
					
					
					
					case "hidden":
					$out .= '
						  <input type="hidden"  name="'.$single_field['name'].'" id="'.$single_field['name'].'" value="'.get_post_meta( $post->ID, $single_field['name'], true ).'">';	
					break;
					case "checkbox":
					$out .= '<div class="form-group">  
						<label class="control-label" for="input01">'.$single_field['title'].'</label>  
					  
						  <input type="checkbox" class="   "  name="'.$single_field['name'].'" id="'.$single_field['name'].'" value="on" '.( get_post_meta( $post->ID, $single_field['name'], true ) == 'on' ? ' checked ' : '' ).' >  
						  
					  </div> ';	
					break;
				}
			}		
			
					
					
			$out .= '
					</div>	
				</div>
				';	
			$this->data_html = $out;
			 
			$this->echo_data();
		}
		
		function echo_data(){
			echo $this->data_html;
		}
		
		function save_postdata( $post_id ) {
			global $current_user; 
			 if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				  return;

			  if ( 'page' == $_POST['post_type'] ) 
			  {
				if ( !current_user_can( 'edit_page', $post_id ) )
					return;
			  }
			  else
			  {
				if ( !current_user_can( 'edit_post', $post_id ) )
					return;
			  }
			  /// User editotions

				if( get_post_type($post_id) == $this->metabox_parameters['post_type'] ){
					foreach( $this->fields_parameters as $single_parameter ){
						update_post_meta( $post_id, $single_parameter['name'], sanitize_text_field( $_POST[$single_parameter['name']] ) );
					}
					
				}
				
			}
	}
}

 
 
add_Action('admin_init',  function (){
	 
	 
	 
	 $all_taxonomies = get_taxonomies();
	 
	 $out_categories = array();
	 
	 
	 if( count($all_taxonomies) > 0 ){
		foreach( $all_taxonomies as $key => $value ) {
			$all_cats =  get_terms( array( 'taxonomy' => $key, 'hide_empty' => 0 ) ) ;
			if( count($all_cats) > 0 ){
				$out_categories[0] = __('Select Term'); 
				foreach( $all_cats as $single_cat ){
					$out_categories[$single_cat->term_id] = $single_cat->name.' ('.$value.')';
				}
			}
		}
		 
	 }
	 
	 
	 
	 $meta_box = array(
		'title' => 'Some Title',
		'post_type' => 'cross_seo',
		'position' => 'advanced',
		'place' => 'high'
	);
	$fields_parameters = array(
		array(
			'type' => 'select',
			'title' => 'Taxonomy 1',
			'name' => 'taxonomy_picking_1',
			'value' => $out_categories
		),
		array(
			'type' => 'select',
			'title' => 'Taxonomy 2',
			'name' => 'taxonomy_picking_2',
			'value' => $out_categories
		),
		array(
			'type' => 'text',
			'title' => 'Page Title',
			'name' => 'page_title',
		),
		array(
			'type' => 'textarea',
			'title' => 'Page Description',
			'name' => 'page_description',
		),
		array(
			'type' => 'text',
			'title' => 'Archive Title',
			'name' => 'archive_title',
		),
		array(
			'type' => 'wide_editor',
			'title' => 'Category Description',
			'name' => 'archive_description',
		),
		array(
			'type' => 'wide_editor',
			'title' => 'Extra Text',
			'name' => 'extra_text',
		),
	 
	);		
	$new_metabox = new vooMetaBox1( $meta_box, $fields_parameters); 
	 
 } );
 

?>