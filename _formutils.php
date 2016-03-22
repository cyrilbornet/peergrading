<?php

	include_once('_phptoolbox.php');

	$GLOBALS['ctrl_id'] = 0;	// Unique identifier for form controles, mainly used for JS

	function beginForm($method='get', $action='', $multipart=false) {
		print('<form id="'.('form'.$GLOBALS['ctrl_id']).'" method="'.$method.'" action="'.($action==''?$_SERVER['PHP_SELF']:$action).'" '.($multipart?' enctype="multipart/form-data"':'').'>');
	}
	function endForm() {
		print('</form>');
	}

	function printRadioInput($title, $field, $default, $options, $comment='') {
		$GLOBALS['ctrl_id']++;
		if ($title!='') { print('<label for="'.$GLOBALS['ctrl_id'].'">'.$title.' </label>'); }
		$i = 0;
		foreach ($options as $option => $label) {
			print('<input type="radio" id="'.$GLOBALS['ctrl_id'].'_'.$i.'" name="'.$field.'" value="'.htmlspecialchars($option).'" '.($option==$default?' checked="checked"':'').' />');
			print('<p style="display:inline; margin-right:10px;">'.$label.'</p>');
			$i++;
		}
		print('<span class="form_comment">'.$comment.'</span>');
		echo '<br/>';
		return $GLOBALS['ctrl_id'];
	}

	function printTextInput($title, $field, $default, $size, $maxchars=0, $comment='', $script='') {
		$GLOBALS['ctrl_id']++;
		if ($title!='') { print('<label for="'.$GLOBALS['ctrl_id'].'">'.$title.' </label>'); }
		print('<input type="text" id="t'.$GLOBALS['ctrl_id'].'" name="'.$field.'" value="'.htmlspecialchars($default).'" size="'.$size.'" '.($maxchars>0?'maxlength="'.$maxchars.'"':'').' '.str_replace('$ID', $GLOBALS['ctrl_id'], $script).'/>');
		if ($comment != '') { print('<span class="form_comment">'.$comment.'</span>'); }
		echo '<br/>';
		return 't'.$GLOBALS['ctrl_id'];
	}

	function printPasswordInput($title, $field, $default, $size, $maxchars=0, $comment='') {
		$GLOBALS['ctrl_id']++;
		if ($title!='') { print('<label for="'.$GLOBALS['ctrl_id'].'">'.$title.' </label>'); }
		print('<input type="password" id="p'.$GLOBALS['ctrl_id'].'" name="'.$field.'" value="'.htmlspecialchars($default).'" size="'.$size.'" '.($maxchars>0?'maxlength="'.$maxchars.'"':'').'/>');
		if ($comment != '') { print('<span class="form_comment">'.$comment.'</span>'); }
		return 'p'.$GLOBALS['ctrl_id'];
	}

	function printStaticInput($title, $content, $size) {
		$GLOBALS['ctrl_id']++;
		if ($title!='') { print('<label>'.$title.' </label>'); }
		print('<input type="text" id="s'.$GLOBALS['ctrl_id'].'" value="'.htmlspecialchars($content).'" size="'.$size.'" disabled="disabled" />');
		echo '<br/>';
		return 's'.$GLOBALS['ctrl_id'];
	}

	function printTextArea($title, $field, $default, $cols, $rows) {
		$GLOBALS['ctrl_id']++;
		if ($title!='') { print('<label for="'.$GLOBALS['ctrl_id'].'">'.$title.' </label>'); }
		print('<textarea id="'.$GLOBALS['ctrl_id'].'" name="'.$field.'" cols="'.$cols.'" rows="'.$rows.'">'.stripslashes($default).'</textarea>');
		echo '<br/>';
		return $GLOBALS['ctrl_id'];
	}

	function printSelectInput($title, $field, $default, $options, $autosubmit=false) {
		$GLOBALS['ctrl_id']++;
		if ($title!='') { print('<label for="'.$GLOBALS['ctrl_id'].'">'.$title.'</label> '); }
		print('<select id="'.$GLOBALS['ctrl_id'].'" name="'.$field.'"'.($autosubmit?' onchange="this.form.submit();"':'').'>');
		foreach ($options as $option => $label) {
			print('<option value="'.htmlspecialchars($option).'" '.($option==$default?' selected="selected"':'').'>'.$label.'</option>');
		}
		print('</select>');
		echo '<br/>';
		return $GLOBALS['ctrl_id'];
	}

	function printCheckInput($title, $field, $defaults, $options, $column=false) {
		$GLOBALS['ctrl_id']++;
		if ($title!='') { print('<label for="'.$GLOBALS['ctrl_id'].'_0">'.$title.' </label>'); }
		$i=0;
		foreach ($options as $option => $label) {
			$check = '';
			foreach ($defaults as $default) {
				if ($option==$default) $check = ' checked="checked"';
			}
			print('<div style="'.($column?'margin-left:135px;':'display:inline;white-space:nowrap;').'"><input type="checkbox" id="'.$GLOBALS['ctrl_id'].'_'.$i.'" name="'.$field.'[]" value="'.htmlspecialchars($option).'" '.$check.' />');
			print('<p style="display:inline; margin-right:10px;">'.$label.'</p></div>');
			$i++;
		}
		echo '<br/>';
		return $GLOBALS['ctrl_id'];
	}

	function printRangeInput($title, $field, $range_min=0, $default_min=20, $default_max=80, $range_max=100){
		print('<label>'.$title.'</label>');
		$default_min = (int)max($range_min, $default_min);
		$default_max = (int)($default_max>$default_min)?min($range_max, $default_max):$range_max;
		print('<div class="slider">');
			print('<input type="hidden" name="'.$field.'_min" value="'.(0).'" id="imin_'.$field.'" />'); /*$default_min*/
			print('<input type="hidden" name="'.$field.'_max" value="'.$default_max.'" id="imax_'.$field.'" />');
			print('<div id="range_'.$field.'"></div>');
		print('</div><br/>');
		printJS('
			$("#range_'.$field.'").slider({
				range:true, values:['.$default_min.', '.$default_max.'], min:'.(int)$range_min.', max:'.(int)$range_max.',
				slide: function( e, ui ) {
					$("#range_'.$field.' a:first").attr("data-val", ui.values[0]);
					$("#imin_'.$field.'").val(ui.values[0]);
					$("#range_'.$field.' a:last").attr("data-val", ui.values[1]);
					$("#imax_'.$field.'").val(ui.values[1]);
				},
				change: function( e, ui) {
					$(this).parents("form").submit();
				}
			});
			$("#range_'.$field.' a:first").attr("data-val", '.$default_min.'); $("#range_'.$field.' a:last").attr("data-val", '.$default_max.');');
	}

	function printUploadInput($title, $field, $default='', $allowedTypes=array(), $path='./', $autoRename=true, $comment='') {
		$id = ++$GLOBALS['ctrl_id'];
		if ($title!='') { print('<label for="i'.$GLOBALS['ctrl_id'].'">'.$title.' </label>'); }
		if ($default != '') {
			if (!isset($GLOBALS['js_ul'])) {
				$GLOBALS['js_ul'] = '
				function showUploadSelect(id, field) {
					document.getElementById("del"+id).value=field;
					document.getElementById("i"+id).style.display="none";
					document.getElementById("sel"+id).style.display="inline";
				}';
				printJS($GLOBALS['js_ul']);
			}
			print('<div id="i'.$GLOBALS['ctrl_id'].'" class="fu">');
				print('<div class="field">');
				if ($path!='') {
					if (!strstr($default, '.jpg')&&!strstr($default, '.png')&&!strstr($default, '.gif')) { print('<a href="'.$path.'/'.$default.'">'.$default.'</a>'); }
					else { print('<a href="'.$path.'/'.$default.'" class="highslide" onclick="return hs.expand(this);">'.$default.'</a>'); }
				}
				else { print($default); }
				print('</div>');
				print('<input type="hidden" id="del'.$GLOBALS['ctrl_id'].'" name="deleteFile[]" value="" />');
				print('<input type="button" value="Replace" onclick="showUploadSelect(\''.$GLOBALS['ctrl_id'].'\',\''.$field.'\');" style="float:right;" />');
			print('</div>');
			print('<span id="sel'.$GLOBALS['ctrl_id'].'" style="float:left;display:none;">');
		}
		else {
			print('<span>');
		}
		print('<div class="fu" id="'.$id.'">');
			// Parameters _____________________________________________
			print('<span class="hidden" id="allowedTypes'.$id.'">'.json_encode($allowedTypes).'</span>');
			print('<input type="hidden" id="fNamePolicy'.$GLOBALS['ctrl_id'].'" value="'.($autoRename?'auto':'file').'" />');
			print('<input id="fFileName'.$id.'" type="hidden" name="'.$field.'" value="'.$default.'" />');
			print('<input id="fFileType'.$id.'" type="hidden" name="'.$field.'_T" value="" />');
			// File select + infos ____________________________________
			print('<span id="fc'.$id.'"><input type="file" name="fileToUpload" id="f'.$id.'" onchange="fileSelected(\''.$id.'\');"/></span>');	# multiple="multiple"
			print('<div id="info'.$id.'" class="fu_fileInfo"></div>');
			// Upload monitor _________________________________________
			print('<div id="t'.$id.'" class="fu_progress">');
				print('<img id="icon'.$id.'" src="" width="16" height="16" alt="..." />');
				print('<div id="progressLabel'.$id.'" class="progressValue">&nbsp;</div>');
				print('<div class="progressBar"><div id="progressBar'.$id.'" class="progressLevel"></div></div>');
			print('</div>');
		print('</div>');
		print('</span>');
		return 'i'.$GLOBALS['ctrl_id'];
	}

	function printFileInput($title, $field, $default='', $path='./', $comment='') {
		$GLOBALS['ctrl_id']++;
		if ($title!='') { print('<label for="'.$GLOBALS['ctrl_id'].'">'.$title.' </label>'); }
		print('<span class="fileInput">');
		if ($default != '') {
			if (!isset($GLOBALS['js_file'])) {
				$GLOBALS['js_file'] = '
				function showFileSelect(id, field) {
					document.getElementById("del"+id).value=field;
					document.getElementById(id).style.display="none";
					document.getElementById("sel"+id).style.display="block";
				}';
				printJS($GLOBALS['js_file']);
			}
			print('<div id="'.$GLOBALS['ctrl_id'].'" class="file">');
				print('<p>');
				if ($path!='') {
					if (!strstr($default, '.jpg')&&!strstr($default, '.png')&&!strstr($default, '.gif')) { print('<a href="'.$path.'/'.$default.'">'.$default.'</a>'); }
					else { print('<a href="'.$path.'/'.$default.'" class="highslide" onclick="return hs.expand(this);">'.$default.'</a>'); }
				}
				else { print($default); }
				print('</p>');
				print('<input type="hidden" id="del'.$GLOBALS['ctrl_id'].'" name="deleteFile[]" value="" />');
				print('<input type="button" value="Replace" onclick="showFileSelect(\''.$GLOBALS['ctrl_id'].'\',\''.$field.'\');" style="float:right;" />');
			print('</div>');
			print('<div id="sel'.$GLOBALS['ctrl_id'].'" class="new hidden"><input type="file" name="'.$field.'" /></div>');
		}
		else {
			print('<div class="new"><input type="file" id="'.$GLOBALS['ctrl_id'].'" name="'.$field.'" /></div><span class="form_comment">'.$comment.'</span><br/>');
		}
		print('</span><br/>');
		return $GLOBALS['ctrl_id'];
	}

	function printHiddenInput($field, $value) {
		$GLOBALS['ctrl_id']++;
		print('<input type="hidden" id="'.$GLOBALS['ctrl_id'].'" name="'.$field.'" value="'.htmlspecialchars($value).'" />');
		return $GLOBALS['ctrl_id'];
	}


	function printSubmitInput($field, $title, $alignLabel=false) {
		if ($alignLabel) { print('<label>&nbsp;</label>'); }
		print('<input type="submit" name="'.$field.'" value="'.htmlspecialchars($title).'" />');
	}


	function printDeleteInput($field, $title, $id, $message='Do you really want to delete this item?') {
		if (!isset($GLOBALS['js_delete'])) {
			$GLOBALS['js_delete'] = '
			function confirmDelete(id, field, message) {
				if (confirm(message)) { window.top.location.href = "'.$_SERVER['PHP_SELF'].'?"+field+"="+id; }
			}';
			printJS($GLOBALS['js_delete']);
		}
		if ($title=='') {
			print('<a href="#" onclick="confirmDelete(\''.$id.'\', \''.$field.'\', \''.addslashes($message).'\');"><img class="btn" src="i/delete.png" alt="[DELETE]" width="16" height="16" /></a>');
		}
		else {
			print('<input type="button" name="'.$field.'" value="'.htmlspecialchars($title).'" onclick="confirmDelete(\''.$id.'\', \''.$field.'\', \''.addslashes($message).'\');" />');
		}
	}

	function printLangInput($title = 'Languages:', $list = false) {
		$langs = array('en' => 'English', 'de' => 'Deutsch', 'fr' => 'French');
		if ($list) print('<ul style="list-style-type:none;">');
		print('<div style="style:border:1px solid #666666;">');
		if ($list) {
			$lg = array_keys($langs);
			foreach ($lg as $lang) {
				print('<li>');
				printCheckInput($title, 'avail_lang', array($lang), array($lang => $langs[$lang]));
				print('</li>');
			}
		}
		else {
			printCheckInput($title, 'avail_lang', array_keys($langs), $langs);
		}
		print('</div>');
		if ($list) print('</ul>');
	}

	function printLinkButton($link, $title, $image='', $inNewWindow=false) {
		if ($inNewWindow) {
			$action = 'open(\''.$link.'\',\'new\',\'width=1040,height=1000,toolbar=yes,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes\')';
		}
		else {
			$action = 'window.top.location.href=\''.$link.'\'';
		}
		if ($image!='') {
			print('<a href="#" onclick="'.$action.'" title="'.$title.'"><img class="btn" src="i/'.$image.'" alt="'.$title.'" width="16" height="16" /></a>');
		}
		else {
			print('<input type="button" value="'.htmlspecialchars($title).'" onclick="'.$action.'" />');
		}
	}


	// ===== Elinchrom specific inputs =====

	function printProductSelect($title, $field, $default, $multiple=true, $showHidden=true, $selectionDelegateFunction='') {
		if ($title!='') { print('<label for="selcat_'.$field.'">'.$title.' </label>'); }
		if ($default > -1) {
			$content_default = db_x('SELECT cat_id FROM products WHERE unit_id="'.$default.'";');
			$row_default = db_fetch($content_default);
			$default_cat = $row_default['cat_id'];
		}
		else {
			$default_cat = -1;
		}
		$js_cat = 'function '.$field.'_chooseCat() {
			var id = document.getElementById(\'selcat_'.$field.'\').value;
			var prods = document.getElementById(\'sel_'.$field.'\');
			var n;
			while (prods.length > 0) { prods.remove(0); }
			'.$selectionDelegateFunction.'(-1);
		';
		$options_cat = "";
		$options_prod = "";
		$content_cats = db_x('SELECT id, name FROM product_cats '.($showHidden?'':'WHERE status="visible"').' ORDER BY name ASC;');
		while ($cat = db_fetch($content_cats)) {
			$js_cat.= 'if (id=='.$cat['id'].') { ';
			$content_prods = db_x('SELECT unit_id, unit_name FROM products WHERE cat_id="'.$cat['id'].'" '.($showHidden?'':'AND status="visible"').' ORDER BY unit_name ASC;');
			while ($prod = db_fetch($content_prods)) {
				$js_cat.= 'n=document.createElement(\'option\'); n.text=\''.$prod['unit_name'].'\'; n.value=\''.$prod['unit_id'].'\'; ';
				$js_cat.= 'try{prods.add(n,null);}catch(ex){prods.add(n);} ';  // First part standard, second for IE
				if ($cat['id']==$default_cat) { $options_prod.='<option value="'.$prod['unit_id'].'"'.($prod['unit_id']==$default?' selected="selected"':'').'>'.$prod['unit_name'].'</option>'; }
			}
			$options_cat.='<option value="'.$cat['id'].'"'.($cat['id']==$default_cat?' selected="selected"':'').'>'.$cat['name'].'</option>';
			$js_cat.= '} ';
		}
		$js_cat.='}';
		printJS($js_cat, true);
		print('<nobr><select id="selcat_'.$field.'"'.($multiple?' multiple="multiple" size="10"':'').' onchange="'.$field.'_chooseCat();">');
			if (!$multiple && $options_prod=='') { print('<option>Choose...</option>'); }
			print($options_cat);
		print('</select>&nbsp;&rarr;&nbsp;');
		print('<select id="sel_'.$field.'"'.($multiple?' multiple="multiple" size="10"':'').' name="'.$field.($multiple?'[]':'').'" onchange="'.$selectionDelegateFunction.'(this.value);">');
			print($options_prod);
		print('</select></nobr>');
	}

	function printReferenceSelect($title, $field, $default_ref, $multiple=true) {
		if ($title!='') { print('<label for="selcat_'.$field.'">'.$title.' </label>'); }
		if ($default_ref > -1) {
			$content_default = db_x('SELECT product_id FROM product_references WHERE id="'.$default_ref.'";');
			$row_default = db_fetch($content_default);
			$default_prod = $row_default['product_id'];
			$content_default = db_x('SELECT cat_id FROM products WHERE id="'.$default_prod.'";');
			$row_default = db_fetch($content_default);
			$default_cat = $row_default['cat_id'];
		}
		else {
			$default_prod = -1;
			$default_cat = -1;
		}
		$js_cat = 'function '.$field.'_chooseCat() {
			var sel_ids = {};
			var box = document.getElementById(\'selcat_'.$field.'\');
			for (var i=0; i<box.length; i++) {
				if (box.options[i].selected) { sel_ids[box.options[i].value] = 0; }		// Needed to get the "for ... in" to work
			}
			var prods = document.getElementById(\'sel_'.$field.'\');
			var refs = document.getElementById(\'selref_'.$field.'\');
			var n;
			while (prods.length > 0) { prods.remove(0); }
			while (refs.length > 0) { refs.remove(0); }
		';
		$js_prod = 'function '.$field.'_chooseProd() {
			var sel_ids = {};
			var box = document.getElementById(\'sel_'.$field.'\');
			for (var i=0; i<box.length; i++) {
				if (box.options[i].selected) { sel_ids[box.options[i].value] = 0; }
			}
			var refs = document.getElementById(\'selref_'.$field.'\');
			var n;
			while (refs.length > 0) { refs.remove(0); }
		';
		$options_cat = "";
		$options_prod = "";
		$options_refs = "";
		$content_cats = db_x('SELECT id, name FROM product_cats ORDER BY name ASC;');
		while ($cat = db_fetch($content_cats)) {
			$js_cat.= 'if ('.$cat['id'].' in sel_ids) { ';
			$content_prods = db_x('SELECT unit_id, unit_name FROM products WHERE cat_id="'.$cat['id'].'" ORDER BY unit_name ASC;');
			while ($prod = db_fetch($content_prods)) {
				$js_cat.= 'n=document.createElement(\'option\'); n.text=\''.$prod['unit_name'].'\'; n.value=\''.$prod['unit_id'].'\'; ';
				$js_cat.= 'try{prods.add(n, null);}catch(ex){prods.add(n);} ';  // First part standard, second for IE
				if ($cat['id']==$default_cat) { $options_prod.='<option value="'.$prod['unit_id'].'"'.($prod['unit_id']==$default_prod?' selected="selected"':'').'>'.$prod['unit_name'].'</option>'; }
				//--------------------------------------------
				$js_prod.= 'if ('.$prod['unit_id'].' in sel_ids) { ';
				$content_refs = db_x('SELECT id, CONCAT(elinca_code," - ",name_'.$_SESSION['lang'].') AS line FROM product_references WHERE product_id="'.$prod['unit_id'].'" ORDER BY elinca_code ASC;');
				while ($ref = db_fetch($content_refs)) {
					$js_prod.= 'n=document.createElement(\'option\'); n.text=\''.$ref['line'].'\'; n.value=\''.$ref['id'].'\'; ';
					$js_prod.= 'try{refs.add(n,null);}catch(ex){refs.add(n);} ';  // First part standard, second for IE
					if ($prod['unit_id']==$default_prod) { $options_refs.='<option value="'.$ref['id'].'"'.($ref['id']==$default_ref?' selected="selected"':'').'>'.$ref['line'].'</option>'; }
				}
				$js_prod.= '} ';
			}
			$options_cat.='<option value="'.$cat['id'].'"'.($cat['id']==$default_cat?' selected="selected"':'').'>'.$cat['name'].'</option>';
			$js_cat.= '} ';
		}
		$js_cat.='}';
		$js_prod.='}';
		printJS($js_cat.$js_prod, true);
		print('<select id="selcat_'.$field.'"'.($multiple?' multiple="multiple" size="10"':'').' onchange="'.$field.'_chooseCat();'.$field.'_chooseProd();">');
			if (!$multiple && $options_prod=='') { print('<option>Choose...</option>'); }
			print($options_cat);
		print('</select>&nbsp;&rarr;&nbsp;');
		print('<select id="sel_'.$field.'"'.($multiple?' multiple="multiple" size="10"':'').' onchange="'.$field.'_chooseProd();">');
			print($options_prod);
		print('</select>&nbsp;&rarr;&nbsp;');
		print('<select id="selref_'.$field.'"'.($multiple?' multiple="multiple" size="10"':'').' name="'.$field.($multiple?'[]':'').'">');
			print($options_refs);
		print('</select>');
	}

	function printSetSelect($title, $field, $default, $multiple = true, $showHidden=true, $selectionDelegateFunction='') {
		if ($title!='') { print('<label for="selcat_'.$field.'">'.$title.' </label>'); }
		if ($default > -1) {
			$content_default = db_x('SELECT cat_id FROM sets WHERE id="'.$default.'";');
			$row_default = db_fetch($content_default);
			$default_cat = $row_default['cat_id'];
		}
		else {
			$default_cat = -1;
		}
		$js_cat = 'function '.$field.'_chooseCat() {
			var id = document.getElementById(\'selcat_'.$field.'\').value;
			var sets = document.getElementById(\'sel_'.$field.'\');
			var n;
			while (sets.length > 0) { sets.remove(0); }
			'.$selectionDelegateFunction.'(-1);
		';
		$options_cat = '';
		$options_prod = '';
		$content_cats = db_x('SELECT id, name FROM set_cats '.($showHidden?'':'WHERE status="visible"').' ORDER BY name ASC;');
		while ($cat = db_fetch($content_cats)) {
			$js_cat.= 'if (id=='.$cat['id'].') { ';
			$content_prods = db_x('SELECT s.id, CONCAT_WS(" - ", b.name, s.name) as name, s.elinca_code FROM (sets AS s LEFT OUTER JOIN sets AS b ON s.base_set=b.id) WHERE s.cat_id="'.$cat['id'].'" '.($showHidden?'':'AND s.status="visible"').' ORDER BY elinca_code ASC;');
			while ($prod = db_fetch($content_prods)) {
				$js_cat.= 'n=document.createElement(\'option\'); n.text=\''.$prod['elinca_code'].' - '.$prod['unit_name'].'\'; n.value=\''.$prod['id'].'\'; ';
				$js_cat.= 'try{sets.add(n,null);}catch(ex){sets.add(n);} ';  // First part standard, second for IE
				if ($cat['id']==$default_cat) { $options_prod.='<option value="'.$prod['id'].'"'.($prod['id']==$default?' selected="selected"':'').'>'.$prod['elinca_code'].' - '.$prod['unit_name'].'</option>'; }
			}
			$options_cat.='<option value="'.$cat['id'].'"'.($cat['id']==$default_cat?' selected="selected"':'').'>'.$cat['name'].'</option>';
			$js_cat.= '} ';
		}
		$js_cat.='}';
		printJS($js_cat, true);
		print('<nobr><select id="selcat_'.$field.'"'.($multiple?' multiple="multiple" size="10"':'').' onchange="'.$field.'_chooseCat();">');
			if (!$multiple && $options_prod=='') { print('<option>Choose...</option>'); }
			print($options_cat);
		print('</select>&nbsp;&rarr;&nbsp;');
		print('<select id="sel_'.$field.'"'.($multiple?' multiple="multiple" size="10"':'').' name="'.$field.($multiple?'[]':'').'" onchange="'.$selectionDelegateFunction.'(this.value);">');
			print($options_prod);
		print('</select></nobr>');
	}


?>