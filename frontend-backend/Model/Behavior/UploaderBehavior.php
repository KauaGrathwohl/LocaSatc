<?php
/**
 * Uploader Behaviors
 *
 * Attach files/Upload files in based model forms
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * Basic Usage:
 *
 * public $actsAs => array('Uploader' => array(
 * 	'files' => array(
 * 		'img' => array('src' => 'youpathrelativetowebroot/image_:id.jpg')
 * 	)
 * ));
 *
 * @author Lucas Ferreira
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @copyright Copyright 2011-2023, Burn web.studio - http://www.burnweb.com.br/
 * @version 1.5b
 */

class UploaderBehavior extends ModelBehavior
{
	public $options = array();
	public $global = array();
	public $pendingFiles = array();

	public function setup(Model $model, $settings=array())
	{
		$this->global = array(
			'delete' => true,
			'virtual' => true,
			'files' => array(),
			'path' => WWW_ROOT,
			'required' => false,
			'ae' => array(
				"jpg", "jpeg",
				"png",
				"gif",
				"txt",
				"htm", "html",
				"xml",
				"pdf",
				"xls", "xlsx",
				"doc", "docx",
				"dat",
				"mp4", "m4v",
				"mov",
				"wmv",
				"flv",
				"mp3", "wav",
				"swf"
			)
		);

	  $_options = array_merge(array(
			'name' => $model->alias,
			'schema' => $model->schema(),
			'delete' => $this->global['delete']
		), $settings);

		if(!empty($_options['files']) && is_array($_options['files']))
		{
			foreach($_options['files'] as &$config)
			{
				$config = array_merge($this->global, $config);
			}
		}
		$this->options[$model->alias] = &$_options;
	}

	public function isFile(Model $model, $data, $check)
	{
		$field_name = key($data);
		if(!isset($this->options[$model->alias]['files'][$field_name]))
		{
			return true;
		}
		else
		{
			$field = $this->options[$model->alias]['files'][$field_name];
		}

		if(func_num_args() === 3)
		{
			$ae = $field['ae'];
		}
		else
		{
			$ae = is_array($check) ? $check : explode(",", $check);
			$ae = array_map("trim", $ae);
		}

 		if($field['required'] !== false && (empty($data[$field_name]) || !is_uploaded_file($data[$field_name]['tmp_name'])))
		{
			return false;
		}

		if(!empty($data[$field_name]) && is_uploaded_file($data[$field_name]['tmp_name']))
		{
			return ( array_search( end( explode(".", $data[$field_name]['name']) ), $ae) !== false );
		}

		return true;
	}

	private function __getFileName($field, $config, $data)
	{
		if(isset($config['virtual']) && (bool)$config['virtual'] !== true)
		{
			if(isset($data[$field]) && !empty($data[$field]))
			{
				$__full_path = pathinfo($config['path'] . $config['src'], PATHINFO_DIRNAME);
				if(file_exists($__full_path . DS . $data[$field]) && is_file($__full_path . DS . $data[$field]))
				{
					return str_replace($config['path'], '', $__full_path . DS . $data[$field]);
				}
			}

			return null;
		}

		$__path = $config['path'];
		$__ae = $config['ae'];

		foreach($data as $i => $d)
		{
			if(is_array($d)) unset($data[$i]);
		}

		if(strpos($config['src'], ":ext") === false)
		{
			$__name = CakeText::insert($config['src'], $data);
			if(file_exists($__path . $__name))
			{
				return $__name;
			}
		}
		else
		{
			foreach($__ae as $ext)
			{
				$data = array_merge($data, array("ext" => $ext));
				$__name = CakeText::insert($config['src'], $data);
				if(file_exists($__path . $__name))
				{
					return $__name;
				}
			}
		}

		return null;
	}

	private function __getFullFileName($field, $config, $id, $data=array())
	{
		$__path = $config['path'];

		$data['id'] = $id;
		if(isset($data[$field]) && !empty($data[$field]['name']))
		{
			$data['ext'] = pathinfo($data[$field]['name'], PATHINFO_EXTENSION);
			$data['filename'] = pathinfo($data[$field]['name'], PATHINFO_FILENAME);
			$data['clean_filename'] = Inflector::slug(iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $data['filename']), "-");
			if(mb_detect_encoding($data['clean_filename']) !== 'UTF-8')
			{
				$data['clean_filename'] = utf8_encode($data['clean_filename']);
			}
			if(strpos($config['src'], ":id") === false)
			{
				$data['clean_filename'] = $data['clean_filename'] . "_" . $id;
			}
		}

		foreach($data as $i => $d)
		{
			if(is_array($d) || is_object($d)) unset($data[$i]);
		}

		$__name = CakeText::insert($config['src'], $data);

		return $__path . $__name;
	}

	public function fileform2data(Model $model, $ff, $data=array())
	{
		$new_data = array();
		foreach($ff as $key=>$form)
		{
			if(!is_array($form) || empty($form['tmp_name'])) continue;

			$fdata = array();
			$fkeys = array_keys($form);
			for($i=0; $i<count($form['tmp_name']); $i++)
			{
				foreach($fkeys as $fk)
				{
					$fdata[$fk] = $form[$fk][$i];
				}
				$new_data[] = array_merge($data, array("$key" => $fdata, "i" => $i));
			}
		}

		return $new_data;
	}

	public function deleteFile(Model $model, $field, $id)
	{
		if(!empty($this->options[$model->alias]['files'][$field]))
		{
			$config = $this->options[$model->alias]['files'][$field];

			$fdata = $model->find('first', array(
				'conditions' => array("{$model->alias}.{$model->primaryKey}" => $id),
				'recursive' => -1
			));
			if(!empty($fdata) && !empty($fdata[$model->alias][$field]))
			{
				$fd = $config['path'] . $fdata[$model->alias][$field];
				if(file_exists($fd))
				{
					@unlink($fd);
					return true;
				}
			}
		}
		return false;
	}

	public function beforeSave(Model $model, $options=array())
	{
		if(!empty($model->data[$model->alias]))
		{
			$data = &$model->data[$model->alias];
			foreach($this->options[$model->alias]['files'] as $field => $config)
			{
				if(!empty($data[$field]) && is_array($data[$field]))
				{
					if(!isset($this->pendingFiles[$model->alias])) $this->pendingFiles[$model->alias] = array();

					$this->pendingFiles[$model->alias][$field] = $data[$field];
					unset($data[$field]);
				}
			}
		}

		return true;
	}

	public function afterSave(Model $model, $created, $options=array())
	{
		$id = $model->{$model->primaryKey};
		if(!empty($this->pendingFiles[$model->alias]))
		{
			$data = $this->pendingFiles[$model->alias];
			foreach($this->options[$model->alias]['files'] as $field => $config)
			{
				if(!empty($data[$field]) && !empty($data[$field]['tmp_name']) && is_uploaded_file($data[$field]['tmp_name']))
				{
					if(!$created && (!isset($config['virtual']) || (bool)$config['virtual'] === true))
					{
						$current_file_src = $this->__getFileName($field, $config, $data);
						if($created !== true && file_exists($config['path'] . $current_file_src))
						{
							@unlink($config['path'] . $current_file_src);
						}
					}

					$file_src = $this->__getFullFileName($field, $config, $id, $data);
					if(move_uploaded_file($data[$field]['tmp_name'], $file_src))
					{
						@chmod($file_src, 0755);

						if(!empty($config['type']) && $config['type'] === "image")
						{
							Util::resizeImage($file_src, $config);
						}

						if(isset($config['virtual']) && (bool)$config['virtual'] !== true)
						{
							$updateData = array('id' => $id, 'modified' => false, "{$field}" => basename($file_src));
							$model->save($updateData, array('validate' => false, 'callbacks' => false, 'counterCache' => false, 'atomic' => false));
						}
					}
				}
			}
		}
	}

	public function afterFind(Model $model, $data, $primary=false)
	{
		foreach($data as $i => $d)
		{
			foreach($this->options as $_modeName => $cfg)
			{
				if(!empty($d[$_modeName]))
				{
					$ad = $d[$_modeName];
					if(empty($ad[0]))
					{
						foreach($cfg['files'] as $field => $config) $ad[$field] = $this->__getFileName($field, $config, $ad);
					}
					else
					{
						foreach($ad as $k=>$dd)
						{
							foreach($cfg['files'] as $field => $config) $ad[$k][$field] = $this->__getFileName($field, $config, $dd);
						}
					}

					$d[$_modeName] = $ad;
				}
			}

			$data[$i] = $d;
		}

		return $data;
	}

	public function beforeDelete(Model $model, $cascade=true)
	{
		if(!empty($model->{$model->primaryKey}) && $this->options[$model->alias]['delete'])
		{
			foreach($this->options[$model->alias]['files'] as $field => $config)
			{
				if(!isset($config['delete']) || $config['delete'] === true)
				{
					$this->deleteFile($model, $field, $model->{$model->primaryKey});
				}
			}
		}

		return true;
	}
}
?>
