<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="ja"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="ja"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="ja"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="ja"> <!--<![endif]-->
<?php
	// メタ情報を取得
	$meta = get_gochi_meta("news");
?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $meta["title"]; ?></title>
<meta name="description" content="<?php echo $mata["description"]; ?>">
<meta name="keywords" content="<?php echo $meta["keywords"]; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:title" content="<?php echo $meta["title"]; ?>">
<meta property="og:description" content="<?php echo $meta["description"]; ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo home_url("/news/", "http"); ?>">
<meta property="og:image" content="<?php echo COMMON_OGP; ?>">

<link rel="shortcut icon" type="image/x-icon" href="/assets/common/img/favicon.ico">

<link rel="stylesheet" href="/assets/common/css/normalize.min.css">
<link rel="stylesheet" href="/assets/common/css/main.css">
<script src="/assets/common/js/vendor/modernizr-2.8.3.min.js"></script>
<!--[if lt IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv-printshiv.js"></script>
<![endif]-->

</head>
<body class="news__body">

<div id="sitetop" class="content">

<?php
	include(DOC_ROOT . "/assets/include/header.inc");
?>

<main class="main">
<section class="section">
<div class="section__inner">

<h1 class="top--hdr news__hdr"><img src="/assets/img/news/news-page_hdr_l.png" alt="NEWS" data-src="/assets/img/news/news-page_hdr_s.png"><span>NEWS 一覧</span></h1>
<div class="news__inner">
<div class="top--news__list">

<?php
	// ページネーション初期設定
	$big = 99999999;
	$paged = (get_query_var("paged")) ? get_query_var("paged") : 1;

	// ページネーションの取得条件を設定（全件取得して手動で調整する）
	$paginates = array();
	$page_args = array(
		"base" => str_replace($big, "%#%", esc_url(get_pagenum_link($big))),
		"format" => "?paged=%#%",
		"current" => max(1, get_query_var("paged")),
		"total" => $wp_query->max_num_pages,
		"show_all" => true,
		"type" => "array",
		"prev_next" => false,
	);

	// ページネーションを配列で取得（ここでは表示させない）
	$paginates = paginate_links($page_args);

	// 現在のページと最後のページ番号を取得
	$cur_page = intval(get_query_var("paged"));
	$max_page = intval($wp_query->max_num_pages);

	// １ページ目の場合は取得できないので、手動で「1」をセット
	if (empty($cur_page)) {
		$cur_page = 1;
	}

	// 現在のページの前後２ページずつを表示する
	if ($cur_page <= 3) {
		$from_page = 1;
		$to_page = 5;
	} else {
		$from_page = $cur_page - 2;
		$to_page = $cur_page + 2;
	}

	// 但し存在しないページのリンクは出さない
	// 全部で５ページ以上ある場合に、現在のページがどこに動いても全体で５ページ表示させるよう調整
	if ($to_page > $max_page) {
		$diff = $to_page - $max_page;
		$to_page = $max_page;
		$from_page = $from_page - $diff;
	}
	
	// ニュース一覧繰り返し表示開始
	while (have_posts()) : the_post();
	
		// ニュースカテゴリー情報を取得
		$news_cat = get_the_terms(get_the_id(), "news_cat");
		$news_cat = $news_cat[0];
		
		// 外部リンク指定時のclassなどを指定
		if (get("news_external") === 1) :
			$blank = ' target="_blank"';
			$class = ' class="link__target-blank"';
		else :
			$blank = null; $class = null;
		endif;
		
		// url指定の有無flg
		$f_url = (get("news_url") <> "") ? true : false;
?>

<article class="top--news__item">
<?php
	// URL指定がある場合のみ<a>タグを出力
	if ($f_url) :
?>
<a href="<?php echo get("news_url"); ?>"<?php echo $blank; ?>>
<?php
	// URL指定がなければ<span>タグを出力
	else : echo "<span>";
	endif;
?>
<p class="top--news__item__tag"><img src="/assets/img/index/news_tag_<?php echo $news_cat->slug; ?>_l.png" data-src="/assets/img/index/news_tag_<?php echo $news_cat->slug; ?>_s.png" alt="<?php echo $news_cat->name; ?>"></p>
<time datetime="<?php echo get_the_time("Y.m.d"); ?>" class="top--news__item__time"><?php echo get_the_time("Y.m.d"); ?></time>
<p class="top--news__item__txt"><span<?php echo $class; ?>><?php echo get("news_text"); ?></span></p>
<?php
	// URL指定がある場合のみ</a>を出力
	if ($f_url) :
?>
</a>
<?php
	// URL指定がなければ</span>を出力
	else : echo "</span>";
	endif;
?>
</article>
<?php
	// ニュース一覧繰り返し表示終了
	endwhile;
?>

<!-- /.top--news__list --></div>
<!-- /.news__inner --></div>

<div class="pagination-area clearfix">
<nav class="pagination">
<?php
	// 現在のページが1ページ目以外の場合のみ、戻るを表示
	if ($cur_page !== 1) :
?>
<a class="pagination__list page-prev" href="<?php echo esc_url(get_pagenum_link($cur_page - 1)); ?>">戻る</a>
<?php
	// 「戻る」表示分岐終了
	endif;
?>
<?php
	// 表示されているページが1ページ目超の場合、ページ表示の前に「…」を表示
	if ($from_page > 1) :
?>
<a class="pagination__list page-ellipsis" href="<?php echo esc_url(get_pagenum_link($from_page - 1)); ?>">…</a>
<?php
	// 「…」表示分岐終了
	endif;
?>
<?php
	// 現在のページを含めて前後2ページを表示ループ開始
	// 但し、存在しないページ番号は非表示
	for ($i = $from_page; $i <= $to_page; $i++) :
	
		// 現在のページの場合、page-current属性を追加
		$current = ($i === $cur_page) ? " page-current" : null;
		
		// 実際に存在するページ番号のみを表示
		if (isset($paginates[$i - 1])) :
?>
<a class="pagination__list page-numbers<?php echo $current; ?>" href="<?php echo esc_url(get_pagenum_link($i)); ?>"><?php echo $i; ?></a>
<?php
		endif;		// 存在するページ番号のみの表示分岐終了
	endfor;			// 現在のページ前後2ページを表示ループ終了
?>
<?php
	// 表示されている最後のページよりまだ先のページがある場合、ページ表示の後に「…」を表示
	if ($to_page < $max_page) :
?>
<a class="pagination__list page-ellipsis" href="<?php echo esc_url(get_pagenum_link($to_page + 1)); ?>">…</a>
<?php
	// 「…」表示分岐終了
	endif;
?>
<?php
	// 現在のページが最後のページ以下の場合、次へを表示
	if ($cur_page < $max_page) :
?>
<a class="pagination__list page-next" href="<?php echo esc_url(get_pagenum_link($cur_page + 1)); ?>">次へ</a>
<?php
	// 「次へ」表示分岐終了
	endif;
?>
</nav>
<!-- /.pagination-area --></div>

<!-- /.section__inner --></div>
</section>

</main>

<?php
	include(DOC_ROOT . "/assets/include/footer.inc");
?>

<!-- /.content --></div>

<script src="/assets/common/js/vendor/jquery-1.11.2.min.js"></script>
<script src="/assets/common/js/base.js"></script>

<?php
	include(DOC_ROOT . "/assets/include/analytics.inc");
?>

</body>
</html>
