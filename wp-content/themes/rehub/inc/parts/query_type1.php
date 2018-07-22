<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php global $post;?>
<?php
$type = (isset($type)) ? $type : '';
?>
<div class="<?php if($type == '2'):?>magazinenews <?php endif;?>news-community clearfix<?php echo rh_expired_or_not($post->ID, 'class');?>">
	<?php echo re_badge_create('ribbonleft'); ?>
	<div class="rh_grid_image_wrapper">		
	    <div class="newsimage rh_gr_img">
	        <figure>
	            <div class="favorrightside wishonimage"><?php echo RH_get_wishlist($post->ID);?></div>       
		        <a href="<?php the_permalink();?>">
			        <?php 
			            $showimg = new WPSM_image_resizer();
			            $showimg->use_thumb = true;
			            if($type == '2') {
			            	$height_figure_single = apply_filters( 're_news_figure_height', 220 );
			            }
			            else{
			            	$height_figure_single = apply_filters( 're_news_figure_height', 160 );
			            }
			            $showimg->height = $height_figure_single;
			            $showimg->width = $height_figure_single;
			            $showimg->crop = false;           
			            $showimg->show_resized_image();                                    
			        ?>
		        </a>
	        </figure>
	    </div>
	    <?php if(rehub_option('hotmeter_disable') !='1') :?>
		    <div class="newsdetail rh_gr_top_right mb5">
		    	<?php echo getHotLike(get_the_ID()); ?> 
		    </div>
	    <?php endif ;?>
	    <div class="newsdetail newstitleblock rh_gr_right_sec">
		    <?php echo rh_expired_or_not($post->ID, 'span');?><h2 class="font120 mt0 mb10 mobfont110 lineheight20 moblineheight15"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
		    <?php if(rehub_option('disable_btn_offer_loop')!='1')  : ?>  		          
			    <?php rehub_generate_offerbtn('showme=price&wrapperclass=pricefont110 rehub-main-color mobpricefont90 fontbold mb5 mr10 lineheight20 floatleft');?> 
		        <?php 
		            $offer_price_old = get_post_meta($post->ID, 'rehub_offer_product_price_old', true );
		            $offer_price_old = apply_filters('rehub_create_btn_price_old', $offer_price_old);
		            if(!empty($offer_price_old)){
		                $offer_price = get_post_meta($post->ID, 'rehub_offer_product_price', true );
		                $offer_price = apply_filters('rehub_create_btn_price', $offer_price);
		                if ( !empty($offer_price)) {
		                    $offer_pricesale = (float)rehub_price_clean($offer_price); 
		                    $offer_priceold = (float)rehub_price_clean($offer_price_old);
		                    if ($offer_priceold !='0' && is_numeric($offer_priceold) && $offer_priceold > $offer_pricesale) {
		                        $off_proc = 0 -(100 - ($offer_pricesale / $offer_priceold) * 100);
		                        $off_proc = round($off_proc);
		                        echo '<span class="rh-label-string mr10 mb5 lineheight20 floatleft">'.$off_proc.'%</span>';
		                    }
		                }
		            }

		        ?> 			    
				<span class="more-from-store-a floatleft ml0 mr10 mb5 lineheight20"><?php WPSM_Postfilters::re_show_brand_tax('list');?></span>			     
				<div class="clearfix"></div>
		    <?php endif; ?>	 		    
	    </div>	
	    <div class="newsdetail rh_gr_right_desc">
	    	<p class="font90 mobfont80 lineheight20 moblineheight15 mb15"><?php kama_excerpt('maxchar=160'); ?></p>
			<?php $content = $post->post_content; ?>
			<?php if( false !== strpos( $content, '[wpsm_update' ) ) : ?>
				<?php 
					$pattern = get_shortcode_regex();
					preg_match('/'.$pattern.'/s', $post->post_content, $matches);
					if (is_array($matches) && $matches[2] == 'wpsm_update') {
		   			$shortcode = $matches[0];
		   			echo do_shortcode($shortcode);
					}
				?>
			<?php endif;?>	    	
	    </div>	            
	    <div class="newsdetail newsbtn rh_gr_right_btn">
	    	<div class="rh-flex-center-align mobileblockdisplay">
		        <div class="meta post-meta">
		            <?php rh_post_header_meta( 'full', true, false, 'compactnoempty', false ); ?>                       
		        </div>	
		        <div class="rh-flex-right-align">    	
				    <?php if(rehub_option('disable_btn_offer_loop')!='1')  : ?>       
					    <?php rehub_generate_offerbtn('btn_more=yes&showme=button&wrapperclass=mobile_block_btnclock mb0');?>      
				    <?php endif; ?>
			    </div> 
			</div>
	    </div>
    </div> 
    <div class="newscom_head_ajax"></div>
    <div class="newscom_content_ajax"></div>
    <?php if(rehub_option('rehub_enable_expand') == 1):?><span class="showmefulln def_btn" data-postid="<?php echo $post->ID; ?>" data-enabled="0"><?php _e('Expand', 'rehubchild');?></span><?php endif;?>     
</div>