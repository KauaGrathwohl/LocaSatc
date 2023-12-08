<?php
class AppModel extends Model {

	public $cacheQueries = true;

	public function find($type = 'first', $query = array()) {
		if($type === 'label')
		{
			$data = $this->find('first', array_merge($query, array(
				'fields' => array("{$this->alias}.{$this->primaryKey}", "{$this->alias}.{$this->displayField}"),
				'recursive' => -1
			)));

			return isset($data[$this->alias][$this->displayField]) ? $data[$this->alias][$this->displayField] : null;
		}

		return parent::find($type, $query);
	}

	public function __getFloatFields() {
		$float_types = "/float|decimal|double/";
		$float_fields = array();

		foreach($this->_schema as $field=>$df) {
			if(preg_match($float_types, $df['type'])) {
				$float_fields[] = $field;
			}
		}

		return $float_fields;
	}

}
?>
