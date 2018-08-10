<?php
	// 個別にsingleページが用意されていない記事詳細は404へ
	header("HTTP/1.0 404 Not Found");
	get_template_part("404");
	return;
?>
