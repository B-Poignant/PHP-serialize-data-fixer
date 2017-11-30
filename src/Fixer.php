<?php

use UnserializeFixer\Interfaces;
use UnserializeFixer\Exceptions;

namespace UnserializeFixer;

class Fixer implements Interfaces\iFixer {

	private static $_serialize_type	 = ['i', 'b', 'd', 's', 'a', 'O'];
	private static $_steps_left		 = null;
	private static $_config		 = null;
	
	/**
	 * writeLog to overwrite see Implements Folder
	 * @param string $message
	 * @param type $data
	 * @param string $level
	 */
	public static function writeLog($message, $data = null, $level = 'debug') {
		if (self::getConfig()->getLogEnabled())
		{
			switch ($level) {
				default:
				case 'debug' :
					$color	 = "C7FAFF";
					break;
				case 'info' :
					$color	 = "1A30F5";
					break;
				case 'warning' :
					$color	 = "FFFF80";
					break;
				case 'error' :
					$color	 = "FD1111";
					break;
			}
			echo '<p style="background-color:' . $color . ';">' . $message . '</p>';
			if ($data)
			{
				echo '<pre>';
				print_r($data);
				echo '</pre>';
			}
		}
	}

	public static function getConfig() {
		if(self::$_config===null){
			$confg = new Config();
			$confg->init();
			self::setConfig($confg);
		}
		
		return self::$_config;
	}
	
	public static function setConfig(Config $config) {
		self::$_config = $config;
	}
	
	/**
	 * Main function
	 * @param type $serialized
	 * @return type
	 */
	public static function run($serialized) {
		self::writeLog('run');

		self::$_steps_left = [
			'LastItem',
			'ArrayNotClose',
			'InvalidLength',
			'InvalidSubsequence',
			'Bracket',
		];

		return self::treat($serialized);
	}

	/**
	 * Main function
	 * @param string $serialized
	 * @return type
	 * @throws \UnserializeFixer\Exceptions\CorruptedException
	 */
	public static function treat($serialized) {
		self::writeLog('treat');

		$data = @unserialize($serialized);
		self::writeLog('serialized', $serialized);

		//still unvalid ?
		if ($data === false)
		{
			if (count(self::$_steps_left) > 0)
			{
				$step		 = reset(self::$_steps_left);
				$next_call	 = 'handle' . $step;

				if (method_exists(__CLASS__, $next_call))
				{
					self::writeLog('next_call', $next_call);
					$serialized = call_user_func_array(array(__CLASS__, $next_call), array($serialized));

					if (($key = array_search($step, self::$_steps_left)) !== false)
					{
						unset(self::$_steps_left[$key]);
					}

					return self::treat($serialized);
				} else
				{
					throw new Exceptions\HandleMethodDontExistException($next_call);
				}
			}
			throw new Exceptions\CorruptedException();
		}


		return $data;
	}

	/**
	 * Check last item
	 * @param string $serialized
	 * @return string
	 */
	public static function handleLastItem($serialized) {
		//https://regex101.com/r/nMA31z
		preg_match_all('/([' . implode("|", self::$_serialize_type) . ']):([0-9]{0,})/', $serialized, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

		if ($matches)
		{
			$type = end($matches)[1][0];
			$serialized = self::handleLastItemByType($matches, $serialized, $type);
		}

		return $serialized;
	}

	/**
	 * Check every element length
	 * @param string $serialized
	 * @return string
	 */
	public static function handleInvalidLength($serialized) {
		//https://regex101.com/r/nGmMno
		preg_match_all('/([b|s]):([0-9]{0,})/', $serialized, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

		if ($matches)
		{
			$position_offset = 0;
			foreach ($matches as $match)
			{
				$type		 = $match[1][0];
				
				//todo refactor
				$lenght		 = (int) $match[2][0];
				$position	 = (int) $match[2][1] + $position_offset;

				if ($type == 's')
				{
					$content					 = substr($serialized, $position + strlen($lenght) + 2, $lenght);
					$content_have_doublequote	 = stristr($content, '"');

					if (substr($serialized, $position + strlen($lenght) + 2 + $lenght, 1) !== '"' || $content_have_doublequote)
					{

						//content end earlier
						if ($content_have_doublequote)
						{
							$double_quote_position	 = strpos($content, '"');
							$nb_to_insert			 = $lenght - $double_quote_position;

							$serialized = substr_replace($serialized, str_repeat('X', $lenght - $double_quote_position), $position + strlen($lenght) + 2 + $double_quote_position, 0);

							$position_offset+=$nb_to_insert;
						} else
						{
							$valid_length = strpos($serialized, '"', $position + 3) - $position - 3;

							$serialized = substr_replace($serialized, $valid_length, $position, strlen($lenght));

							if (strlen($valid_length) !== strlen($lenght))
							{
								$position_offset+=strlen($valid_length) - strlen($lenght);
							}
						}
					}
				} elseif ($type == 'b')
				{
					if (!in_array($lenght, [0, 1]))
					{
						$serialized = substr_replace($serialized, 1, $position, strlen($lenght));
					}
				}
			}
		}

		return $serialized;
	}

	/**
	 * Called by handleLastItem
	 * @param array $matches
	 * @param string $serialized
	 * @param string $type
	 * @return string
	 * @throws UnserializeFixer\Exceptions\InvalidTypeException
	 */
	public static function handleLastItemByType($matches, $serialized, $type) {
		switch ($type) {
			case 's' :

				$match = end($matches);
				
				//todo refactor
				$lenght		 = (int) $match[2][0];
				$position	 = (int) $match[2][1];
				
				$content					 = substr($serialized, $position + strlen($lenght) + 2, $lenght);
				$content_have_doublequote	 = stristr($content, '"');

				if (substr($serialized, $position + strlen($lenght) + 2 + $lenght, 1) !== '"' || $content_have_doublequote)
				{
					//content end earlier
					if ($content_have_doublequote)
					{
						$double_quote_position	 = strpos($content, '"');
						$nb_to_insert			 = $lenght - $double_quote_position;

						$serialized = substr_replace($serialized, str_repeat('X', $lenght - $double_quote_position), $position + strlen($lenght) + 2 + $double_quote_position, 0);
					} else
					{
						$serialized.= str_repeat("X", strlen($serialized)-$position-2).'";';
					}
				}

				break;
			case 'i' :
			case 'b' :
			case 'd' :
				if (substr($serialized, -1) == ':')
				{
					$serialized.= '1';
				}
				break;

			case 'a' :

				//ArrayNotClose gonna fix
				break;

			default :

				throw new Exceptions\InvalidTypeException($type);
		}

		return $serialized;
	}

	/**
	 * Check if all bracket are closed
	 * @param type $serialized
	 * @return type
	 */
	public static function handleBracket($serialized) {

		$missing_bracket = substr_count($serialized, '{') - substr_count($serialized, '}');

		if ($missing_bracket > 0)
		{
			$serialized.= str_repeat("}", $missing_bracket);
		}

		return $serialized;
	}

	/**
	 * Check any array
	 * @param string $serialized
	 * @return string
	 */
	public static function handleArrayNotClose($serialized) {

		//https://regex101.com/r/GlJioI
		preg_match_all('/a:([0-9]{0,})/', $serialized, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

		if ($matches)
		{
			$previous_imbricated_array_lenght = 0;
			foreach (array_reverse($matches) as $match)
			{
				$position	 = $match[0][1];
				$lenght		 = $match[1][0];

				if (substr($serialized, $match[0][1] + strlen($match[0][0]), 1) !== ':')
				{
					$serialized = substr_replace($serialized, ':', $match[0][1] + strlen($match[0][0]), 0);
				}
				if (substr($serialized, $match[0][1] + strlen($match[0][0]) + 1, 1) !== '{')
				{
					$serialized = substr_replace($serialized, '{', $match[0][1] + strlen($match[0][0]) + 1, 0);
				}

				$content = substr($serialized, $match[0][1] + 2 + strlen($lenght) + 2);

				//https://regex101.com/r/6xOzG7
				preg_match_all('/[' . implode("|", self::$_serialize_type) . ']:/', $content, $matches);

				$nb_element_missing = $lenght * 2 - count(end($matches)) + $previous_imbricated_array_lenght;

				if ($nb_element_missing > 0)
				{
					for ($i = 0; $i < $nb_element_missing; $i++)
					{
						$last_char = substr($serialized, -1);
						if ($last_char !== ';' && $last_char !== '{' && $last_char !== '}')
						{
							$serialized.=';';
						}
						$serialized.='s:' . strlen('X_' . $i) . ':"X_' . $i . '"';
					}

					$serialized .=';}';
				}

				$previous_imbricated_array_lenght+=$lenght * 2;
			}
		}

		return $serialized;
	}
	
	public static function handleInvalidSubsequence($serialized) {
		return preg_replace('~{{2,}~','{', $serialized);
	}

	static function setStepsLeft(array $steps_left) {
		self::$_steps_left = $steps_left;
	}
	
	
}
