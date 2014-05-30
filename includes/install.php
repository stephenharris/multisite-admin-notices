<?php

/**
 * Activate the plugin
 */
function msan_activate() {

	// First load the init scripts in case any rewrite functionality is being loaded
	msan_init();

	flush_rewrite_rules();
}


/**
 * Deactivate the plugin
 */
function msan_deactivate() {

	flush_rewrite_rules();
}


/**
 * Uninstall the plug-in
 */
function msan_uninstall() {

	flush_rewrite_rules();
}
