<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="ja"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="ja"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="ja"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="ja"> <!--<![endif]-->
<?php
	// レストラン詳細ページのURLが複数化しても対応できるよう、
	// 指定されたURLの情報から、カテゴリーと料理ジャンルのslugを取得
	$exp_url = explode("/", $_SERVER["REQUEST_URI"]);
	$rk = array_search("restaurant", $exp_url, true);
	if ($rk === false) : return; endif;		// URLにrestaurantが含まれなければ終了
	$parent_slug = (isset($exp_url[$rk+1])) ? $exp_url[$rk+1] : null;
	$cat_slug = (isset($exp_url[$rk+2])) ? $exp_url[$rk+2] : null;
	$food_slug = (isset($exp_url[$rk+3])) ? $exp_url[$rk+3] : null;
	// 使用する階層いずれかがセットされていなければ終了
	if (empty($parent_slug) || empty($cat_slug) || empty($food_slug)) : return; endif;

	// リクエストされたページに基づくカテゴリー情報を取得
	$cat = get_category_by_slug($cat_slug);
	$cat_hier = get_category_hierarchy($cat->term_id);

	// 関連店舗として選んだ記事のIDを取得
	$p = get_post_meta(get_the_id(), "report_target", true);

	// restaurant詳細ページと共通のメタ情報を取得
	$title = get_rest_meta_title($p);
	$description = esc_html(get_post_meta($p, "restaurant_meta_description", true));
	$keywords = esc_html(get_post_meta($p, "restaurant_meta_keywords", true));

	// ジャンル表示、コース・料金表示などの繰り返し共通表示情報を取得
	$genre = get_disp_term($p);				// ジャンル
	$disp_course = get_disp_course($p);		// コース名称と料金
	$rest_name = esc_html(get_post_meta($p, "restaurant_name", true));		// 店舗名
	$rest_ruby = esc_html(get_post_meta($p, "restaurant_name_ruby", true));	// 店舗名カナ
	$rest_tel = get_post_meta($p, "restaurant_tel", true);					// 電話番号
?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $title; ?></title>
<meta name="description" content="<?php echo $description; ?>">
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">

<meta property="og:title" content="<?php echo $title; ?>">
<meta property="og:description" content="<?php echo $description; ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php the_permalink(); ?>">
<meta property="og:image" content="<?php echo get_ogimage($p); ?>">

<link rel="shortcut icon" type="image/x-icon" href="/assets/common/img/favicon.ico">

<link rel="stylesheet" href="/assets/common/css/normalize.min.css">
<link rel="stylesheet" href="/assets/common/js/vendor/jquery.bxslider/jquery.bxslider.css">
<link rel="stylesheet" href="/assets/common/css/main.css">
<script src="/assets/common/js/vendor/modernizr-2.8.3.min.js"></script>
<!--[if lt IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv-printshiv.js"></script>
<![endif]-->

</head>
<body class="rest__body">

<div id="sitetop" class="content">

<?php
	include(DOC_ROOT . "/assets/include/header.inc");
?>

<main class="main">
<section class="section rest__details">
<div class="section__inner">
<h1 class="top--hdr rest__hdr"><img src="/assets/img/index/rest_hdr_l.png" data-src="/assets/img/index/rest_hdr_s.png" alt="RESTAURANT"></h1>

<div class="rest__details__inner">
<div class="rest__dateils__inner-sp--liquid">
<div class="rest__details__title-area">
<div class="rest__details__title__box">
<h1 class="rest__details__title"><?php echo $rest_name; ?></h1>
<div class="clearfix">
<p class="rest__details__sub-title"><?php echo $rest_ruby; ?></p>
<p class="rest__details__area sp_hide"><span>【エリア】</span><?php echo $cat->name; ?></p><p class="rest__details__genre sp_hide"><span>【ジャンル】</span><?php echo $genre; ?></p>
</div>
<!-- /.rest__details__title__box --></div>
<p class="rest__details__tel sp_hide"><?php echo $rest_tel; ?></p>
<!-- /.rest__details__title-area --></div>

<!-- sp -->
<div class="rest__details__sp-box sp_show">
<p class="rest__details__area-genre clearfix">
<span class="rest__details__area"><span>【エリア】</span><?php echo $cat->name; ?></span><span class="rest__details__genre"><span>【ジャンル】</span><?php echo $genre; ?></span></p>
<ul class="rest__details__social__list">
<li class="rest__details__social__item rest__details__social__item--00"><a href="http://line.me/R/msg/text/?<?php echo rawurlencode($title); ?>%0D%0A<?php the_permalink(); ?>"><img src="/assets/common/img/hdr_sns_line_s.png" alt=""></a></li>
<li class="rest__details__social__item rest__details__social__item--01"><a href="http://twitter.com/intent/tweet?text=<?php echo urlencode($title); ?>&amp;url=<?php the_permalink(); ?>" onclick="window.open(this.href, 'TWwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_tw_s.png"></a></li>
<li class="rest__details__social__item rest__details__social__item--02"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" onclick="window.open(this.href, 'FBwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_fb_s.png"></a></li>
</ul>
<!-- /.rest__details__sp-box --></div>
<!-- /sp -->

<div class="rest__details__sub-area clearfix">
<p class="rest__details__course">ごち会コース<span>：<?php echo $disp_course; ?></span></p>
<ul class="rest__details__social__list sp_hide clearfix">
<li class="rest__details__social__item rest__details__social__item--01"><a href="http://twitter.com/intent/tweet?text=<?php echo urlencode($title); ?>&amp;url=<?php the_permalink(); ?>" onclick="window.open(this.href, 'TWwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_tw_l.png"></a></li>
<li class="rest__details__social__item rest__details__social__item--02"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" onclick="window.open(this.href, 'FBwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_fb_l.png"></a></li>
</ul>
<!-- /.rest__details__sub-area --></div>
</div>

<div class="clearfix">
<div class="rest__details__main" id="rest__details__tab">
<!-- pc -->
<div class="sp_hide">
<ul class="rest__details__main__tab rest__details__main__tab-01 clearfix">
<li class="rest__details__main__tab-01__item img_replace rest__details__main__tab-01__shop is-tab__btn"><a href="../../">店舗情報</a></li>
<li class="rest__details__main__tab-01__item img_replace rest__details__main__tab-01__report is-tab__btn is-active">レポート</li>
<!-- /.rest__details__main__tab --></ul>
</div>
<!-- /pc -->

<!-- sp -->
<div class="sp_show">
<ul class="rest__details__main__tab rest__details__main__tab-01 clearfix">
<li class="rest__details__main__tab-01__item rest__details__main__tab-01__shop is-tab__btn"><a href="../../"><img src="/assets/img/restaurant/rest_details_main_repo_shop.png" alt="店舗情報"></a></li>
<li class="rest__details__main__tab-01__item rest__details__main__tab-01__report is-tab__btn is-active"><img src="/assets/img/restaurant/rest_details_main_repo_repo.png" alt="レポート"></li>
<!-- /.rest__details__main__tab --></ul>
</div>
<!-- /sp -->

<div class="rest__dateils__inner-sp--liquid">
<article class="rest__details__contents rest__details__report">
<h1 class="rest__details__report__hdr"><?php echo esc_html(get("report_title")); ?></h1>
<?php
	// レポート内容の繰り返しグループ取得
	$group = get_group("report_info");
	// レポート内容がある場合のみ表示開始
	if (count($group) > 0) :

		// 画像と必要クラスを一括取得
		$pictures = get_gochi_image_class(get_the_id(), "report_picture", "full", false);
		$i = 0;

		// レポート内容の繰り返し開始
		foreach ($group as $each) :

			// 各項目がさらに配列で取得されているので、先頭の要素を抜き出しておく
			foreach ($each as $k => $v) :
				$each[$k] = array_shift($each[$k]);
			endforeach;

			// 小見出しがある場合のみh2タグを出力
			if (!empty($each["report_subtitle"])) :
?>
<h2 class="rest__details__report__sub-hdr"><?php echo $each["report_subtitle"]; ?>
</h2>
<?php
			// 小見出し表示if終了
			endif;

			// 画像がある場合のみpタグ、imgタグを出力
			if (!empty($each["report_picture"])) :
?>
<p<?php echo $pictures[$i]["class"]; ?>><img src="<?php echo $pictures[$i]["url"]; ?>" data-src="<?php echo $pictures[$i]["url"]; ?>" alt=""></p>
<?php
			// 画像がある場合の表示if終了
			endif;
			$i++;		// 画像をカウントアップ

			// 紹介文がある場合のみ、pタグを出力
			if (!empty($each["report_content"])) :
?>
<p><?php echo nl2br($each["report_content"]); ?></p>
<?php
			// 紹介文がある場合の表示if終了
			endif;
		// レポート内容の繰り返し終了
		endforeach;
	// レポート内容ありの場合の表示分岐終了
	endif;
?>
</article>
</div>
<div class="sp_hide">
<ul class="rest__details__main__tab rest__details__main__tab-01 is-under clearfix">
<li class="rest__details__main__tab-01__item img_replace rest__details__main__tab-01__shop is-tab__btn"><a href="../../">店舗情報</a></li>
<li class="rest__details__main__tab-01__item img_replace rest__details__main__tab-01__report is-tab__btn is-active">レポート</li>
<!-- /.rest__details__main__tab --></ul>
</div>
<!-- sp -->
<div class="sp_show">
<ul class="rest__details__main__tab rest__details__main__tab-01 is-under clearfix">
<li class="rest__details__main__tab-01__item rest__details__main__tab-01__shop is-tab__btn"><a href="../../"><img src="/assets/img/restaurant/rest_details_main_repo_shop_under.png" alt="店舗情報"></a></li>
<li class="rest__details__main__tab-01__item rest__details__main__tab-01__report is-tab__btn is-active"><img src="/assets/img/restaurant/rest_details_main_repo_repo_under.png" alt="レポート"></li>
<!-- /.rest__details__main__tab --></ul>
</div>
<!-- /sp -->
<!-- /.rest__details__main --></div>

<?php
	$p_flg = false;
	// 同じエリアの他のお店をランダムに5件取得（親記事以外）
	$args = array(
		"post_type" => "restaurant",
		"posts_per_page" => 5,
		"orderby" => "rand",
		"cat" => $cat->term_id,
		"post__not_in" => array($p),
	);
	$others = get_posts($args);

	// 同じエリアに他に1件も無ければ、親エリアの店を取得（親記事以外）
	if (count($others) <= 0) :
		$args = array(
			"post_type" => "restaurant",
			"posts_per_page" => 5,
			"orderby" => "rand",
			"cat" => $cat_hier["parent"]->term_id,
			"post__not_in" => array($p),
		);
		$others = get_posts($args);
		$p_flg = true;
	endif;

	// 親エリアにも他に店がなければ近くの店舗はまるごと非表示
	if (count($others) > 0) :
?>
<aside class="rest__details__side">
<div class="rest__dateils__inner-sp--liquid">
<h1 class="rest__details__side__hdr"><?php echo ($p_flg) ? $cat_hier["parent"]->name : $cat->name; ?>の他のお店</h1>
</div>
<!-- <p class="rest__details__side__genre">イタリアン</p> -->

<div class="clearfix">
<?php
		// 同じエリアの他のお店表示ループ開始
		foreach ($others as $other) :
			$other_disp_term = get_disp_term($other->ID, "food_cat");
			$other_disp_charge = get_disp_charge($other->ID);
?>
<article class="rest__details__item">
<a href="<?php echo get_permalink($other->ID); ?>">
<p class="rest__details__item__img"><img src="<?php echo get_gochi_image($other->ID, "restaurant_thumbnail", "restaurant_thumb"); ?>" alt=""></p>
<div class="rest__details__item__txt-box">
<p class="rest__details__item__title"><?php echo esc_html(get_post_meta($other->ID, "restaurant_name", true)); ?></p>
<p class="rest__details__item__category"><span class="genre"><?php echo $other_disp_term; ?></span></p>
<p class="rest__details__item__price"><?php echo $other_disp_charge; ?></p>
</div>
</a>
<!-- /.rest__item --></article>
<?php
		// 同じエリアの他のお店表示ループ終了
		endforeach;
?>
<!-- /.clearfix --></div>

<!-- <ul class="rest__details__list__genre">
<li class="rest__details__list__genre__item"><a href="#">フレンチ（2）</a></li>
<li class="rest__details__list__genre__item"><a href="#">和食（1）</a></li>
<li class="rest__details__list__genre__item"><a href="#">その他（3）</a></li>
<li class="rest__details__list__genre__item"><a href="#">イタリアン　Italian　Italian（251）</a></li>
</ul> -->

</aside>
<?php
	// 同じエリアの他のお店がある場合の表示if終了
	endif;
?>
<!-- /.clearfix --></div>
<ol class="breadcrumb-list">
<li class="breadcrumb-list__item breadcrumb-list--anchor"><a href="/restaurant/tokyo/">レストラン検索トップ</a></li>
<li class="breadcrumb-list__item breadcrumb-list--current"><?php echo $rest_name; ?></li>
</ol>
<!-- /.rest__details__inner --></div>
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
