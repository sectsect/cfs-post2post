<div class="wrap">
	<h1>CFS Post 2 Post</h1>
	<section>
		<form method="post" action="options.php">
			<hr />
			<h3>Overwrite Settings</h3>
	        <?php
	            settings_fields('cfs_p2p-settings-group');
	            do_settings_sections('cfs_p2p-settings-group');
	        ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="cfs_p2p_overwrite_type">
								Overwrite <span style="color: #888; font-size: 10px; font-weight: normal;">(Optional)</span>
							</label>
						</th>
						<td>
							<?php
								$overwrite_types = array(
									"none"  => "Do not overwrite",
									"first" => "First Element",
									"last"  => "Last Element"
								);
							?>
							<select id="cfs_p2p_overwrite_type" name="cfs_p2p_overwrite_type" style="width: 150px;">
								<?php foreach ($overwrite_types as $key => $overwrite_type): ?>
									<?php $selected = (get_option('cfs_p2p_overwrite_type') == $key) ? "selected" : ""; ?>
									<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $overwrite_type; ?></option>
								<?php endforeach; ?>
                            </select>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="notes">
				If the field in a related post, whether it is a relationship field that has a maximum number of related posts, if the field in the related post already has the maximum number of values allowed then, by default, a new value will not be added. You can override this default by specifying overwrite settings.<br>
				The value selected in the above field is deleted and the new value is added to the end.
			</p>
			<hr />
			<div class="link-doc">
				<a href="https://github.com/sectsect/cfs-post2post" target="_blank">
					<dl>
						<dt>
							<img src="https://github-sect.s3-ap-northeast-1.amazonaws.com/github.svg" width="22" height="auto">
						</dt>
					    <dd>
					        Document on Github
					    </dd>
					</dl>
				</a>
			</div>
			<?php submit_button(); ?>
		</form>
	</section>
</div>
