<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="ja"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="ja"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="ja"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="ja"> <!--<![endif]-->
<?php
	// ページに対応したパーマリンクを生成
	$cat = (isset($wp_query->query_vars["category_name"])) ? 
		get_category_by_slug($wp_query->query_vars["category_name"]) : null;
	$food_cat = (isset($wp_query->query_vars["food_cat"])) ? 
		$wp_query->query_vars["food_cat"] : null;
	$cat_hier = (!empty($cat)) ? get_category_hierarchy($cat->term_id, true) : null;
	if (empty($cat_hier)) :
		$permalink = get_the_permalink();
	else :
		$permalink = home_url("/restaurant/", "http") . $cat_hier["parent"]->slug ."/". $cat->slug ."/".
						$food_cat ."/". get_the_id() . "/";
	endif;
	
	// レポート記事のURLを取得
	// この店を関連店舗に選んだレポート記事を抽出
	$args = array(
		"post_type" => "report",
		"posts_per_page" => 1,
		"meta_key" => "report_target",
		"meta_value" => get_the_id(),
	);
	$reports = get_posts($args);

	// 関連するレポート記事が無い場合は設定しない
	$report_url = (count($reports) > 0) ? $permalink ."report/". $reports[0]->ID ."/" : null;
	if ( $report_url ) {
		$report_contents = get_post_meta( $reports[0]->ID, "report_content", false );
		if ( $report_contents ) {
			foreach ( $report_contents as $rc ) {
				$report_content .= $rc;
			}
		}
	}
	
	// メタ情報の取得（複数箇所で使用するため、まとめて取得）
	// タイトルは、入力があればそちらを活かし、なければ自動生成されるfunction get_rest_meta_title()を使用
	$title = get_rest_meta_title(get_the_id());
	$description = esc_html(get("restaurant_meta_description"));
	$keywords = esc_html(get("restaurant_meta_keywords"));
	
	// ジャンル表示、コース・料金表示などの繰り返し共通表示情報を取得
	$genre = get_disp_term();							// ジャンル
	$disp_course = get_disp_course(get_the_id());		// コース名称と料金
	$rest_name = esc_html(get("restaurant_name"));		// 店舗名
	$rest_ruby = esc_html(get("restaurant_name_ruby"));	// 店舗名カナ
	$rest_tel = get("restaurant_tel");					// 電話番号
	
	// 住所
	$rest_address = get("restaurant_address1").get("restaurant_address2");
	$org_address = $rest_address;
	if (get("restaurant_address3") <> "") : $rest_address .= " " . get("restaurant_address3"); endif;

	// googleマップ用qパラメータ
	// 緯度経度の入力があればそちらを活かし、なければ住所（ビル・マンション名を除く）をエンコード
	if (get("restaurant_latitude") <> "" && get("restaurant_longitude") <> "") :
		$q = get("restaurant_latitude") . "," . get("restaurant_longitude") . "+";
	else :
		$q = urlencode($org_address);
	endif;
?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<meta name="description" content="<?php echo $description; ?>">
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">

<meta property="og:title" content="<?php echo $title; ?>">
<meta property="og:description" content="<?php echo $description; ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php the_permalink(); ?>">
<meta property="og:image" content="<?php echo get_ogimage(get_the_id()); ?>">

<link rel="shortcut icon" type="image/x-icon" href="/assets/common/img/favicon.ico">
<link rel="canonical" href="<?php the_permalink(); ?>">

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
<h1 class="top--hdr rest__hdr"><img src="/assets/img/index/rest_hdr_l.png" data-src="/assets/img/index/rest_hdr_s.png" alt="RESTAURANT"><span>ごち会コースの楽しめるお店</span></h1>

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
<li class="rest__details__social__item rest__details__social__item--01"><a href="http://twitter.com/intent/tweet?text=<?php echo urlencode($title); ?>&amp;url=<?php the_permalink(); ?>" onclick="window.open(this.href, 'TWwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_tw_s.png" alt=""></a></li>
<li class="rest__details__social__item rest__details__social__item--02"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" onclick="window.open(this.href, 'FBwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_fb_s.png" alt=""></a></li>
</ul>
<!-- /.rest__details__sp-box --></div>
<!-- /sp -->

<div class="rest__details__sub-area clearfix">
<p class="rest__details__course">ごち会コース<span>：<?php echo $disp_course; ?></span></p>
<ul class="rest__details__social__list sp_hide clearfix">
<li class="rest__details__social__item rest__details__social__item--01"><a href="http://twitter.com/intent/tweet?text=<?php echo urlencode($title); ?>&url=<?php the_permalink(); ?>" onclick="window.open(this.href, 'TWwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_tw_l.png" alt=""></a></li>
<li class="rest__details__social__item rest__details__social__item--02"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" onclick="window.open(this.href, 'FBwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_fb_l.png" alt=""></a></li>
</ul>
<!-- /.rest__details__sub-area --></div>
</div>

<div class="clearfix">
<div class="rest__details__main" id="rest__details__tab">
<div class="sp_hide">
<?php
	// レポート記事がない場合は、「店舗情報」「レポート」のタブごと非表示
	if (!empty($report_url)) :
?>
<ul class="rest__details__main__tab rest__details__main__tab-01 clearfix">
<li class="rest__details__main__tab-01__item img_replace rest__details__main__tab-01__shop is-tab__btn is-active">店舗情報</li>
<li class="rest__details__main__tab-01__item img_replace rest__details__main__tab-01__report is-tab__btn"><a href="<?php echo $report_url; ?>">レポート</a></li>
<!-- /.rest__details__main__tab --></ul>
<?php
	// 「店舗情報」「レポート」タブ表示条件分岐終了
	endif;
?>
</div>
<!-- sp -->
<div class="sp_show">
<?php
	// レポート記事がない場合は、「店舗情報」「レポート」のタブごと非表示
	if (!empty($report_url)) :
?>
<ul class="rest__details__main__tab rest__details__main__tab-01 clearfix">
<li class="rest__details__main__tab-01__item rest__details__main__tab-01__shop is-tab__btn is-active"><img src="/assets/img/restaurant/rest_details_main_shop_shop.png" alt="店舗情報"></li>
<li class="rest__details__main__tab-01__item rest__details__main__tab-01__report is-tab__btn"><a href="<?php echo $report_url; ?>"><img src="/assets/img/restaurant/rest_details_main_shop_repo.png" alt="レポート"></a></li>
<!-- /.rest__details__main__tab --></ul>
<?php
	// 「店舗情報」「レポート」タブ表示条件分岐終了
	endif;
?>
</div>
<!-- /sp -->

<article class="rest__details__contents rest__details__shop">

<div class="rest__details__shop__slide__loading"></div>
<div class="rest__details__shop__slide">
<?php
	$images = get_restaurant_slider_images(get_the_id(), false);
	if (count($images) > 0) :
		foreach ($images as $image) :
?>
<article class="rest__details__shop__slide__item">
<p class="rest__details__shop__slide__list"><img src="<?php echo $image; ?>" alt=""></p>
</article>
<?php
		endforeach;
	endif;
?>
<!-- /.rest__details__shop__slide --></div>

<div class="rest__dateils__inner-sp--liquid">
<?php
	// 紹介文（見出しと内容）どちらも入力がなければdivタグごと表示しない
	if (get("restaurant_title") <> "" && get("restaurant_text") <> "") :
?>
<div class="rest__details__shop__summry">
<h1 class="rest__details__shop__summry__hdr"><?php echo esc_html(get("restaurant_title")); ?></h1>
<p class="rest__details__shop__summry__txt"><?php echo nl2br(esc_html(get("restaurant_text"))); ?></p>
<!-- /.rest__details__shop__summry --></div>
<?php
	// 紹介文ある場合の条件分岐終了
	endif;
?>

<?php
	// ごち会コースグループの取得
	$group = get_group("course_info");
	// コース情報がある場合のみ表示開始
	if (count($group) > 0) :
?>
<div class="rest__details__shop__course">
<h1 class="rest__details__topic__hdr">ごち会コース</h1>

<?php
	// コース内容の繰り返し開始
	foreach ($group as $each) :
		// 各項目がさらに配列で取得されているので、先頭の要素を抜き出しておく
		foreach ($each as $k => $v) :
			$each[$k] = array_shift($each[$k]);
		endforeach;
?>
<div class="rest__details__shop__course__inner">
<p class="rest__details__shop__course__type"><?php echo (isset($each["course_name"]) && $each["course_name"]<>"") ? esc_html($each["course_name"]."（".$each["course_charge"]."）") : esc_html($each["course_charge"]); ?></p>
<?php if (isset($each["course_terms"]) && $each["course_terms"]<>"") : ?><p class="rest__details__shop__course__requirement">※予約条件：<span><?php echo nl2br(esc_html($each["course_terms"])); ?></span></p><?php endif; ?>
<?php
		// 飲み物メニューがある場合のクラスを出力
		if ( isset( $each["course_drink"] ) && $each["course_drink"] <> "" ) :
?>
<div class="rest__details__shop__course__menu is-2column">
<h2 class="rest__details__shop__course__menu__hdr">MENU</h2>
<p class="rest__details__shop__course__menu__subhdr">お食事＋よく合うお飲み物</p>
<div class="rest__details__shop__course__menu__txtarea clearfix">
<div class="rest__details__shop__course__menu__txtarea__inner">
<h3 class="rest__details__shop__course__menu__txtarea__title"><span>お食事</span></h3>
<p class="rest__details__shop__course__menu__txtarea__txt"><?php echo (isset($each["course_detail"])) ? nl2br($each["course_detail"]) : null; ?></p>
<!-- /.rest__details__shop__course__menu__txtarea__inner --></div>

<div class="rest__details__shop__course__menu__txtarea__inner">
<h3 class="rest__details__shop__course__menu__txtarea__title"><span>お飲み物</span></h3>
<p class="rest__details__shop__course__menu__txtarea__txt"><?php echo nl2br(gochi_strip_fontsize($each["course_drink"])); ?></p>
<ul class="rest__details__shop__course__menu__txtarea__list">
<li>※上記以外のお飲み物は、お店でご確認ください。</li>
<li>※ごち会コースは、ソフトドリンクでも楽しめます。未成年飲酒は法律で禁止されていますので、ソフトドリンクをお楽しみください。</li>
<!-- /.rest__details__shop__course__menu__txtarea__list --></ul>
<!-- /.rest__details__shop__course__menu__txtarea__inner --></div>
<!-- /.rest__details__shop__course__menu__txtarea --></div>
<!-- /.rest__details__shop__course__menu --></div>
<!-- /.rest__details__shop__course__inner --></div>

<?php
		else:		// 飲み物メニューが無い場合は以下を出力
?>

<div class="rest__details__shop__course__menu">
<h2 class="rest__details__shop__course__menu__hdr">MENU</h2>
<p class="rest__details__shop__course__menu__subhdr">お食事</p>
<div class="rest__details__shop__course__menu__txtarea">
<div class="rest__details__shop__course__menu__txtarea__inner">
<p class="rest__details__shop__course__menu__txtarea__txt"><?php echo (isset($each["course_detail"])) ? nl2br($each["course_detail"]) : null; ?></p>
<!-- /.rest__details__shop__course__menu__txtarea__inner --></div>
<!-- /.rest__details__shop__course__menu__txtarea --></div>
<!-- /.rest__details__shop__course__menu --></div>
<!-- /.rest__details__shop__course --></div>

<?php
		endif;		// 飲み物メニューの有／無での分岐終了
	endforeach;		// コース情報繰り返し出力終了
?>
</div>
<?php
	// コース情報ありの分岐終了
	endif;
?>

<div class="rest__details__shop__info">
<h1 class="rest__details__topic__hdr">店舗詳細</h1>

<div class="rest__details__shop__info__list-area">
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">店舗名</dt>
<dd class="rest__details__shop__info__description rest__details__shop__info__description-shop"><?php echo $rest_name; ?>（<?php echo $rest_ruby; ?>）</dd>
</dl>
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">アクセス</dt>
<dd class="rest__details__shop__info__description"><?php echo (get("restaurant_access")<>"") ? nl2br(get("restaurant_access")) : "－"; ?></dd>
</dl>
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">住所</dt>
<dd class="rest__details__shop__info__description rest__details__shop__info__description-map"><?php echo (get("restaurant_zipcode") <> "") ? "〒".get("restaurant_zipcode")."<br>" : null; ?><?php echo $rest_address; ?><span class="sp_show-inline"><a href="http://maps.google.co.jp/maps?q=<?php echo $q; ?>" target="_blank" onclick="ga('send', 'event', 'map', 'click', 'img', 1, {'nonInteraction': 1});">（地図を見る）</a></span><br>
<iframe src="http://maps.google.co.jp/maps?q=<?php echo $q; ?>&amp;output=embed" frameborder="0" style="border:0" ></iframe></dd>
</dl>
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">電話番号</dt>
<dd class="rest__details__shop__info__description rest__details__shop__info__description-tel"><?php echo $rest_tel; ?><?php if (get("restaurant_tel_memo") <> "") : ?><br>
<span><?php echo nl2br(esc_html(get("restaurant_tel_memo"))); ?></span><?php endif; ?></dd>
</dl>
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">営業時間</dt>
<dd class="rest__details__shop__info__description"><?php echo (get("restaurant_hours") <> "") ? nl2br(esc_html(get("restaurant_hours"))) : "－"; ?></dd>
</dl>
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">席数</dt>
<dd class="rest__details__shop__info__description"><?php echo (get("restaurant_seats") <> "") ? esc_html(get("restaurant_seats")) : "－"; ?></dd>
</dl>
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">貸切</dt>
<dd class="rest__details__shop__info__description"><?php echo (get("restaurant_charter") <> "") ? esc_html(get("restaurant_charter")) : "－"; ?></dd>
</dl>
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">個室</dt>
<dd class="rest__details__shop__info__description"><?php echo (get("restaurant_private") <> "") ? esc_html(get("restaurant_private")) : "－"; ?></dd>
</dl>
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">たばこ</dt>
<dd class="rest__details__shop__info__description"><?php echo (get("restaurant_smoking") <> "") ? esc_html(get("restaurant_smoking")) : "－"; ?></dd>
</dl>
<dl class="rest__details__shop__info__list">
<dt class="rest__details__shop__info__term">HP</dt>
<?php if (check_url(get("restaurant_url"))) : ?>
<dd class="rest__details__shop__info__description"><a href="<?php echo esc_url(get("restaurant_url")); ?>" target="_blank"><?php echo esc_url(get("restaurant_url")); ?></a></dd>
<?php else: ?>
<dd class="rest__details__shop__info__description"><?php echo (get("restaurant_url")<>"") ? esc_html(get("restaurant_url")) : "－"; ?></dd>
<?php endif; ?>
</dl>
<!-- /.rest__details__shop__info__list --></div>
<div class="sp_show">
<!-- onclickの挿入 2018/04/09 -->
<p class="rest__details__shop__info__btn"><a href="http://maps.google.co.jp/maps?q=<?php echo $q; ?>" target="_blank" onclick="ga('send', 'event', 'map', 'click', 'button', 1, {'nonInteraction': 1});"><span>Google map!</span><img src="/assets/img/restaurant/rest_details_shop_info_map_btn.png" alt=""></a></p>
<p class="rest__details__shop__info__btn"><a href="tel:<?php echo $rest_tel; ?>" onclick="ga('send', 'event', 'tel', 'click', 'button',1, {'nonInteraction': 1});"><span><?php echo $rest_tel; ?></span><img src="/assets/img/restaurant/rest_details_shop_info_tel_btn.png" alt=""></a></p>
</div>
<!-- /.rest__details__shop__info --></div>
<?php
	if (!empty($report_url)) :		// レポート記事がある場合のみ以下を出力
?>
<div class="rest__details__shop__report">
	<h1 class="rest__details__topic__hdr">ごち会レポート</h1>
	<p class="rest__details__shop__report__txt"><?php echo mb_strimwidth($report_content, 0, 220, "...", "UTF-8"); ?><a href="<?php echo $report_url; ?>">read more</a></p>
<!-- /.rest__details__shop__report --></div>
</div>
<?php
	endif;		// レポート記事がある場合のみの出力終了
?>
</article>

<?php
	if (!empty($report_url)) :		// レポート記事がある場合のみ以下を出力
?>
<div class="sp_hide">
<ul class="rest__details__main__tab rest__details__main__tab-01 is-under clearfix">
<li class="rest__details__main__tab-01__item img_replace rest__details__main__tab-01__shop is-tab__btn is-active">店舗情報</li>
<li class="rest__details__main__tab-01__item img_replace rest__details__main__tab-01__report is-tab__btn"><a href="<?php echo $report_url; ?>">レポート</a></li>
<!-- /.rest__details__main__tab --></ul>
</div>
<!-- sp -->
<div class="sp_show">
<ul class="rest__details__main__tab rest__details__main__tab-01 is-under clearfix">
<li class="rest__details__main__tab-01__item rest__details__main__tab-01__shop is-tab__btn is-active"><img src="/assets/img/restaurant/rest_details_main_shop_shop_under.png" alt="店舗情報"></li>
<li class="rest__details__main__tab-01__item rest__details__main__tab-01__report is-tab__btn"><a href="<?php echo $report_url; ?>"><img src="/assets/img/restaurant/rest_details_main_shop_repo_under.png" alt="レポート"></a></li>
<!-- /.rest__details__main__tab --></ul>
</div>
<!-- /sp -->
<?php
	endif;		// レポート記事がある場合のみの出力終了
?>
<!-- /.rest__details__main --></div>

<?php
	$p_flg = false;
	// 同じエリアの他のお店をランダムに5件取得
	$args = array(
		"post_type" => "restaurant",
		"posts_per_page" => 5,
		"orderby" => "rand",
		"cat" => $cat->term_id,
		"post__not_in" => array(get_the_id()),
	);
	$others = get_posts($args);
	
	// 同じエリアに他に1件も無ければ、親エリアの店を取得
	if (count($others) <= 0) :
		$args = array(
			"post_type" => "restaurant",
			"posts_per_page" => 5,
			"orderby" => "rand",
			"cat" => $cat_hier["parent"]->term_id,
			"post__not_in" => array(get_the_id()),
		);
		$others = get_posts($args);
		$p_flg = true;
	endif;
	
	// 親エリアにも他に店がなければ、近くの店舗はまるごと非表示
	if (count($others) > 0) :
?>
<aside class="rest__details__side">
<div class="rest__dateils__inner-sp--liquid">
<h1 class="rest__details__side__hdr"><?php echo ($p_flg) ? $cat_hier["parent"]->name : $cat->name; ?>の他のお店</h1>
</div>

<div class="clearfix">
<?php
		// 同じエリアの他のお店表示ループ開始
		foreach ($others as $other) :
			$other_disp_term = get_disp_term($other->ID, "food_cat");
			$other_disp_charge = get_disp_charge($other->ID);
			$other_image = get_restaurant_slider_images($other->ID, true);
?>
<article class="rest__details__item">
<a href="<?php echo get_permalink($other->ID); ?>">
<p class="rest__details__item__img"><img src="<?php echo $other_image["s_image"]; ?>" alt=""></p>
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
<script src="/assets/common/js/vendor/jquery.bxslider/jquery.bxslider.min.js"></script>
<script src="/assets/common/js/base.js"></script>
<script src="/assets/js/restaurant_details.js"></script>

<?php
	include(DOC_ROOT . "/assets/include/analytics.inc");
?>

</body>
</html>
