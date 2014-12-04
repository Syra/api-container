<?php

namespace Syra\ApiContainer\Helper;


class Observer {

	/**
	 * @var Observer
	 */
	protected static $Instance;
	protected $events;

	public static function getInstance() {
		if (empty(static::$Instance)) {
			static::$Instance = new Observer();
		}
		return static::$Instance;
	}

	public static function setListener($event, $action) {
		$Instance = static::getInstance();
		$Instance->events[$event][] = $action;
	}

	public static function fireEvent($event) {
		$Instance = static::getInstance();
		if (isset($Instance->events[$event])) {
			foreach ($Instance->events[$event] as $action) {
				call_user_func($action);
			}
		}
	}

	public static function getEventName($event, $class, $method = null) {
		$eventName = $event . get_class($class);
		if ($method) {
			$eventName .= '::' . $method;
		}
		return $eventName;
	}

} 