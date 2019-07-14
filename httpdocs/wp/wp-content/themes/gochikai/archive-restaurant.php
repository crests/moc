<?php
	// フェーズ1の間はrestaurantトップなど、カテゴリーの指定がないリクエストは無効とする
	if (empty($wp_query->query_vars["category_name"])) {
		header("HTTP/1.0 404 Not Found");
		get_template_part("404");
		return;
	}
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="ja"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="ja"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="ja"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="ja"> <!--<![endif]-->
<?php
	// リクエストされたカテゴリー情報を取得
	$q_obj = get_queried_object();
	// ページに対応するカテゴリーの階層情報を取得（restaurant記事があるもののみ）
	$cat_hier = get_category_hierarchy($q_obj->term_id, true);

	// メタ情報用にこのページのURLを再現
	$permalink = home_url("/restaurant/", "http");
	if ( $cat_hier["attr"] === "grandchild" ) {
		$permalink .= $cat_hier["parent"]->slug ."/". $cat_hier["cat"]->slug ."/";
	} else {
		$permalink .= $cat_hier["cat"]->slug ."/";
	}

	// メタ情報を取得
	$meta = get_gochi_meta("restaurant");

	// ジャンルの指定があればタクソノミー情報を取得しつつ、$permalinkに追加
	if (isset($wp_query->query_vars["food_cat"]) && !empty($wp_query->query_vars["food_cat"])) {
		$food_cat = get_term_by("slug", $wp_query->query_vars["food_cat"], "food_cat");
		$permalink .= $food_cat->slug ."/";
	}
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
<meta property="og:url" content="<?php echo $permalink; ?>">
<meta property="og:image" content="<?php echo COMMON_OGP; ?>">

<link rel="shortcut icon" type="image/x-icon" href="/assets/common/img/favicon.ico">

<link rel="stylesheet" href="/assets/common/css/normalize.min.css">
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
<section class="section rest" id="js-heightAlign">
<div class="section__inner">
<h1 class="top--hdr rest__hdr"><img src="/assets/img/index/rest_hdr_l.png" data-src="/assets/img/index/rest_hdr_s.png" alt="RESTAURANT"></h1>
<?php
	// 全体の記事がある場合のみカテゴリー別に再抽出
	if (have_posts()) :

		// newマーク表示のための基準となる日付（現在より7日前）を取得
		$new_date = date("Ymd", strtotime("-7 day"));

		// 1階層下のカテゴリー情報をループに使用できるよう汎用的に設定
		$children = (empty($food_cat)) ? $cat_hier["children"] : array($food_cat);

		// 再抽出用共通設定項目
		$base_args = array(
			"post_type" => "restaurant",
			"posts_per_page" => -1,
		);

		// 料理ジャンルタクソノミーの指定がない場合のみアンカーリンクを出力
		if (empty($food_cat)) :
?>
<div class="rest__anchor">
<ul class="rest__anchor__list clearfix">
<?php
			// アンカーリンクの出力ループ開始（1階層下のカテゴリー情報ループ）
			foreach ($children as $child) :

				// 自分が孫の場合、料理ジャンルタクソノミーはそのカテゴリーで記事が存在するとは限らないので、
				// 条件を絞り込んで記事を抽出し、記事がある場合のみアンカーリンクを表示する
				if ($cat_hier["attr"] === "grandchild") :

					// 追加条件：カテゴリー＆料理ジャンルタクソノミー別で再抽出
					$add_args = array(
						"cat" => $cat_hier["cat"]->term_id,
						"tax_query" => array(
							array(
								"taxonomy" => "food_cat",
								"terms" => $child->term_id,
							),
						),
					);

					// 追加条件を追加して記事を抽出
					$args = $base_args;
					$args += $add_args;
					$anc_posts = get_posts($args);

				else :
					// 自分が孫以外の場合は条件を満たすようにダミーで配列をセット
					$anc_posts = array(1);
				endif;

				// 孫の場合、記事がある場合のみアンカーリンクのリストを出力
				if (count($anc_posts) > 0) :
?>
<li class="rest__acchor__item"><a href="#rest__list--<?php echo $child->slug; ?>"><span><?php echo $child->name; ?></span></a></li>
<?php
				endif;					// 記事がある場合のみのアンカーリンク出力分岐終了
			endforeach;					// アンカーリンク出力ループ終了
?>
</ul>
</div>
<?php
		endif;		// アンカーリンクの出力分岐終了

		// 1階層下のカテゴリー情報ループ開始
		// 自分が孫の場合は料理ジャンルを下階層とし、さらに料理ジャンルまで指定があれば絞り込んで表示
		foreach ($children as $child) :

			// 料理ジャンルタクソノミーまで指定がある場合、記事の再抽出は実行せず、もとの抽出結果を使用する
			if (empty($food_cat)) :

				// 自分が孫の場合、$childには料理ジャンルカテゴリーが設定されているので、
				// それに対応した追加条件を設定
				if ($cat_hier["attr"] === "grandchild") :

					// 追加条件：カテゴリー＆料理ジャンルタクソノミー別に再抽出
					$add_args = array(
						"cat" => $cat_hier["cat"]->term_id,
						"tax_query" => array(
							array(
								"taxonomy" => "food_cat",
								"terms" => $child->term_id,
							),
						),
					);

				// 自分が孫以外（＝子を想定）の場合は、
				// $child別に店舗情報を抽出する追加条件を設定
				else:

					// 追加条件：カテゴリー別に店舗情報を再抽出
					$add_args = array(
						"cat" => $child->term_id,
					);

				endif;

				// 追加条件を追加して記事を抽出
				$args = $base_args;
				$args += $add_args;
				$wp_query->query($args);

			// 記事の再抽出終了
			endif;

			// 再抽出した結果も踏まえ、レストラン記事がある場合のみ表示開始
			if (have_posts()) :
?>

<div class="rest__list" id="rest__list--<?php echo $child->slug; ?>">
<h1 class="rest__list__title"><?php echo (!empty($food_cat)) ? $cat_hier["cat"]->name."　" : null; echo $child->name; ?></h1>
<div class="rest__item-box clearfix">
<?php
			// 料理ジャンル毎に記事を並べるため、料理ジャンルでループして絞り込み再抽出
			$terms = ($cat_hier["attr"] === "grandchild") ? array($child) : get_terms("food_cat", array("orderby" => "t.order"));
			$not_in = array();

			// カテゴリー内料理ジャンル毎のループ開始
			foreach ($terms as $term) :

				// 自分が孫以外（子を想定）の場合、下階層カテゴリーと料理ジャンルで
				// 絞り込む追加条件を設定
				if ($cat_hier["attr"] !== "grandchild") :

					$add_args = array(
						"cat" => $child->term_id,
						"post__not_in" => $not_in,
						"tax_query" => array(
							array(
								"taxonomy" => "food_cat",
								"terms" => $term->term_id,
							),
						),
					);

					// 基本条件に追加条件をプラス
					$args = $base_args;
					$args += $add_args;

					// 追加条件を設定して再抽出
					$wp_query->query($args);

				// 自分が孫の場合は、既に抽出されている記事でOKなので何もしない
				endif;

				// レストラン記事ループ開始
				while (have_posts()) : the_post();

					// 料理ジャンルを複数持つ場合に、１つのカテゴリー内で同じ記事が重複しないよう
					// １度掲載した記事のIDを配列に追加していく（別カテゴリーでは重複表示でOK）
					$not_in[] = get_the_id();

					// newマーク表示用のclassを設定
					$new_class = ( strtotime(get_the_time("Ymd")) > strtotime($new_date)) ? " top--rest__item--new" : null;

					// 料理ジャンルタクソノミーを取得
					$post_term = get_the_terms(get_the_id(), "food_cat");
					$post_term = $post_term[0];

					// パーマリンクを生成（カテゴリー複数の場合のバリエーションに対応）
					$post_url = home_url("/restaurant/", "http");
					$post_url .= ($cat_hier["attr"] === "grandchild") ? $cat_hier["parent"]->slug ."/". $cat_hier["cat"]->slug ."/". $child->slug ."/" :
							$cat_hier["cat"]->slug ."/". $child->slug ."/". $post_term->slug ."/";

					$post_url .= get_the_id() ."/";

					// メイン画像（PC・タブレット用、スマホ用）を取得
					$images =get_restaurant_slider_images(get_the_id(), true);

					// 表示用料理ジャンルタクソノミー情報を取得
					$disp_term = get_disp_term();

					// 表示用コース料金を取得
					$disp_charge = get_disp_charge(get_the_id());
?>

<article class="top--rest__item<?php echo $new_class; ?>">
<div class="r-mask"></div>
<a href="<?php echo $post_url; ?>">
<div class="clearfix">
<!-- PC image-->
<p class="top--rest__item__img sp_hide"><span><img src="<?php echo $images["l_image"]; ?>" alt=""></span></p>
<!-- SP image -->
<p class="top--rest__item__img sp_show"><span><img src="<?php echo $images["s_image"]; ?>" alt=""></span></p>
<div class="top--rest__item__txt-box">
<p class="top--rest__item__title"><?php echo esc_html(get("restaurant_name")); ?></p>
<p class="top--rest__item__sub-title"><?php echo esc_html(get("restaurant_name_ruby")); ?></p>
<p class="top--rest__item__category"><span class="genre"><?php echo $disp_term; ?></span></p>
<p class="top--rest__item__price"><?php echo $disp_charge; ?></p>
</div>
</div>
</a>
<!-- /.rest__item --></article>
<?php
				// レストラン記事ループ終了
				endwhile;
			// カテゴリー内料理ジャンル毎のループ終了
			endforeach;
?>

<!-- /.rest__item-box --></div>
<!-- /.rest__list --></div>
<?php
			// レストラン記事がある場合のみの表示終了
			endif;
		// 1階層下のカテゴリーループ終了
		endforeach;
		wp_reset_postdata(); wp_reset_query();

	// 全体の記事がゼロの場合の結果表示（仮）
	else:
		echo "<p>該当店舗がありません。</p>";
	endif;
?>

<!-- /.section__inner --></div>
</section>

</main>

<?php
	include(DOC_ROOT."/assets/include/footer.inc");
?>

<!-- /.content --></div>

<script src="/assets/common/js/vendor/jquery-1.11.2.min.js"></script>
<script src="/assets/common/js/base.js"></script>
<script src="/assets/js/restaurant.js"></script>

<?php
	include(DOC_ROOT."/assets/include/analytics.inc");
?>

</body>
</html>
