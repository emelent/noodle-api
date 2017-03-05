<?php

const ROOT = '/v1/';
const MODULES_ROUTE = ROOT . 'modules';
const EVENTS_ROUTE = ROOT . 'events';
const TIMETABLES_ROUTE = ROOT . 'timetables';
const USERS_ROUTE = ROOT . 'users';

const MODULE_FIELDS = [
  'id', 'code','description', 
  'name', 'postgrad', 'period'
];

const EVENT_FIELDS = [
  'name', 'day', 'start', 'end',
  'date', 'language', 'group',
  'creator_id', 'module_id', 'created_at',
  'updated_at'
];

const USER_FIELDS = [
	'email',
	'id'
];

const TIMETABLE_FIELDS = [
  'id', 'hash', 'moduleDna', 'creator_id',
  'created_at', 'updated_at'
];
