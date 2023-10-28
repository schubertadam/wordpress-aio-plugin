<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-28
 * Time: 09:20
 */

function showErrorMessage(string $message): void {
	add_action('admin_notices', function () use ($message) {
		echo sprintf('<div class="error notice"><p>%s</p></div>', esc_html($message));
	});
}

function showNoticeMessage(string $message): void {
	add_action('admin_notices', function () use ($message) {
		echo sprintf('<div class="update-nag notice"><p>%s</p></div>', esc_html($message));
	});
}