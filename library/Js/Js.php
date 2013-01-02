<?php
class Js_Js
{
	private $_params = array();
	private $_db;
	private $_scriptOnload;

	public function __construct()
	{
		$this->_db = Db_Db::conn();
	}

	public function setParams(array $params)
	{
		$this->_params = $params;
	}

	/**
	 * @param mixed $key
	 * @param mixed $defaultValue
	 * @param bool $overwriteEmptyValue
	 * @return mixed
	 */
	protected function _chkParam($key, $defaultValue = null, $overwriteEmptyValue = false)
	{
		return ( is_array($this->_params) && array_key_exists($key, $this->_params) && !empty($this->_params[$key])
				 || array_key_exists($key, $this->_params) && !$overwriteEmptyValue )
				? $this->_params[$key]
				: $defaultValue;
	}

	public function setScriptOnload($script)
	{
		$this->_scriptOnload .= $script;
	}

	public function fieldFocusOnload($id)
	{
		$id = is_string($id) ? $id : 'wrap';
		$this->_scriptOnload .= 'document.getElementById("' . $id . '").focus();';
	}

	public function setRequest($url, array $params, $id)
	{
		$paramsObj = array();
		foreach($params as $key => $value){
			if( is_array($value) === true ){
				foreach($value as $func => $val){
					$value = call_user_func($func, $this->_chkParam($val));
				}
			}else{
				$value = $this->_chkParam($value);
			}
			$paramsObj[] = "'$key':'$value'";
		}
		$this->_scriptOnload .= "request('$url', {" . implode(',', $paramsObj) . "}, '$id');" . PHP_EOL;
	}

	public function onloadScript()
	{
		return "window.onload = function(){ " . $this->_scriptOnload . " }";
	}

	public function categorySubjectScript()
	{
		$js = null;
		$result = array();

		$js .= 'var arrOpt = new Array();' . PHP_EOL;
		foreach($this->_db->fetchAll('SELECT * FROM categories') as $keyC => $c){
			$js .= 'arrOpt["' . $c['categories_id'] . '"] = new Array();' . PHP_EOL;
			$dbQuery = 'SELECT * FROM subjects WHERE categories_id = ' . $c['categories_id'] . ' ORDER BY categories_id';
			foreach($this->_db->fetchAll($dbQuery) as $keyS => $s){
				$js .= 'arrOpt["' . $c['categories_id'] . '"]'
					. '["' . $s['subjects_id'] . '_' . $s['subject'] . '"] = new Array();' . PHP_EOL;
			}
		}

		$js .= 'var cIndexSession = 0;
			var sIndexSession = 0;

			function nullOptions(obj){
				var n=obj.options.length
				for (i=0;i<n;i++){
					obj.options[i]=null
				}
				obj.options.length=0;
			}

			function getOpt(objs, params){
				var c = objs[0];
				var cIndex = c.selectedIndex;

				var s = objs[1];
				var sIndex = cIndexSession == cIndex ? s.selectedIndex : 0;
				var sParam = params != undefined && params.subjects_id != undefined
					? params.subjects_id
					: null;
				nullOptions(s);
				sText = cIndex > 0
					? "Sub-Categoría"
					: "Categoría";
				s.options[0] = new Option("[Selecciona " + sText + "]", "", true);

				cIndexSession = cIndex;
				sIndexSession = sIndex;

				for(var i in arrOpt){
					if(i == c.value){
						var n = 1;
						for(var ii in arrOpt[i]){
							sKey = ii.split("_");
							bool = sIndex == n || sKey[0] == sParam ? true : false;
							s.options[n++] = new Option(sKey[1], sKey[0], false, bool);
						}
					}
				}
			}';

		$this->_scriptOnload .= "getOpt("
				. "{0:document.getElementById('categories_id'),1:document.getElementById('subjects_id')},"
				. "{'subjects_id':'" . $this->_chkParam('subjects_id') . "'}"
			. ");";

		return $js;
	}
}