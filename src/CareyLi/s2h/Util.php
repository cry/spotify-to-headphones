<?php

namespace CareyLi\s2h;

/**
 * Utility Functions
 * @author Carey Li
 */
class Util
{
	/*
	 * Returns first non null variable.
	 */
	public static function coalesce(...$params)
	{
		foreach (func_get_args() as $arg) {
			if($arg !== null) {
				return $arg;
			}
		}
	}

	/*
	 * Attempts to get a $_GET variable, if non-existent return null.
	 */
	public static function getArgExists($arg)
	{
		if (isset($_GET[$arg])) {
			return true;
		} else {
			return false;
		}
	}
}