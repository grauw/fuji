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
					<td><?php	$n = Database::getArray('SELECT COUNT(*) ' . Database::$db_tprefix . 'FROM users'); echo $n[0][0]; ?></td>
				</tr>
				<tr>
					<td><?php echo L10n::$num_posts ?></td>
					<td><?php	$n = Database::getArray('SELECT COUNT(*) ' . Database::$db_tprefix . 'FROM entries'); echo $n[0][0]; ?></td>
				</tr>
				<tr>
					<td><?php echo L10n::$most_active_user ?></td>
					<td><?php	$most_active_user = Database::getArray('SELECT userref,count(*) FROM ' . Database::$db_tprefix . 'entries GROUP BY userref HAVING count(*) >= ALL(SELECT count(*) FROM entries GROUP BY userref)');
								$most_active_user_id = $most_active_user[0][0];
								$most_active_user_count = $most_active_user[0][1];
								$most_active_user_name = Database::getArray("SELECT nickname FROM " . Database::$db_tprefix . "users WHERE id = '$most_active_user_id'");
								$most_active_user_name = $most_active_user_name[0][0]; 
								echo "<a href=\"profile/$most_active_user_id\" target=\"_blank\">$most_active_user_name</a> ($most_active_user_count)"?>
					</td>
				</tr>
				<tr>
					<td><?php echo L10n::$most_active_thread ?></td>
					<td><?php	$most_active_thread = Database::getArray("SELECT ref,count(*) FROM " . Database::$db_tprefix . "entries GROUP BY ref HAVING count(*) >= ALL(SELECT count(*) FROM entries WHERE type = '4' GROUP BY ref)");
								$most_active_thread_id = $most_active_thread[0][0];
								$most_active_thread_count = $most_active_thread[0][1];
								$most_active_thread_title = Database::getArray("SELECT title FROM " . Database::$db_tprefix . "entries WHERE id = '$most_active_thread_id'");
								$most_active_thread_title = $most_active_thread_title[0][0];					
								echo "<a href=\"#item/$most_active_thread_id/\">$most_active_thread_title</a> ($most_active_thread_count)" ?>
					</td>
				</tr>																	
				<tr>
					<td><?php echo L10n::$most_used_language ?></td>
					<td><?php	$langs = Database::getArray('SELECT language,count(*) FROM ' . Database::$db_tprefix . 'users GROUP BY language');
								$default_lang = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'language'");
								$default_lang_name = $default_lang[0][0];
								$default_lang_count;
								foreach($langs as $lang)
								{
									if(!$lang[0])
										$default_lang_count = $lang[1];
								}
								$most_used_lang_name = '';
								$most_used_lang_count = -1;
								foreach($langs as $lang)
								{
									$lang_name = $lang[0];
									$lang_count = $lang[1];
									if($lang_name == $default_lang_name)
										$lang_count += $default_lang_count;
									if(!$lang_name)
										$lang_name = $default_lang_name;									 
									if($lang_count > $most_used_lang_count)
									{
										$most_used_lang_name = $lang_name;
										$most_used_lang_count = $lang_count;
									}
								}
								$most_used_lang_full_name = Database::getArray("SELECT name FROM " . Database::$db_tprefix . "languages WHERE id = '$most_used_lang_name'");								
								$most_used_lang_full_name = $most_used_lang_full_name[0][0];
								echo "$most_used_lang_full_name ($most_used_lang_count)"	?>
					</td>
				</tr>
				<tr>
					<td><?php echo L10n::$most_used_style ?></td>
					<td><?php	$styles = Database::getArray('SELECT style,count(*) FROM ' . Database::$db_tprefix . 'users GROUP BY style');
								$default_style = Database::getArray("SELECT value FROM defaults WHERE name = 'style'");
								$default_style_name = $default_style[0][0];
								$default_style_count;
								foreach($styles as $style)
								{
									if(!$style[0])
										$default_style_count = $style[1];
								}
								$most_used_style_name = '';
								$most_used_style_count = -1;
								foreach($styles as $style)
								{
									$style_name = $style[0];
									$style_count = $style[1];
									if($style_name == $default_style_name)
										$style_count += $default_style_count; 
									if(!$style_name)
										$style_name = $default_style_name;
									if($style_count > $most_used_style_count)
									{
										$most_used_style_name = $style_name;
										$most_used_style_count = $style_count;
									}
								}
								$most_used_style_full_name = Database::getArray("SELECT id FROM " . Database::$db_tprefix . "styles WHERE id = '$most_used_style_name'");								
								$most_used_style_full_name = $most_used_style_full_name[0][0];
								echo "$most_used_style_full_name ($most_used_style_count)"	?>
					</td>
				</tr>
				<tr>
					<td><?php echo L10n::$newest_user ?></td>
					<td><?php	$newest_member = Database::getArray('SELECT id, nickname ' . Database::$db_tprefix . 'FROM users GROUP BY id HAVING id >= ALL(SELECT id FROM users)');
								$newest_member_id = $newest_member[0][0];
								$newest_member_name = $newest_member[0][1];
								echo "<a href=\"profile/$newest_member_id\" target=\"_blank\">$newest_member_name</a>"?>
					</td>
				</tr>									
			</table>
		</div>
	</fuji:content>
</fuji:item>