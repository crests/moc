<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="ja"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="ja"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="ja"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="ja"> <!--<![endif]-->
<?php
	// メタ情報を取得
	$meta = get_gochi_meta("enjoyment");
	// 記事タイトルを取得し、取得したメタタイトルと組み合わせる
	$title = get("enjoyment_title");
	$meta["title"] = $title . $meta["title"];
?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $meta["title"]; ?></title>
<meta name="description" content="<?php echo $meta["description"]; ?>">
<meta name="keywords" content="<?php echo $meta["keywords"]; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">

<meta property="og:title" content="<?php echo $meta["title"]; ?>">
<meta property="og:description" content="<?php echo $meta["description"]; ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php the_permalink(); ?>">
<meta property="og:image" content="<?php echo get_ogimage(get_the_id(), "enjoyment"); ?>">

<link rel="shortcut icon" type="image/x-icon" href="/assets/common/img/favicon.ico">

<link rel="stylesheet" href="/assets/common/css/normalize.min.css">
<link rel="stylesheet" href="/assets/common/js/vendor/jquery.bxslider/jquery.bxslider.css">
<link rel="stylesheet" href="/assets/common/css/main.css">
<script src="/assets/common/js/vendor/modernizr-2.8.3.min.js"></script>
<!--[if lt IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv-printshiv.js"></script>
<![endif]-->

</head>
<body class="enjoy__body">

<div id="sitetop" class="content">

<?php
	include(DOC_ROOT . "/assets/include/header.inc");
?>

<?php
	// enjoymentカテゴリーの取得
	$term = get_the_terms(get_the_id(), "enj_cat");
	$term = $term[0];
?>
<main class="main">
<section class="section top--enjoy">
<div class="section__inner">
<h1 class="top--hdr top--enjoy__hdr"><img src="/assets/img/index/enjoy_hdr_l.png" alt="ENJOYMENT" data-src="/assets/img/index/enjoy_hdr_s.png"><span>ごちそうを楽しむために</span></h1>
<div class="enjoy__inner">
<div class="enjoy__article__title-area">
<p class="enjoy__article__tag"><?php echo $term->name; ?></p>
<h1 class="enjoy__article__title"><?php echo esc_html($title); ?></h1>
<!-- /.enjoy__title-area --></div>

<article class="enjoy__article">
<div class="clearfix">
<time class="enjoy__article__time" datetime="<?php echo get_the_time("Y.m.d"); ?>"><?php echo get_the_time("Y.m.d"); ?></time>
<ul class="enjoy__article__social clearfix">
<li class="enjoy__article__social__list"><a href="http://twitter.com/intent/tweet?text=<?php echo urlencode($meta["title"]); ?>&url=<?php the_permalink(); ?>" onclick="window.open(this.href, 'TWwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_tw_l.png" data-src="/assets/common/img/hdr_sns_tw_s.png"></a></li>
<li class="enjoy__article__social__list"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" onclick="window.open(this.href, 'FBwindow', 'width=650, height=450, menubar=no, toolbar=no, scrollbars=yes'); return false;"><img src="/assets/common/img/hdr_sns_fb_l.png" data-src="/assets/common/img/hdr_sns_fb_s.png"></a></li>
</ul>
<!-- /.clearfix --></div>

<!-- .enjoy__article--over-img 画像横幅100%
.enjoy__article--reverse 画像記事反転 -->
<?php
	// メイン画像の取得
	$picture = get_gochi_image_class(get_the_id(), "enjoyment_image", "full", true);
?>
<div class="enjoy__article__box clearfix<?php echo $picture["class"]; ?>">
<?php
	// サブタイトルがある場合のみ表示
	if (get("enjoyment_subtitle") <> "") :
?>
<h1 class="enjoy__article__sub-title"><?php echo esc_html(get("enjoyment_subtitle")); ?></h1>
<?php
	endif;		// サブタイトルありの場合の表示分岐終了
?>
<p class="enjoy__article__img"><img src="<?php echo $picture["url"]; ?>" alt=""></p>
<p class="enjoy__article__txt"><?php echo nl2br(get("enjoyment_content")); ?></p>
<!-- /.clearfix --></div>
<?php
	$group = get_group("enjoyment_add_article");
	$g_cnt = count($group);
	
	// 追加記事がある場合のみ以下を表示
	if ($g_cnt > 0) :
		// 追加記事があった場合は、画像全部とそれに対応するクラスを取得
		$pictures = get_gochi_image_class(get_the_id(), "enjoyment_add_image", "full", false);
		$i = 0;

		// 追加記事の繰り返し開始
		foreach ($group as $each) :
			// 各項目がさらに配列で構成されているので先頭要素を一旦全部抜き出しておく
			foreach ($each as &$v) :
				$v = array_shift($v);
			endforeach;
?>

<div class="enjoy__article__box clearfix<?php echo $pictures[$i]["class"]; ?>">
<?php
	if (isset($each["enjoyment_add_title"]) && $each["enjoyment_add_title"] <> "") :		// 追加記事の見出しがある場合のみ以下を出力
?>
<h1 class="enjoy__article__sub-title"><?php echo esc_html($each["enjoyment_add_title"]); ?></h1>
<?php
	endif;											// 追加記事の見出しがある場合のみの出力終了
	if (!empty($pictures[$i]["url"])) :				// 追加画像がある場合のみ以下を出力
?>
<p class="enjoy__article__img"><img src="<?php echo $pictures[$i]["url"]; ?>" alt=""></p>
<?php
	endif;											// 追加画像がある場合のみの出力終了
?>
<p class="enjoy__article__txt"><?php echo (isset($each["enjoyment_add_content"])) ? nl2br($each["enjoyment_add_content"]) : null; ?></p>
<!-- /.clearfix --></div>
<?php
		$i++;				// 画像・クラス表示用カウンターの追加
		endforeach;			// 追加記事の繰り返し終了
	endif;					// 追加記事があった場合のみの表示終了
?>

</article>
<!-- /.enjoy__inner --></div>
<!-- /.section__inner --></div>

<?php
	// 同じカテゴリの記事を抽出
	$args = array(
		"post_type" => "enjoyment",
		"posts_per_page" => 3,
		"post__not_in" => array(get_the_id()),
		"tax_query" => array(
			array(
				"taxonomy" => "enj_cat",
				"terms" => $term->term_id,
				"field" => "ID",
			),
		),
	);
	$other_posts = get_posts($args);
	
	// 同じカテゴリの記事がある場合のみ以下を表示
	if (count($other_posts) > 0) :
?>
<aside class="enjoy__other-artcile">
<div class="enjoy__other-artcile__inner">
<h1 class="enjoy__other-artcile__hdr">同じカテゴリの記事</h1>

<div class="clearfix">
<?php
		// 同じカテゴリの記事ループ開始
		foreach ($other_posts as $other) :
			$other_thumb = get_gochi_image($other->ID, "enjoyment_image", "restaurant_thumb", true);
?>
<article class="enjoy__other-artcile__item">
<a href="<?php echo get_the_permalink($other->ID); ?>">
<p class="enjoy__other-article__item__img"><img src="<?php echo $other_thumb; ?>" alt=""></p>
<div class="enjoy__other-artcile__txt-area">
<p class="enjoy__other-artcile__tag"><?php echo $term->name; ?></p>
<p class="enjoy__other-artcile__title"><?php echo esc_html(get_post_meta($other->ID, "enjoyment_title", true)); ?></p>
<time class="enjoy__other-artcile__time" datetime="<?php echo date("Y.m.d", strtotime($other->post_date)); ?>"><?php echo date("Y.m.d", strtotime($other->post_date)); ?></time>
</div>
</a>
</article>

<?php
		endforeach;
?>
<!-- /.clearfix --></div>
<!-- /.enjoy__other-artcile__inner --></div>
</aside>
<?php
	endif;		// 同じカテゴリの記事がある場合のみの表示終了
?>

<p class="section__btn top--enjoy__btn img_replace"><a href="/enjoyment/"><span>ENJOYMENT一覧はこちら</span></a></p>
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
