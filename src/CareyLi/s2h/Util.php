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
}