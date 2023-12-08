<?php
/**
 * Jsooner Behavior class
 *
 * Compact and expand fields in database (using json)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author Lucas Ferreira
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @copyright Copyright 2014, Burn web.studio - http://www.burnweb.com.br/
 * @version 0.5b
 */

class JsoonerBehavior extends ModelBehavior
{
	private $options = array();

	public function setup(Model $model, $settings=array())
	{
    $_options = array_merge(array(
			'name' => $model->alias,
			'schema' => $model->schema(),
			'displayField' => $model->displayField,
			'primaryKey' => $model->primaryKey,
			'fields' => array()
		), $settings);

		$this->options[$model->alias] = $_options;
	}

	private function __encode($data)
	{
		if(is_array($data) || is_object($data))
		{
			$data = json_encode($data);
		}

		return $data;
	}

	private function __decode($data)
	{
		if(is_string($data))
		{
			$data = json_decode($data, true);
		}

		return $data;
	}

	public function afterFind(Model $model, $datas, $primary=false)
	{
		foreach ($datas as $i => &$data)
		{
			foreach ($this->options as $m => $opts)
			{
				if (!empty($opts['fields']) && is_array($opts['fields']))
				{
					foreach ($opts['fields'] as $field)
					{
						if(isset($data[$m][0]) && is_array($data[$m][0]))
						{
							foreach ($data[$m] as &$sdata)
							{
								if(!empty($sdata[$field])) $sdata[$field] = $this->__decode($sdata[$field]);
							}
						}
						else
						{
							if(!empty($data[$m][$field])) $data[$m][$field] = $this->__decode($data[$m][$field]);
						}
					}
				}
			}
		}

		return $datas;
	}

	public function beforeSave(Model $model, $options=array())
	{
		foreach ($this->options as $m => $opts)
		{
			if (!empty($opts['fields']) && is_array($opts['fields']))
			{
				foreach ($opts['fields'] as $field)
				{
					if (isset($model->data[$m][$field]))
					{
						$model->data[$m][$field] = $this->__encode($model->data[$m][$field]);
					}
					if (isset($model->data[$field]))
					{
						$model->data[$field] = $this->__encode($model->data[$field]);
					}
				}
			}
		}

		return true;
	}
}
?>
