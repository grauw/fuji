<?php require_once('../scripts/database.php') ?>
<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<h2 class="windowTitle"><?php echo L10n::$statistics ?></h2>
		<div id="threadsTableDiv">
			<table id="threadsTable">
				<tr>
					<td><?php echo L10n::$num_users ?></td>
					<td><?php	$n = Database::getArray('SELECT COUNT(*) FROM users'); echo $n[0][0]; ?></td>
				</tr>
				<tr>
					<td><?php echo L10n::$num_posts ?></td>
					<td><?php	$n = Database::getArray('SELECT COUNT(*) FROM entries'); echo $n[0][0]; ?></td>
				</tr>
				<tr>
					<td><?php echo L10n::$most_active_user ?></td>
					<td><?php	$most_active_user = Database::getArray('SELECT userref,count(*) FROM entries GROUP BY userref HAVING count(*) >= ALL(SELECT count(*) FROM entries GROUP BY userref)');
								$most_active_user_id = $most_active_user[0][0];
								$most_active_user_count = $most_active_user[0][1];
								$most_active_user_name = Database::getArray("SELECT nickname FROM users WHERE id = '$most_active_user_id'");
								$most_active_user_name = $most_active_user_name[0][0]; 
								echo "<a href=\"profile/$most_active_user_id\" target=\"_blank\">$most_active_user_name</a> ($most_active_user_count)"?>
					</td>
				</tr>
				<tr>
					<td><?php echo L10n::$most_active_thread ?></td>
					<td><?php	$most_active_thread = Database::getArray("SELECT ref,count(*) FROM entries GROUP BY ref HAVING count(*) >= ALL(SELECT count(*) FROM entries WHERE type = '4' GROUP BY ref)");
								$most_active_thread_id = $most_active_thread[0][0];
								$most_active_thread_count = $most_active_thread[0][1];
								$most_active_thread_title = Database::getArray("SELECT title FROM entries WHERE id = '$most_active_thread_id'");
								$most_active_thread_title = $most_active_thread_title[0][0];					
								echo "<a href=\"#item/$most_active_thread_id/\">$most_active_thread_title</a> ($most_active_thread_count)" ?>
					</td>
				</tr>																	
				<tr>
					<td><?php echo L10n::$most_used_language ?></td>
					<td><?php	$most_used_lang = Database::getArray('SELECT language,count(*) FROM users GROUP BY language HAVING count(*) >= ALL(SELECT count(*) FROM users GROUP BY language)');
								$most_used_lang_id = $most_used_lang[0][0];
								$most_used_lang_count = $most_used_lang[0][1];
								if(!$most_used_lang_id)
								{
									$most_used_lang_id = Database::getArray("SELECT value FROM defaults WHERE name = 'language'");
									$most_used_lang_id = $most_used_lang_id[0][0]; 
								}
								$most_used_lang_name = Database::getArray("SELECT name FROM languages WHERE id = '$most_used_lang_id'");
								$most_used_lang_name = $most_used_lang_name[0][0];
								echo "$most_used_lang_name ($most_used_lang_count)"	?>
					</td>
				</tr>
				<tr>
					<td><?php echo L10n::$most_used_style ?></td>
					<td><?php	$most_used_style = Database::getArray('SELECT style,count(*) FROM users GROUP BY style HAVING count(*) >= ALL(SELECT count(*) FROM users GROUP BY style)');
								$most_used_style_name = $most_used_style[0][0];
								$most_used_style_count = $most_used_style[0][1];
								if(!$most_used_style_name)
									$most_used_style_name = Database::getArray("SELECT value FROM defaults WHERE name = 'style'"); 
								else
									$most_used_style_name = Database::getArray("SELECT name FROM styles WHERE id = '$most_used_style_name'");
								$most_used_style_name = $most_used_style_name[0][0];
								echo "$most_used_style_name ($most_used_style_count)" ?>
					</td>
				</tr>
				<tr>
					<td><?php echo L10n::$newest_user ?></td>
					<td><?php	$newest_member = Database::getArray('SELECT id, nickname FROM users GROUP BY id HAVING id >= ALL(SELECT id FROM users)');
								$newest_member_id = $newest_member[0][0];
								$newest_member_name = $newest_member[0][1];
								echo "<a href=\"profile/$newest_member_id\" target=\"_blank\">$newest_member_name</a>"?>
					</td>
				</tr>									
			</table>
		</div>
	</fuji:content>
</fuji:item>