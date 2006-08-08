<?php

require_once "processor.php";
/**
 * An XHTML parser/filter, based on the abstract Processor class.
 */
class XHTML extends Processor {
	public $allowed_tags = array(	'a', 'abbr', 'acronym', 'address', 'blockquote', 'cite', 'code',
					'del', 'dl', 'em', 'h4', 'h5', 'h6', 'ins', 'kdb', 'li', 'ol', 'p',
					'samp', 'strong', 'table', 'thead', 'tbody', 'tr', 'td', 'var' );
	public $allowed_attrs = array(	'class', 'href', 'target'  );
	public function startElement($parser, $name, $attrs)
	{
		$name_lc = strtolower($name);
		$attr_string = $this->html_var_implode($attrs);
		$allowed_tag = array_search($name_lc, $this->allowed_tags) !== false;
		$allowed_attrs = $this->allowed_attrs_test($attrs);
		if($allowed_tag && $allowed_attrs)
			echo $attr_string ? "<$name_lc $attr_string>" : "<$name_lc>";
		else
			echo $attr_string ? "&lt;$name_lc $attr_string&gt;" : "&lt;$name_lc&gt;";
	}

	public function endElement($parser, $name)
	{
		$name_lc = strtolower($name);
		$allowed_tag = array_search($name_lc, $this->allowed_tags) !== false;
		if($allowed_tag)
			echo "</$name_lc>";
		else
			echo "&lt;/$name_lc&gt;";
	}
	
	public function piData($parser, $target, $data)
	{
		echo "&lt;?$target $data?&gt;";
	}

	public function characterData($parser, $data)
	{
		echo htmlspecialchars($data);
	}

	public function parse() {
		$input = func_get_arg(0);
		ob_start();
		$xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
		xml_set_element_handler($xml_parser, array ( &$this, 'startElement' ), array ( &$this, 'endElement' ));
		xml_set_processing_instruction_handler($xml_parser, array ( &$this, 'piData' ));
		xml_set_character_data_handler($xml_parser, array ( &$this, 'characterData' ));
		xml_parse($xml_parser, $input, true);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	public function html_var_implode($pieces) {
		$output = "";
		$pieces_keys = array_keys($pieces);
		$pieces_values = array_values($pieces);
		for($n = 0; $n < count($pieces_keys); $n++)
		{
			$piece_key = strtolower($pieces_keys[$n]);
			$piece_value = $pieces_values[$n];
			$output .= ($n == 0 ? "" : " ") . "$piece_key=\"$piece_value\"";
		}
		return $output;
	}
	
	public function allowed_attrs_test($attrs) {
		$attrs_keys = array_keys($attrs);
		foreach($attrs_keys as $attr_key)
		{
			$attr_key = strtolower($attr_key);
			if(array_search($attr_key, $this->allowed_attrs) === false)
				return false;
		}
		return true;
	}
}