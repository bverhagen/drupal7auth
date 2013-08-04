<?php
// now set up the mysql config strings
$conf['server']   = 'localhost';		// Address of the database server
$conf['user']     = '';				// Username of the database user
$conf['password'] = '';				// Associated password of the database user
$conf['database'] = '';				// Name of the Drupal 7 database
$conf['db_prefix'] = '';			// Prefix of each table in the Drupal 7 database

// Queries for the database: do not touch
$conf['checksession'] = "SELECT u.name, u.mail FROM %{db_prefix}sessions s INNER JOIN %{db_prefix}users u ON s.uid=u.uid WHERE s.uid = '%{user_id}' AND s.sid = '%{session_id}'";
$conf['roles'] = "SELECT r.name FROM %{db_prefix}users_roles u RIGHT JOIN %{db_prefix}role r ON u.rid=r.rid WHERE u.uid='%{user_id}'";
$conf['groups'] = "SELECT r.name FROM %{db_prefix}role r";
