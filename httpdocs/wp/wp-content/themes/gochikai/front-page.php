<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="ja"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="ja"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="ja"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="ja"> <!--<![endif]-->

<?php
	// メタ情報を取得
	$meta = get_gochi_meta("top");
?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $meta["title"]; ?></title>
<meta name="description" content="<?php echo $meta["description"]; ?>">
<meta name="keywords" content="<?php echo $meta["keywords"]; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta property="og:title" content="<?php echo $meta["title"]; ?>">
<meta property="og:description" content="<?php echo $meta["description"]; ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo home_url("/", "http"); ?>">
<meta property="og:image" content="<?php echo COMMON_OGP; ?>">

<link rel="shortcut icon" type="image/x-icon" href="/assets/common/img/favicon.ico">

<link rel="stylesheet" href="/assets/common/css/normalize.min.css">
<!-- <link rel="stylesheet" href="/assets/common/js/vendor/jquery.bxslider/jquery.bxslider.css"> -->
<link rel="stylesheet" href="/assets/common/css/main.css">
<script src="/assets/common/js/vendor/modernizr-2.8.3.min.js"></script>
<!--[if lt IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv-printshiv.js"></script>
<![endif]-->

</head>
<body class="top__body">

<div id="sitetop" class="content">

<?php
	include(DOC_ROOT . "/assets/include/header.inc");
?>

<main class="main top">
<!-- スライダー -->
<!-- <div class="top--cover is-slideFull">
<div class="slide_all is-sliderFull" id="slide_all">
<div class="slide_wrap is-sliderFull" id="slide_wrap">
<div class="coverSlider　sp-slides" id="coverSlider">
<article class="sp-slide">
<p class="top--cover__slider__list"><img src="/assets/img/index/cover_slider_img_01_l.jpg" data-src="/assets/img/index/cover_slider_img_01_s.jpg" alt="" ></p>
</article>
<article class="sp-slide">
<p class="top--cover__slider__list"><img src="/assets/img/index/cover_slider_img_02_l.jpg" data-src="/assets/img/index/cover_slider_img_02_s.jpg" alt="" ></p>
</article>
<article class="sp-slide">
<p class="top--cover__slider__list"><img src="/assets/img/index/cover_slider_img_03_l.jpg" data-src="/assets/img/index/cover_slider_img_03_s.jpg" alt="" ></p>
</article>
</div>
</div>
</div>
</div> -->
<!-- /スライダー -->

<section class="top--cover-2" id="top--cover-2">
<div class="top--cover-2__bg">
<div class="top--cover-2__inner">
<div class="js-panel-08 top--cover-2__item top--cover-2__item--box" id="cover__item--01"></div>
<div class="js-panel-10 top--cover-2__item" id="cover__item--02"><img src="assets/img/index/cover_img_01.jpg" alt=""></div>
<div class="js-panel-08 top--cover-2__item top--cover-2__item--box" id="cover__item--03"></div>
<div class="js-panel-10 top--cover-2__item" id="cover__item--04"><img src="assets/img/index/cover_img_02.jpg" alt=""></div>
<div class="js-panel-10 top--cover-2__item" id="cover__item--05"><img src="assets/img/index/cover_img_03.jpg" alt=""></div>
<div class="js-panel-09 top--cover-2__item" id="cover__item--06"><img src="assets/img/index/cover_img_04.jpg" alt=""></div>
<div class="js-panel-08 top--cover-2__item top--cover-2__item--box" id="cover__item--07"></div>
<div class="js-panel-08 top--cover-2__item top--cover-2__item--box" id="cover__item--08"></div>
<div class="js-panel-08 top--cover-2__item" id="cover__item--09"><img src="assets/img/index/cover_img_05.jpg" alt=""></div>
<div class="js-panel-05 top--cover-2__item" id="cover__item--10"><img src="assets/img/index/cover_img_06.jpg" alt=""></div>
<div class="js-panel-06 top--cover-2__item" id="cover__item--11"><img src="assets/img/index/cover_img_07.jpg" alt=""></div>
<div class="js-panel-09 top--cover-2__item" id="cover__item--12"><img src="assets/img/index/cover_img_08.jpg" alt=""></div>
<div class="js-panel-04 top--cover-2__item top--cover-2__item--box" id="cover__item--13"></div>
<div class="js-panel-05 top--cover-2__item top--cover-2__item--box" id="cover__item--14"></div>
<div class="js-panel-04 top--cover-2__item" id="cover__item--15"><img src="assets/img/index/cover_img_09.jpg" alt=""></div>
<div class="js-panel-02 top--cover-2__item" id="cover__item--16"><img src="assets/img/index/cover_img_10.jpg" alt=""></div>
<div class="js-panel-07 top--cover-2__item" id="cover__item--17"><img src="assets/img/index/cover_img_11.jpg" alt=""></div>
<div class="js-panel-03 top--cover-2__item" id="cover__item--18"><img src="assets/img/index/cover_img_12.jpg" alt=""></div>
<div class="js-panel-06 top--cover-2__item top--cover-2__item--box" id="cover__item--19"></div>
<div class="js-panel-05 top--cover-2__item top--cover-2__item--box" id="cover__item--20"></div>
<div class="js-panel-05 top--cover-2__item" id="cover__item--21"><img src="assets/img/index/cover_img_13.jpg" alt=""></div>
<div class="js-panel-01 top--cover-2__item" id="cover__item--22"><img src="assets/img/index/cover_img_14.jpg" alt=""></div>
<div class="js-panel-07 top--cover-2__item top--cover-2__item--box" id="cover__item--23"></div>
<div class="js-panel-04 top--cover-2__item top--cover-2__item--box" id="cover__item--24"></div>
<div class="js-panel-04 top--cover-2__item" id="cover__item--25"><img src="assets/img/index/cover_img_15.jpg" alt=""></div>
<div class="js-panel-08 top--cover-2__item" id="cover__item--26"><img src="assets/img/index/cover_img_16.jpg" alt=""></div>
<div class="js-panel-06 top--cover-2__item" id="cover__item--27"><img src="assets/img/index/cover_img_17.jpg" alt=""></div>
<div class="js-panel-05 top--cover-2__item" id="cover__item--28"><img src="assets/img/index/cover_img_18.jpg" alt=""></div>
<div class="js-panel-03 top--cover-2__item" id="cover__item--29"><img src="assets/img/index/cover_img_19.jpg" alt=""></div>
<div class="js-panel-07 top--cover-2__item top--cover-2__item--box" id="cover__item--30"></div>
<div class="js-panel-07 top--cover-2__item" id="cover__item--31"><img src="assets/img/index/cover_img_20.jpg" alt=""></div>
<div class="js-panel-09 top--cover-2__item" id="cover__item--32"><img src="assets/img/index/cover_img_21.jpg" alt=""></div>
<div class="js-panel-10 top--cover-2__item" id="cover__item--33"><img src="assets/img/index/cover_img_22.jpg" alt=""></div>
<div class="js-panel-08 top--cover-2__item top--cover-2__item--box" id="cover__item--34"></div>
<div class="js-panel-10 top--cover-2__item" id="cover__item--35"><img src="assets/img/index/cover_img_23.jpg" alt=""></div>
<div class="js-panel-08 top--cover-2__item top--cover-2__item--box" id="cover__item--36"></div>
<div class="js-panel-10 top--cover-2__item" id="cover__item--37"><img src="assets/img/index/cover_img_24.jpg" alt=""></div>
<div class="js-panel-08 top--cover-2__item top--cover-2__item--box" id="cover__item--38"></div>
<div class="top--cover-2__logo img_replace">ごちそう会</div>
<!-- /.top--cover-2__inner --></div>
<p class="top--cover-2__logo tb-show img_replace">飲み放題より満足できる、ごち会を開こう！ ごち会</p>
<!-- /.top--cover-2__bg --></div>
</section>

<section class="section top--concept" id="top--concept">
<div class="top__inner">
<h1 class="top--hdr top--concept__hdr"><img src="/assets/img/index/concept_hdr_l.png" data-src="/assets/img/index/concept_hdr_s.png" alt="CONCEPT"><span>ごち会のコンセプト</span></h1>
<p class="top--consept__title" id="top--consept__title"><span id="js-txt-01">また飲み放題？</span><br class="sp--line-break"><span class="span" id="js-txt-02">同じ金額を払うならごち会！</span></p>
<div class="top--concept__img-box">
<p class="top--concept__img" id="top--concept__img--01"></p>
<p class="top--concept__img" id="top--concept__img--02"></p>
<p class="top--concept__img" id="top--concept__img--03"></p>
</div>
<div class="top--concept__txt-box">
<p class="top--concept__txt">サークルやクラスの飲み会と言えば、<br class="sp--line-break">いつも決まって飲み放題コース。<br>
同じ金額を払うなら、<br class="sp--line-break">美味しい食事をしっかり食べたい。<br>
お酒は、いいものを大切に味わいたい。<br>
そんな大学生の想いから生まれた<br class="sp--line-break"><em>「ごちそう＋飲み物2杯」</em>の<br class="sp--line-break">美味しい選択肢、<br>
それが<em>「ごち会」</em>です。</p>

<!-- youtube movie (2018/04/09 added) -->
<!-- 後でスタイルシートに移したいが、どこ？ -->
<div style="width:90%; max-width:700px; margin:0 auto 24px auto;">
<div style="width:100%; height:0; padding-bottom:56.25%; position:relative;">
<iframe width="100%" height="100%" style="position:absolute; top:0;left:0;" src="https://www.youtube.com/embed/qLFz-QIC6s0?rel=0&amp;showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
</div>
</div>

<p class="section__btn top--concept__btn img_replace"><a href="/concept/"><span>詳しくはこちら</span></a></p>
</div>
<!-- /.top__inner --></div>
</section>

<section class="section top--rest" id="js-heightAlign">
<div class="top__inner">
<div class="top--rest__inner">
<h1 class="top--hdr top--rest__hdr"><img src="/assets/img/index/rest_hdr_l.png" data-src="/assets/img/index/rest_hdr_s.png" alt="RESTAURANT"><span>ごち会コースの楽しめるお店</span></h1>
<div class="top--rest__list clearfix" id="top--rest__section">
<div class="top--rest__item-box clearfix">
<?php
	// レストラン抽出条件：ランダムに4件抽出
	$args = array(
		"post_type" => "restaurant",
		"posts_per_page" => 4,
		"orderby" => "rand",
	);

	$restaurants = get_posts($args);

	// newマーク表示のための基準となる日付（現在より7日前）を取得
	$new_date = date("Ymd", strtotime("-7 day"));

	// 記事が抽出できた場合のみレストラン出力開始
	if (count($restaurants) > 0) :
		foreach ($restaurants as $rest) :

			// newマーク表示用のclassを設定
			$new_class = (strtotime(get_the_time("Ymd", $rest->ID)) > strtotime($new_date)) ? " top--rest__item--new" : null;

			// 表示用料理ジャンルを取得
			$disp_term = get_disp_term($rest->ID, "food_cat");

			// 表示用コース料金を取得
			$disp_charge = get_disp_charge($rest->ID);

			// メイン画像（PC・タブレット用、スマホ用）を取得
			$images = get_restaurant_slider_images($rest->ID, true);
?>
<article class="top--rest__item<?php echo $new_class; ?>">
<div class="r-mask"></div>
<a href="<?php echo get_the_permalink($rest->ID); ?>">
<div class="clearfix">
<!-- PC image-->
<p class="top--rest__item__img sp_hide"><span><img src="<?php echo $images["l_image"]; ?>" alt=""></span></p>
<!-- SP image -->
<p class="top--rest__item__img sp_show"><span><img src="<?php echo $images["s_image"]; ?>" alt=""></span></p>
<div class="top--rest__item__txt-box">
<p class="top--rest__item__title"><?php echo esc_html(get_post_meta($rest->ID, "restaurant_name", true)); ?></p>
<p class="top--rest__item__sub-title"><?php echo esc_html(get_post_meta($rest->ID, "restaurant_name_ruby", true)); ?></p>
<p class="top--rest__item__category"><span class="genre"><?php echo $disp_term; ?></span></p>
<p class="top--rest__item__price"><?php echo $disp_charge; ?></p>
</div>
</div>
</a>
<!-- /.top--rest__item --></article>

<?php
		// レストラン出力終了
		endforeach;
	endif;
?>
<!-- .top--rest__item-box --></div>
<!-- /.top--rest__list --></div>
<p class="section__btn top--rest__btn img_replace"><a href="/restaurant/tokyo/"><span>レストラン検索はこちら</span></a></p>
<!-- /.top--rest__inner --></div>
<!-- /.top__inner --></div>
</section>

<style>
	/*
<section class="section top--special">
<div class="top__inner">
<h1 class="top--hdr top--special__hdr"><img src="/assets/img/index/special_hdr_l.png" data-src="/assets/img/index/special_hdr_s.png" alt="SPECIAL 特集"></h1>
<div class="top--special__list clearfix">
<article class="top--special__item">
<a href="/restaurant/tokyo/area/category/00/">
<p class="top--special__item__img"><img src="/assets/img/index/special_item_img_01.png" alt=""></p>
<div class="top--special__item__txt-box">
<p class="top--special__item__point top--special__item__point--01 img_replace">SPECIAL 01</p>
<p class="top--special__item__title"><span>男子ご飯 特集</span></p>
<p class="top--special__item__txt">お肉とワインの相性を楽しもう！</p>
</div>
</a>
</article>

<article class="top--special__item">
<a href="#">
<p class="top--special__item__img"><img src="/assets/img/index/special_item_img_02.png" alt=""></p>
<div class="top--special__item__txt-box">
<p class="top--special__item__point top--special__item__point--02 img_replace">SPECIAL 02</p>
<p class="top--special__item__title"><span>プレミアムコース 特集</span></p>
<p class="top--special__item__txt">フレンチとワインで乾杯！</p>
</div>
</a>
</article>

<article class="top--special__item">
<a href="#">
<p class="top--special__item__img"><img src="/assets/img/index/special_item_img_03.png" alt=""></p>
<div class="top--special__item__txt-box">
<p class="top--special__item__point top--special__item__point--03 img_replace">SPECIAL 03</p>
<p class="top--special__item__title"><span>誕生日、記念日 特集</span></p>
<p class="top--special__item__txt">大切な時間をジャンパンと</p>
</div>
</a>
</article>
<!-- /.top--special__list --></div>

<p class="section__btn top--special__btn"><a href="/restaurant/tokyo/"><img src="/assets/img/index/special_btn_l.png" data-src="/assets/img/index/special_btn_s.png" alt="特集一覧はこちら" class="rollover"></a></p>

<!-- /.top__inner --></div>
</section>
*/
</style>

<section class="section top--enjoy">
<div class="top__inner">
<h1 class="top--hdr top--enjoy__hdr"><img src="/assets/img/index/enjoy_hdr_l.png" alt="ENJOYMENT" data-src="/assets/img/index/enjoy_hdr_s.png"><span>ごちそうを楽しむために</span></h1>

<div class="top--enjoy__list">
<?php
	$args = array(
		"post_type" => "enjoyment",
		"posts_per_page" => 3,
	);
	$enjoyments = get_posts($args);

	// enjoyment記事がある場合のみ出力開始
	if (count($enjoyments) > 0) :
		foreach ($enjoyments as $enj) :

			// メイン画像、enjカテゴリー情報の取得
			$thumb = get_gochi_image($enj->ID, "enjoyment_image", "full");
			$enj_term = get_the_terms($enj->ID, "enj_cat");
			$enj_term = $enj_term[0];

?>
<article class="top--enjoy__item">
<a href="<?php echo get_the_permalink($enj->ID); ?>">
<div class="r-mask-02"></div>
<p class="top--enjoy__item__img"><img src="<?php echo $thumb; ?>" data-src="<?php echo $thumb; ?>" alt=""></p>
<div class="top--enjoy__item__txt-box">
<p class="top--enjoy__item__category"><?php echo $enj_term->name; ?></p><time datetime="<?php echo get_the_time("Y.m.d", $enj->ID); ?>" class="top--enjoy__item__time"><?php echo get_the_time("Y.m.d", $enj->ID); ?></time>
<p class="top--enjoy__item__title"><?php echo esc_html(get_post_meta($enj->ID, "enjoyment_title", true)); ?></p>
<p class="top--enjoy__item__txt"><?php echo esc_html(get_post_meta($enj->ID, "enjoyment_lead", true)); ?></p>
</div>
</a>
</article>

<?php
	// enjoyment記事がある場合の出力終了
		endforeach;
	endif;
?>
<!-- /.top--enjoy__list --></div>
<p class="section__btn top--enjoy__btn img_replace"><a href="/enjoyment/"><span>ENJOYMENT一覧はこちら</span></a></p>
</div>
</section>

<section class="section top--about" id="top--about">
<div class="top__inner">
<h1 class="top--hdr top--about__hdr"><img src="/assets/img/index/about_hdr_l.png" data-src="/assets/img/index/about_hdr_s.png" alt="ABOUT US"><span>私たちについて</span></h1>
<div class="top--about__paper" id="top--about__paper--left"></div>
<div class="top--about__paper" id="top--about__paper--right"></div>
<p class="top--about__txt"><em>「ごち会」</em>は<br>
「ごちそう＋飲み物2杯の<br class="sp--line-break">美味しい選択肢」を提案する<br>
学生団体<em>「想食系幹事」</em><br class="sp--line-break">が推進するプロジェクトです。</p>

<p class="section__btn top--about__btn img_replace"><a href="/about/"><span>詳しくはこちら</span></a></p>
</div>
</section>

<section class="section top--news">
<div class="top__inner">
<h1 class="top--news__hdr"><img src="/assets/img/index/news_hdr_l.png" data-src="/assets/img/index/news_hdr_s.png" alt="NEWS"></h1>
<div class="top--news__list">

<?php
	$args = array(
		"post_type" => "news",
		"posts_per_page" => 4,
	);
	$news = get_posts($args);

	// news記事がある場合のみ出力開始
	if (count($news) > 0) :
		foreach ($news as $new) :

			// ニュースカテゴリー情報を取得
			$news_term = get_the_terms($new->ID, "news_cat");
			$news_term = $news_term[0];

			// 外部リンク指定時のclassなどをせってお
			if (get_post_meta($new->ID, "news_external", true) == 1) :
				$blank = ' target="_blank"';
				$class = ' class="link__target-blank"';
			else:
				$blank = null; $class = null;
			endif;

			// URL指定の有無flg（aタグを出力するかどうかの判定）
			$f_url = (get_post_meta($new->ID, "news_url", true) <> "") ? true : false;
?>
<article class="top--news__item">
<?php
	// URL指定がある場合のみ<a>タグを出力
	if ($f_url) :
?>
<a href="<?php echo get_post_meta($new->ID, "news_url", true); ?>"<?php echo $blank; ?>>
<?php
	// URL指定がなければ<span>タグを出力
	else: echo "<span>";
	endif;
?>
<p class="top--news__item__tag"><img src="/assets/img/index/news_tag_<?php echo $news_term->slug; ?>_l.png" data-src="/assets/img/index/news_tag_<?php echo $news_term->slug; ?>_s.png" alt="<?php echo $news_term->name; ?>"></p>
<time datetime="<?php echo get_the_time("Y.m.d", $new->ID); ?>" class="top--news__item__time"><?php echo get_the_time("Y.m.d", $new->ID); ?></time>
<p class="top--news__item__txt"><span<?php echo $class; ?>><?php echo esc_html(get_post_meta($new->ID, "news_text", true)); ?></span></p>
<?php
	// URL指定がある場合のみ</a>を出力
	if ($f_url) :
?>
</a>
<?php
	// URL指定がなければ</span>を出力
	else: echo "</span>";
	endif;
?>
</article>

<?php
	// news記事がある場合の出力終了
		endforeach;
	endif;
?>
<!-- /.top--news__list --></div>
<div class="clearfix">
<p class="top--news__btn"><a href="/news/"><img src="/assets/img/index/news_btn_l.png" data-src="/assets/img/index/news_btn_s.png" alt="NEWS一覧はこちら"></a></p>
</div>
<!-- /.top__inner --></div>
</section>

</main>

<?php
	include(DOC_ROOT . "/assets/include/footer.inc");
?>

<!-- /.content --></div>

<script src="/assets/common/js/vendor/jquery-1.11.2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<!-- <script src="/assets/common/js/vendor/jquery.bxslider/jquery.bxslider.min.js"></script> -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/1.17.0/TweenMax.min.js"></script>
<script src="/assets/common/js/base.js"></script>
<script src="/assets/js/index.js"></script>

<?php
	include(DOC_ROOT . "/assets/include/analytics.inc");
?>

</body>
</html>
