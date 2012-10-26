<?php

	namespace phpish\template;


	function render($template, $template_vars=array())
	{
		$template_file = filename($template);

		if (file_exists($template_file))
		{
			if (!empty($template_vars) and is_array($template_vars)) extract($template_vars);
			ob_start();
			require $template_file;
			$buffer = ob_get_contents();
			ob_end_clean();

			return $buffer;
		}
		else
		{
			trigger_error("Eh-oh! Required template $template ($template_file) not found", E_USER_ERROR);
		}

		return false;
	}

		function filename($template)
		{
			if (_has_absolute_path($template))
			{
				$template_dir = '';
			}
			else
			{
				$template_dir = basedir() ?: default_basedir();
			}

			$template_dir = !empty($template_dir) ? rtrim($template_dir, '/\\').DIRECTORY_SEPARATOR : $template_dir;
			$template = rtrim($template, '/\\');
			return _slashes_to_directory_separator("$template_dir$template.php");
		}

			function _has_absolute_path($template)
			{
				$begins_with_directory_separator = (DIRECTORY_SEPARATOR === $template{0});
				$begins_with_windows_drive_letter = (':\\' === substr($template, 1, 2));
				$begins_with_network_drive = ('\\\\' === substr($template, 0, 2));
				return ($begins_with_directory_separator or $begins_with_windows_drive_letter or $begins_with_network_drive);
			}

			function basedir($dir=NULL)
			{
				static $template_dir;
				if (is_null($dir) and isset($template_dir)) return $template_dir;
				return $template_dir = $dir;
			}

			function default_basedir()
			{
				return _currently_executing_script_dir().'templates'.DIRECTORY_SEPARATOR;
			}

				function _currently_executing_script_dir()
				{
					return dirname($_SERVER['SCRIPT_FILENAME']).DIRECTORY_SEPARATOR;
				}

			function _slashes_to_directory_separator($path)
			{
				return preg_replace('/[\/\\\]/', DIRECTORY_SEPARATOR, $path);
			}


	function compose($template)
	{
		$args = array_slice(func_get_args(), 1);
		$template_vars = (!empty($args) and (is_array($args[0]))) ? array_shift($args) : array();
		$content = render($template, $template_vars);

		while(!empty($args))
		{
			if (is_null($args[0]))
			{
				array_shift($args);
				continue;
			}
			$template = array_shift($args);
			$template_vars = (!empty($args) and (is_array($args[0]))) ? (array_shift($args) + $template_vars) : $template_vars;
			$template_vars['content'] = $content;
			$content = render($template, $template_vars);
		}

		return $content;
	}

?>