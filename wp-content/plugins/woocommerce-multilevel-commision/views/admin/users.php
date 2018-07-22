<?php
//$period = esc_attr( get_the_author_meta( 'credit_points_expiry_period', $user->ID ) );
//$periodData = array('day' => 'Days', 'month' => 'Months', 'year' => 'Years');
			
?>
<h3><?php echo __('Referral Program Statistics', 'wmc'); ?></h3>

<table class="form-table">

	<tr>
		<th><label for="join_date"><?php echo __('Join Date', 'wmc')?></label></th>
		<td>
			<input type="text" name="join_date" id="join_date" disabled value="<?php echo $user['join_date'] ?>" class="regular-text" /><br />
			<span class="description"><?php echo __('Joining date of referral user', 'wmc');?>.</span>
		</td>
	</tr>
	<tr>
		<th><label for="referal_benefits"><?php echo __('Referral Discount', 'wmc')?></label></th>
		<td>
			<input type="checkbox" name="referal_benefits" disabled id="referal_benefits" <?php echo esc_attr($user['referal_benefits'] ) ? 'checked' : '' ; ?> /><br />
			<span class="description"><?php echo __('Status of referral user for discount that taken or not?', 'wmc');?>.</span>
		</td>
	</tr>
	<tr>
		<th><label for="referal_code"><?php echo __('Referral code', 'wmc')?></label></th>
		<td>
			<input type="text" name="referal_code" id="referal_code" disabled value="<?php echo esc_attr( $user['referral_code'] ); ?>" class="regular-text" /><br />
			<span class="description"><?php echo __('Auto generated referral code for referral users', 'wmc');?>.</span>
		</td>
	</tr>
	<?php /*
	<tr>
		<th><label for="credit_points_expiry"><?php echo __('Credit points expiry', 'wmc')?></label></th>
		<td>
			<input type="text" name="credit_points_expiry_number" id="credit_points_expiry_number" value="<?php echo esc_attr( get_the_author_meta( 'credit_points_expiry_number', $user->ID ) ); ?>" class="regular-text" />
			<select name="credit_points_expiry_period">
				<?php
				echo '<option value="">'. __('Period', 'wmc') .'</option>';
				foreach($periodData as $key => $value):
					echo "<option ".($period == $key ? 'selected' : '')." value='$key'>". __($value, 'wmc')."</option>";
				endforeach;
				?>
			</select>
			<br />
			<span class="description"><?php echo __('Expire periods of earn credits.', 'wmc');?>.</span>
		</td>
	</tr>
<?php */?>
</table>