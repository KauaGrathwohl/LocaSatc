<?php
/**
 * Copyright 2008, Debuggable Limited (http://www.debuggable.com/)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @version    1.0 Beta
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * A behavior to allow any amount of virtual fields on a Model.
 *
 * @todo Add support for negative field inclusion rules
 * @package default
 * @access public
 */
class ExpandableBehavior extends ModelBehavior {

	public $settings = array();

	public function setup(Model $model, $settings = array()) {
		$base = array('schema' => $model->schema());
		if (isset($settings['with'])) {
			$conventions = array('foreignKey', $model->hasMany[$settings['with']]['foreignKey']);
			return $this->settings[$model->alias] = am($base, $conventions, $settings);
		}
		foreach ($model->hasMany as $assoc => $option) {
			if (strpos($assoc, 'Field') !== false) {
				$conventions = array('with' => $assoc, 'foreignKey' => $model->hasMany[$assoc]['foreignKey']);
				return $this->settings[$model->alias] = am($base, $conventions, !empty($settings) ? $settings : array());
			}
		}
	}

	public function afterFind(Model $model, $results, $primary=false) {
		extract($this->settings[$model->alias]);
		if (!Set::matches('/'.$with, $results)) {
			return;
		}
		foreach ($results as $i => $item) {
			foreach ($item[$with] as $field) {
				$results[$i][$model->alias][$field['key']] = $field['val'];
			}
		}
		return $results;
	}

	public function afterSave(Model $model, $created, $options=array()) {
		extract($this->settings[$model->alias]);
		$fields = array_diff_key($model->data[$model->alias], $schema);
		$id = $model->id;
		foreach ($fields as $key => $val) {
			if(!is_array($val))
			{
				$field = $model->{$with}->find('first', array(
					'fields' => array($with.'.id'),
					'conditions' => array($with.'.'.$foreignKey => $id, $with.'.key' => $key),
					'recursive' => -1,
				));
				$model->{$with}->create(false);
				if ($field) {
					$model->{$with}->set('id', $field[$with]['id']);
				} else {
					$model->{$with}->set(array($foreignKey => $id, 'key' => $key));
				}
				$model->{$with}->set('val', $val);
				$model->{$with}->save();
			}
		}
	}

}
?>
