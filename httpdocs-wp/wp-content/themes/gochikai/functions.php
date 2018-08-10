<?php
/**
 * gochikai functions and definitions
 */

// 管理画面での表示制限を解除するユーザのIDをセット（＝代表管理者）
define ('GOCHI_ADMIN_ID', 1);

// 編集可能な投稿タイプを取得
function gochi_manage_post_types( $username, $role ) {

	// 管理者：サイト開発者用
	if ( $role == 'administrator' ) {
		$post_types = array(
			'restaurant',
			'report',
			'enjoyment',
			'news',
			'meta',
		);

	// 編集者：学生ユーザー様
	} elseif ( $username == 'sousyoku' ) {
		$post_types = array(
			'restaurant',
			'report',
			'enjoyment',
			'news',
		);

	// 編集者：prj様
	} else {
		$post_types = array(
			'enjoyment',
		);
	}

	return $post_types;
}

// 編集不可能な投稿タイプを取得
function gochi_unmanage_post_types( $username, $role ) {

	$post_types = array();

	// 学生ユーザー様
	if ( $username == 'sousyoku' ) {
		$post_types = array(
			'meta',
		);

	// 管理者以外（prj様）
	} elseif ( $role !== 'administrator' ) {
		$post_types = array(
			'restaurant',
			'report',
			'news',
			'meta',
		);
	}
	return $post_types;
}

// 更新通知を非表示
add_filter( 'pre_site_transient_update_core', '__return_zero' );
remove_action( 'wp_version_check', 'wp_version_check' );
remove_action( 'admin_init', '_maybe_update_core' );


// 管理バーから不要な項目を非表示
add_action('admin_bar_menu', 'gochi_remove_bar_menus', 201);
function gochi_remove_bar_menus( $wp_admin_bar ) {

	global $current_user;
	get_currentuserinfo();

	// 代表管理者以外のユーザーの場合、管理バー（画面上部）より以下のメニューを非表示
	if ($current_user->ID != GOCHI_ADMIN_ID) {

		$wp_admin_bar->remove_menu('updates');		// 更新
		$wp_admin_bar->remove_menu('comments');		// コメント

		// 「新規」内メニューより以下を非表示
		$wp_admin_bar->remove_menu('new-page');		// 固定ページ
		$wp_admin_bar->remove_menu('new-post');		// 投稿

	}

	// 管理者以外の場合は、さらに使用不許可の投稿タイプも非表示
	if (!current_user_can('administrator')) {

		// 対象ユーザーが使用できない投稿タイプを全取得
		$post_types = gochi_unmanage_post_types( $current_user->user_login, $current_user->roles[0] );

		// 取得したメニューをすべて非表示
		if (count($post_types) > 0) {
			foreach ($post_types as $post_type) {
				$wp_admin_bar->remove_menu('new-'.$post_type);
			}
		}

		// プロフィール編集を非表示
		$wp_admin_bar->remove_menu('edit-profile');
	}
}

// 代表管理者以外の場合、サイドメニューから不要なものを非表示
add_action('admin_menu', 'gochi_remove_menu');
function gochi_remove_menu() {

	global $menu;
	global $current_user;
	get_currentuserinfo();
	$admin_auth = array();

	// 表示できるメニュー（共通）
	$common_auth = array(
		"ダッシュボード",
		"メディア",
		"Enjoyments",
		"Special",
		"ユーザー",			// 管理者以外では権限上非表示となる
	);

	// 代表管理者以外の場合
	if ($current_user->ID != GOCHI_ADMIN_ID) {

		// 自分が使用できる投稿タイプを配列で取得
		$post_types = gochi_manage_post_types( $current_user->user_login, $current_user->roles[0] );

		// 取得した投稿タイプのラベル名で自分用ホワイトリスト配列を作成
		if ( count( $post_types ) > 0 ) {
			foreach ( $post_types as $post_type ) {
				$args = array(
					'name' => $post_type,
				);
				$obj = get_post_types( $args, 'objects' );
				if ( ! empty( $obj ) ) {
					$admin_auth[] = $obj[$post_type]->labels->name;
				}
			}
		}

		// 共通のホワイトリストと自分用のホワイトリストをマージ
		$auth = array_merge( $common_auth, $admin_auth );

		// メニューを全チェックし、許可された権限とセパレータ以外はメニューから除去
		foreach ($menu as $num => $each) {
			if (!empty($each[0])) {
				if (!in_array($each[0], $auth, true)) {
					unset($menu[intval($num)]);
				}
			}
		}

		// ダッシュボード内の「ホーム」「更新」サブメニューを非表示
		remove_submenu_page('index.php', 'update-core.php');

	// 代表管理者はすべてのメニューを表示
	}
}

// ダッシュボードウィジェット非表示
add_action('wp_dashboard_setup', 'gochi_remove_dashboard_widgets');
function gochi_remove_dashboard_widgets() {

	global $menu;
	global $current_user;
	get_currentuserinfo();

	global $wp_meta_boxes;

	// クイックドラフトは全員無効（ダッシュボードを開くたびにポストIDが繰り上がってしまうのを避ける）
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // クイック投稿

	// 代表管理者以外のユーザーではダッシュボードのウィジェットを非表示
	if ($current_user->ID != GOCHI_ADMIN_ID) {

		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // 現在の状況
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 最近のコメント
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // 被リンク
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // プラグイン
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // 最近の下書き
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPressブログ
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPressフォーラム
	}
}

// カスタム投稿（シングルページ）のパーマリンク設定
add_filter('post_type_link', 'my_post_type_link', 1, 2);
function my_post_type_link($link, $post) {

	// restaurant投稿のパーマリンク
	// カテゴリーの親子孫のうち、子孫のみの階層で表現（親階層の表示をなくす）
	if ($post->post_type === 'restaurant') {

		$category = get_the_category($post->ID);

		// カテゴリー未設定時はそのまま返す
		if (count($category) < 1) {
			return $link;
		}

		$parent = get_category($category[0]->parent);
		$term = get_the_terms($post->ID, 'food_cat');

		// 料理ジャンルタクソノミー未設定時はそのまま返す
		if (is_wp_error($term) || empty($term)) {
			return $link;
		}

		return home_url('/restaurant/'.$parent->slug.'/'.$category[0]->slug.'/'.$term[0]->slug.'/'.$post->ID.'/');

	// report投稿のパーマリンク
	} elseif ($post->post_type === 'report') {

		// 対象店舗として登録されたrestaurant投稿のIDを取得（紐づくrestaurant投稿の配下のようなURLにする）
		$t_id = get_post_meta($post->ID, 'report_target', true);
		$category = get_the_category($t_id);

		// カテゴリー未設定時はそのまま返す
		if (count($category) < 1) {
			return $link;
		}

		$parent = get_category($category[0]->parent);
		$term = get_the_terms($t_id, 'food_cat');

		// 料理ジャンルタクソノミー未設定時はそのまま返す
		if (is_wp_error($term) || empty($term)) {
			return $link;
		}

		return home_url('/restaurant/'.$parent->slug.'/'.$category[0]->slug.'/'.$term[0]->slug.'/'.$t_id.'/report/'.$post->ID.'/');

	// enjoyment投稿のパーマリンク
	} elseif ($post->post_type === 'enjoyment') {

		// カテゴリー名は変更の可能性があるため、単一ページのURLにはカテゴリー名は含めない
		return home_url('/enjoyment/'.$post->ID.'/');

	// news投稿のパーマリンク
	} elseif ($post->post_type === 'news') {

		$term = get_the_terms($post->ID, 'news_cat');

		if (is_wp_error($term) || empty($term)) {
			return $link;
		}

		// ページは存在しないため、こちらが呼ばれた場合は404リダイレクトさせる
		return home_url('/news/'.$term[0]->slug.'/'.$post->ID.'/');

	// meta投稿のパーマリンク
	} elseif ($post->post_type === 'meta') {

		// ページは存在しないため、こちらが呼ばれた場合は404リダイレクトさせる
		return home_url('/meta/'.$post->ID.'/');

	} else {
		return $link;
	}
}

// カスタム投稿（シングルページ）のリライトルール追加
add_filter('rewrite_rules_array', 'my_rewrite_rules_array');
function my_rewrite_rules_array($rules) {

	// restaurant/%category%（子孫のみ）/%food_cat%/%post_id%/のリライトルール
	$new_rules1 = array(
		'restaurant/(?!food)(?!page)([^/]+)/(?!page)([^/]+)/(?!page)([^/]+)/([0-9]+)/?$' =>
				'index.php?post_type=restaurant&category_name=$matches[2]&food_cat=$matches[3]&p=$matches[4]',
	);

	// report/%post_id%/のリライトルール
	$new_rules2 = array(
		'restaurant/(?!food)(?!page)([^/]+)/(?!page)([^/]+)/(?!page)([^/]+)/([0-9]+)/report/([0-9]+)/?$' =>
				'index.php?post_type=report&p=$matches[5]',
	);

	// enjoyment/%post_id%/のリライトルール
	$new_rules3 = array(
		'enjoyment/([0-9]+)/?$' =>
				'index.php?post_type=enjoyment&p=$matches[1]',
	);

	// news/%news_cat%/%post_id%/のリライトルール（基本的に表示させないがパーマリンクは設定）
	$new_rules4 = array('news/(?!page)([^/]+)/([0-9]+)/?$' => 'index.php?post_type=news&news_cat=$matches[1]&p=$matches[2]');

	// meta/%post_id%/のリライトルール（基本的に表示させないがパーマリンクは設定）
	$new_rules5 = array('meta/([0-9]+)/?$' => 'index.php?post_type=meta&p=$matches[1]');

	return $new_rules1 + $new_rules2 + $new_rules3 + $new_rules4 + $new_rules5 + $rules;

}

// リライトルール追加

// ①レストラン一覧：エリア中分類－エリア小分類－ジャンル
// restaurant/子カテゴリー（ex:tokyo）/孫カテゴリー（ex:areatky001）/food_cat（ex:italian）/を想定
add_rewrite_rule('restaurant/(?!food)(?!page)([^/]+)/(?!page)([^/]+)/(?!page)([^/]+)/?$',
		'index.php?post_type=restaurant&category_name=$matches[2]&food_cat=$matches[3]', 'top');

// 上記①のページング対応
add_rewrite_rule('restaurant/(?!food)(?!page)([^/]+)/(?!page)([^/]+)/(?!page)([^/]+)/page/([0-9]+)/?$',
		'index.php?post_type=restaurant&category_name=$matches[2]&food_cat=$matches[3]&paged=$matches[4]', 'top');

// ②レストラン一覧：エリア中分類－エリア小分類
// restaurant/子カテゴリー（ex:tokyo）/孫カテゴリー（ex:areatky001）/を想定
add_rewrite_rule('restaurant/(?!food)([^/]+)/(?!page)([^/]+)/?$',
		'index.php?post_type=restaurant&category_name=$matches[2]', 'top');

// 上記②のページング対応
add_rewrite_rule('restaurant/(?!food)([^/]+)/(?!page)([^/]+)/page/([0-9]+)/?$',
		'index.php?post_type=restaurant&category_name=$matches[2]&paged=$matches[3]', 'top');

// ③レストラン一覧：エリア中分類
// restaurant/子カテゴリー（ex:tokyo）/を想定
add_rewrite_rule('restaurant/(?!food)(?!page)([^/]+)/?$',
		'index.php?post_type=restaurant&category_name=$matches[1]', 'top');

// 上記③のページング対応
add_rewrite_rule('restaurant/(?!food)(?!page)([^/]+)/page/([0-9]+)/?$',
		'index.php?post_type=restaurant&category_name=$matches[1]&paged=$matches[2]', 'top');

// ④レストラン一覧：ジャンル
// restaurant/food/food_cat（ex:italian）/を想定
add_rewrite_rule('restaurant/food/(?!page)([^/]+)/?$',
		'index.php?post_type=restaurant&food_cat=$matches[1]', 'top');

// 上記④のページング対応
add_rewrite_rule('restaurant/food/(?!page)([^/]+)/page/([0-9]+)/?$',
		'index.php?post_type=restaurant&food_cat=$matches[1]&paged=$matches[2]', 'top');

// ⑤レストラン一覧：ジャンル－エリア中分類
// restaurant/food/food_cat（ex:italian）/子カテゴリー（ex:tokyo）/を想定
add_rewrite_rule('restaurant/food/(?!page)([^/]+)/(?!page)([^/]+)/?$',
		'index.php?post_type=restaurant&food_cat=$matches[1]&category_name=$matches[2]', 'top');

// 上記⑤のページング対応
add_rewrite_rule('restaurant/food/(?!page)([^/]+)/(?!page)([^/]+)/page/([0-9]+)/?$',
		'index.php?post_type=restaurant&food_cat=$matches[1]&category_name=$matches[2]&paged=$matches[3]', 'top');

// ⑥ごちそうを楽しむ一覧ページング対応：Enjoyment+page
// enjoyment/page/paged（ex:1）/を想定
add_rewrite_rule('enjoyment/page/([0-9]+)/?$',
		'index.php?post_type=enjoyment&paged=$matches[1]', 'top');

// ⑦ごちそうを楽しむ一覧：Enjoyment－enjカテゴリー
// enjoyment/enj_cat/を想定
add_rewrite_rule('enjoyment/(?!page)([^/]+)/?$', 'index.php?post_type=enjoyment&enj_cat=$matches[1]', 'top');

// 上記⑦のページング対応
add_rewrite_rule('enjoyment/(?!page)([^/]+)/page/([0-9]+)/?$',
		'index.php?post_type=enjoyment&enj_cat=$matches[1]&paged=$matches[2]', 'top');

// ⑧news一覧ページング対応：News+page
add_rewrite_rule('news/page/([0-9]+)/?$', 'index.php?post_type=news&paged=$matches[1]', 'top');

// ⑨news一覧：news－newsカテゴリー
// news/news_cat/を想定
add_rewrite_rule('news/(?!page)([^/]+)/?$', 'index.php?post_type=news&news_cat=$matches[1]', 'top');

// 上記⑨のページング対応
add_rewrite_rule('news/(?!page)([^/]+)/page/([0-9]+)/?$',
		'index.php?post_type=news&news_cat=$matches[1]&paged=$matches[2]', 'top');

// 記事抽出カスタマイズ
add_action('pre_get_posts', 'custom_archive_pre_get_posts');
function custom_archive_pre_get_posts($query) {

	// 管理画面の抽出カスタマイズ
	if (is_admin()) {

		// AJAXレスポンス処理中でのカスタマイズ
		if (defined('DOING_AJAX') && DOING_AJAX) {

			// ビジュアルエディタ内リンクの編集ダイアログで表示される「既存のコンテンツにリンク」の
			// リストでは、meta投稿、news投稿を除外する（restaurant、report、enjoymentのみ表示）
			if (isset($_POST["action"]) && $_POST["action"] == "wp-link-ajax") {
				$query->set('post_type', array(
											'restaurant',
											'enjoyment',
											'report',
											)
							);
			}

		}

	// 管理画面以外の抽出カスタマイズ
	} else {

		// メインループ以外の場合は終了（メインループ以外は都度個別に設定）
		if (!$query->is_main_query()) {
			return;
		}

		// restaurant投稿アーカイブページのカスタマイズ
		if (is_post_type_archive('restaurant')) {
			$query->set('posts_per_page', -1);

		}

		// enjoyment投稿アーカイブページのカスタマイズ
		if (is_post_type_archive('enjoyment')) {
			$query->set('posts_per_page', 10);
		}

		// news投稿アーカイブページのカスタマイズ
		if (is_post_type_archive('news')) {
			$query->set('posts_per_page', 10);
		}

	}
}

// <<---管理画面「投稿」「固定ページ」画面カスタマイズ（誤使用防止）--- start---
add_action('admin_init', 'my_admin_init');
function my_admin_init() {

	global $menu;
	global $current_user;
	global $pagenow;
	get_currentuserinfo();

	// 管理者以外はプロフィールページにアクセスした場合、管理画面トップへリダイレクト
	if ($pagenow == 'profile.php' && !current_user_can('administrator')) {
		wp_redirect('index.php');
		exit;
	}

	// 「編集者」権限から一律、カテゴリー編集の権限を削除
	$role = get_role('editor');
	$role->remove_cap('manage_categories');

	// 代表管理者以外の場合は表示制限
	if ($current_user->ID != GOCHI_ADMIN_ID) {

		// 投稿ページの誤使用防止
		remove_post_type_support('post', 'editor');					// テキスト・ビジュアルエディター
		remove_post_type_support('post', 'title');					// 投稿タイトル
		remove_post_type_support('post', 'thumbnail');				// サムネイル

		// 投稿ページメタボックスの除去
		remove_meta_box('submitdiv', 'post', 'normal');				// 公開ボックス
		remove_meta_box('categorydiv', 'post', 'normal');			// カテゴリーボックス
		remove_meta_box('tagsdiv-post_tag', 'post', 'normal');		// タグボックス

		// オリジナルメタボックスの追加
		add_meta_box("message", "この画面は使用しないでください","my_admin_warning","post","normal","high");

		// 固定ページの誤操作防止
		remove_post_type_support('page', 'editor');					// テキスト・ビジュアルエディター
		remove_post_type_support('page', 'thumbnail');				// サムネイル

		// 固定ページの誤使用防止
		remove_post_type_support('page', 'title');				// タイトル
		remove_post_type_support('page', 'page-attributes');	// ページ属性

		// 固定ページメタボックスの除去
		remove_meta_box('submitdiv', 'page', 'normal');		// 公開ボックス

		// オリジナルメタボックスの追加
		add_meta_box("message", "この画面は使用しないでください","my_admin_warning","page","normal","high");

		$post_types = gochi_unmanage_post_types( $current_user->user_login, $current_user->roles[0] );

		// カスタムタクソノミーだけを配列で取得
		$args = array(
			'public' => true,
			'_builtin' => false,
		);
		$taxonomies = get_taxonomies($args);

		// 編集不可の投稿タイプすべてについて以下を実行
		if (count($post_types) > 0) {
			foreach ($post_types as $post_type) {

				// 投稿画面のメタボックス除去
				remove_meta_box('submitdiv', $post_type, 'normal');			// 公開ボックス
				remove_meta_box('categorydiv', $post_type, 'normal');		// カテゴリーボックス

				// カスタムタクソノミーメタボックスをすべて除去
				if (count($taxonomies) > 0) {
					foreach ($taxonomies as $tax) {
						remove_meta_box($tax.'div', $post_type, 'normal');
					}
				}

				// オリジナルメタボックスの追加
				add_meta_box("message", "この画面は使用しないでください",
									"my_admin_warning", $post_type, "normal", "high");

			}
		}

	}

}

// 追加オリジナルメタボックス
function my_admin_warning() {
	echo "この画面は使用できません";
}
// --- 管理画面「投稿」「固定ページ」画面カスタマイズ（誤使用防止）--- end --->>>

// <<<---管理画面カテゴリチェックボックスカスタマイズ Start---
// 管理画面：親カテゴリのチェックボックスは非表示にする
require_once(ABSPATH . '/wp-admin/includes/template.php');
class My_Category_Checklist extends Walker_Category_Checklist {

     function start_el( &$output, $category, $depth = 0, $args = Array(), $id = 0 ) {
        extract($args);
        if ( empty($taxonomy) )
            $taxonomy = 'category';

        if ( $taxonomy == 'category' )
            $name = 'post_category';
        else
            $name = 'tax_input['.$taxonomy.']';

        $class = in_array( $category->term_id, $popular_cats ) ? ' class="popular-category"' : '';

        global $post_type;

        // カテゴリー種類がカテゴリーで、親カテゴリーの場合
        if($category->parent == 0 && $category->taxonomy == 'category'){

        	// 未分類は表示しない
        	if ($category->name == "未分類") {

        	// 非表示分以外はラベルのみ表示
        	} else {

				// チェックボックスを表示しない（ラベルのみ）
				$output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" .
					'<label class="selectit">' . esc_html( apply_filters('the_category', $category->name )) . '</label>';
            }

		// カテゴリー種類がカテゴリーで、子孫カテゴリーの場合や、その他のカテゴリー種類の場合
        }else{

			// カテゴリー種類がカテゴリーで、子カテゴリーの場合はチェックボックス表示しない
			// 自分の親カテゴリーにさらに親がいないことで子カテゴリーと判断
			$parent = get_category($category->parent);
			if (!is_wp_error($parent) && $parent->parent == 0 && $category->taxonomy == 'category') {

               $output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" .
               			'<label class="selectit">' . esc_html( apply_filters('the_category', $category->name )) . '</label>';

            // 孫カテゴリの場合と、その他のカテゴリーの場合はチェックボックス表示
            } else {

	            $output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" .
	            		'<label class="selectit"><input value="' . $category->term_id .
	            		'" type="checkbox" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' .
	            		checked( in_array( $category->term_id, $selected_cats ), true, false ) .
	            		disabled( empty( $args['disabled'] ), false, false ) . ' /> ' .
	            		esc_html( apply_filters('the_category', $category->name )) . '</label>';
			}

        }

    }

}

// カテゴリーを開閉式にする（下階層がある場合のみ開閉可能な▼を表示）
add_action('admin_head', 'my_category_toggle') ;
function my_category_toggle() {
?>
<script type="text/javascript">
<!--
    jQuery(function() {
        jQuery( '.categorydiv div.tabs-panel' ).css({'max-height':'100%'});
        jQuery( '#categorychecklist  li').each(function(){
            if( jQuery( this ).children('ul').hasClass( 'children' ) ){
            jQuery( this ).children( 'label' ).after('<span class="toggle-switch"> ▼ </span>&nbsp;').css({'cursor':'default'});
            }else{
            jQuery( this ).children( 'label' ).after('<span></span>&nbsp;');

            }
        });
        jQuery('#categorychecklist li > .children').hide();
        jQuery('.toggle-switch').click(function () {
            jQuery(this).siblings('ul').toggle("slow");
        });
     });
//-->
</script>
<?php
}

// 管理画面：選択されたカテゴリーがリストのトップに出ないようにする（元の並び順を維持する）
// カテゴリー種類がカテゴリーの場合、孫カテゴリ以外のチェックボックスは非表示にする
function my_wp_category_terms_checklist_no_top( $args, $post_id = null ) {

    $args['checked_ontop'] = false;
    $args['walker'] = new My_Category_Checklist();
    return $args;
}
add_action( 'wp_terms_checklist_args', 'my_wp_category_terms_checklist_no_top' );

// MagicFieldsの投稿タイプ別のグループ情報を取得
// 管理画面の非表示ループ時に使用
function gochi_get_group($post_type) {

	global $table_prefix;
	global $wpdb;
	$grp_tbl = $table_prefix ."mf_custom_groups";

	$sql = "SELECT * FROM ".$grp_tbl." WHERE post_type='".$post_type."'";
	$res = $wpdb->get_results($sql);

	return $res;

}

// 管理画面：「＋新規カテゴリーを追加」を非表示にする
// 管理画面：一覧ページのクイック編集からカテゴリーの選択を非表示にする
function hide_category_add() {

	global $pagenow;
	global $post_type;		//投稿タイプで切り分けたいときに使う
	global $current_user;
	get_currentuserinfo();

	// カスタムタクソノミーだけを配列で取得
	$args = array(
		'public' => true,
		'_builtin' => false,
	);
	$taxonomies = get_taxonomies($args);

	// 編集できない投稿タイプを配列で取得
	$admin_post_types = gochi_unmanage_post_types( $current_user->user_login, $current_user->roles[0] );

	// 管理バーの更新ボタンを非表示
	echo '<style type="text/css">.update-plugins,update-count,#contextual-help-link-wrap,li#wp-admin-bar-updates {display:none !important;}</style>';

	// 各種記事編集・新規作成画面
	if ($pagenow=='post-new.php' || $pagenow=='post.php'){

		// 共通：カテゴリー、カスタムタクソノミーの新規追加を非表示
		echo '<style type="text/css">
			#category-adder{display:none;}';

		// すべてのカスタムタクソノミーの新規追加を非表示
		if (count($taxonomies) > 0) {
			foreach ($taxonomies as $tax) {
				echo '#'.$tax.'-adder{display:none;}';
			}
		}

		// パスワード保護ラジオボタンとラベルの非表示
		echo '#visibility-radio-password{display:none;}';
		echo 'label[for="visibility-radio-password"]{display:none;}';
		echo '</style>';

		// 代表管理者以外の場合
		if ($current_user->ID != GOCHI_ADMIN_ID) {

			// 通常の投稿か固定ページの場合
			if ($post_type == 'post' || $post_type == 'page') {

				// 表示オプション、ヘルプ、サイドの点線枠非表示（投稿画面を非表示にして無効化する）
				echo '<style type="text/css">
					#screen-options-link-wrap{display:none;}
					#contextual-help-link-wrap{display:none;}
					#side-sortables{display:none;}
					</style>';

			// 編集できない投稿タイプの場合
			} elseif ( in_array( $post_type, $admin_post_types, true ) ) {

				// Add-newボタン、表示オプション、ヘルプ、サイドの点線枠非表示（投稿画面を非表示にして無効化する）
				echo '<style type="text/css">
					.add-new-h2{display:none;}
					#screen-options-link-wrap{display:none;}
					#contextual-help-link-wrap{display:none;}
					#side-sortables{display:none;}';

					// カスタムフィールドのグループの情報（消したいpostboxの情報）を取得し、
					// カスタムフィールドのpostboxをすべて非表示
					$groups = gochi_get_group($post_type);
					if (count($groups) > 0) {
						foreach ($groups as $group) {
							echo '#mf_'.$group->id.'{display:none;}';
						}
					}

				echo '</style>';

			// 管理者または、許可された投稿画面の場合
			} else {

				// パーマリンク編集、「表示オプション」の「表示する項目」を非表示
				echo '<style type="text/css">
				#edit-slug-box{display:none;}
				.metabox-prefs{display:none;}';

				// 管理者以外の場合はVisual、HTMLの切り替えボタンを非表示
				if (!current_user_can('administrator')) {
					echo '.tab_multi_mf{display:none;}';
				}

				echo '</style>';

			}

		}

	// 各種記事一覧画面：
	} elseif ($pagenow=='edit.php'){

		// クイック編集のカテゴリー選択のすべてと、パスワード保護は非表示
		echo '<style type="text/css">
		.inline-edit-categories{display:none;}
		div.inline-edit-group label.alignleft span.input-text-wrap{display:none;}
		div.inline-edit-group label.alignleft span.title{display:none;}
		div.inline-edit-group .inline-edit-or{display:none;}
		</style>';

		// 代表管理者以外の場合
		if ($current_user->ID != GOCHI_ADMIN_ID) {

			// 管理者以外で管理者専用の投稿タイプの場合か、管理者でも管理者以外でも通常の投稿か固定ページの場合
			if ( ( in_array( $post_type, $admin_post_types, true ) ) || ($post_type == 'post' || $post_type == 'page')) {

				// 記事一覧のフォームボックス、「表示オプション」と「ヘルプ」タブ、
				// 「新規追加」ボタン、「すべて」「公開済み」などの絞り込みリンク非表示
				echo '<style type="text/css">
				#posts-filter{display:none;}
				#screen-options-link-wrap{display:none;}
				#contextual-help-link-wrap{display:none;}
				.add-new-h2{display:none;}
				.subsubsub{display:none;}
				</style>';

			}

		}

	}

}
add_action( 'admin_head', 'hide_category_add'  );

// クイック編集の「パスワード」と一緒に非表示処理される「ステータス」ラベルだけを再度表示
add_action('admin_footer', 'gochi_admin_footer', 999);
function gochi_admin_footer() {

	echo '<script type="text/javascript">
			jQuery("div.inline-edit-group label.inline-edit-status span.title").after("<span>ステータス</span>");
	</script>';

}

// 管理画面：カテゴリーボックスの高さを自動調整する
function post_output_css() {

	global $pagenow;
	global $post_type;

	// カスタムタクソノミーのみをオブジェクトで抽出
	$args = array(
		"public" => true,
		"_builtin" => false,
	);
	$taxonomies = get_taxonomies($args, "objects");

	// 投稿画面及び新規投稿画面の場合のみ
	if ($pagenow == 'post-new.php' || $pagenow == 'post.php') {

		// 共通：カテゴリーメタボックスの高さ指定を解除
		echo '<style type="text/css">
			#categorydiv{height:auto;}
			#category-all{max-height:none; height:auto;}
			#category-pop{max-height:none; height:auto;}';

		// 各投稿タイプに関連付けされたカスタムタクソノミーのメタボックス高さ指定を解除
		foreach ($taxonomies as $tax) {
			if (in_array($post_type, $tax->object_type, true)) {
				echo '#'.$tax->name.'div{height:auto;}';
				echo '#'.$tax->name.'-all{max-height:none; height:auto;}';
				echo '#'.$tax->name.'-pop{max-height:none; height:auto;}';
			}
		}

		echo '</style>'."\n";

	}

}
add_action('admin_head', 'post_output_css');

// カテゴリー＆カスタムタクソノミーの並び順
// プラグインで設定した並び順を管理画面やその他関数でも使用
function my_get_terms_orderby($orderby) {

	// 管理画面だけ違う並びにしたい場合はis_admin()で切り分ける
	$orderby = "t.term_order";
	return $orderby;
}
add_filter('get_terms_orderby', 'my_get_terms_orderby', 10 );


// スクリーンレイアウトの列の数を「1」で固定にする
// enjoyment投稿（管理者以外にも解放しているもの）
function gochi_screen_layout($result, $option, $user) {
	return 1;
}
add_filter('get_user_option_screen_layout_enjoyment', 'gochi_screen_layout', 10, 3);


// 一覧表示で幅が小さくなった場合、カスタマイズで追加した列を表示させないCSSを追加読み込み
function gochi_admin_style() {
	wp_enqueue_style('gochi_admin_style', get_stylesheet_directory_uri() . '/gochi_style.css');
}
add_action('admin_enqueue_scripts', 'gochi_admin_style');

// ---管理画面カテゴリチェックボックスカスタマイズ End --->>>

//bootstrapの読み込み
function mybootstrap_enqueue_styles() {
    wp_register_style('bootstrap', get_template_directory_uri() . '/bootstrap.min.css' );
    $dependencies = array('bootstrap');
    wp_enqueue_style( 'mybootstrap-style', get_stylesheet_uri(), $dependencies );
}

function mybootstrap_enqueue_scripts() {
    $dependencies = array('jquery');
    wp_enqueue_script('bootstrap', get_template_directory_uri().'/bootstrap.min.js', $dependencies, '3.3.6', true ); // trueとしてbodyタグのクロージングの前で読み込むように設定
}
//add_action( 'wp_enqueue_scripts', 'mybootstrap_enqueue_styles' );
//add_action( 'wp_enqueue_scripts', 'mybootstrap_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'mybootstrap_enqueue_styles' );
add_action( 'admin_enqueue_scripts', 'mybootstrap_enqueue_scripts' );
//bootstrapの読み込み end---->


// 与えられた文字列の改行コードをLFに統一
function convert_lf($string="") {

	$string = str_replace("\r", "\n", str_replace("\r\n", "\n", $string));
	return $string;

}

// ポストタイプrestaurantの場合は記事タイトルをカスタムフィールドの「店舗名」にする
// ポストタイプreportの場合は記事タイトルをカスタムフィールドの「レポートタイトル」にする
// ポストタイプenjoymentの場合は記事タイトルをカスタムフィールドの「タイトル」にする
// ポストタイプnewsの場合は記事タイトルをカスタムフィールドの「表示文字列」にする
// ポストタイプmetaの場合は記事タイトルをカスタムフィールドの「タイトル」にする
function replace_post_title($title) {

	global $post;

	if (!empty($post)) {

		// restaurant投稿の場合
		if ($post->post_type == "restaurant") {

			// 店舗名が入力されていれば、店舗名をタイトルにセット
			if (isset($_POST["magicfields"]["restaurant_name"][1][1])) {
				$title = $_POST["magicfields"]["restaurant_name"][1][1];
			}

		// report投稿の場合
		} elseif ($post->post_type == "report") {

			// レポートタイトルが入力されていれば、タイトルにセット
			if (isset($_POST["magicfields"]["report_title"][1][1])) {
				$title = $_POST["magicfields"]["report_title"][1][1];
			}

		// enjoyment投稿の場合
		} elseif ($post->post_type == "enjoyment") {

			// メイン記事内のタイトルが入力されていれば、タイトルにセット
			if (isset($_POST["magicfields"]["enjoyment_title"][1][1])) {
				$title = $_POST["magicfields"]["enjoyment_title"][1][1];
			}

		// news投稿の場合
		} elseif ($post->post_type == "news") {

			// 表示文字列が入力されていれば、タイトルにセット
			if (isset($_POST["magicfields"]["news_text"][1][1])) {
				$title = $_POST["magicfields"]["news_text"][1][1];
			}

		// meta投稿の場合
		} elseif ($post->post_type == "meta") {

			// 表示文字列が入力されていれば、タイトルにセット
			if (isset($_POST["magicfields"]["meta_title"][1][1])) {
				$title = $_POST["magicfields"]["meta_title"][1][1];
			}

		}

	}

	return $title;
}
add_filter("title_save_pre","replace_post_title");

// <<<--- 管理画面：restaurant投稿一覧に、food_catタクソノミー列追加 Start ---
add_filter('manage_edit-restaurant_columns', 'manage_restaurant_posts_columns');
add_action('manage_restaurant_posts_custom_column', 'add_restaurant_column', 10, 2);

// restaurant一覧に表示する列を追加
function manage_restaurant_posts_columns($columns) {

	$date_escape = $columns['date'];		// 一旦退避
	unset($columns['date']);				// 一旦消す

	$cat_escape = $columns['categories'];	// 一旦退避
	unset($columns['categories']);			// 一旦消す

	$columns['post_id'] = '店舗ID';
	$columns['categories'] = $cat_escape;	// ここで戻すとカテゴリーが店舗IDの後ろになる
	$columns['food_cat'] = 'ジャンル';
	$columns['date'] = $date_escape;		// ここで戻すと日付が最後になる
	return $columns;

}

// restaurant投稿一覧に、post_id、food_catタクソノミー表示
function add_restaurant_column($column_name, $post_id) {
	if ($column_name == 'post_id') {
		echo $post_id;
	}
	if ($column_name == 'food_cat') {
		echo get_the_term_list($post_id, 'food_cat', '', ', ');
	}
}
// --- 管理画面：restaurant投稿一覧に、food_catタクソノミー列追加 End --->>>


// <<<--- 管理画面：report投稿一覧に、対象店舗（report_target）列追加 Start ---
add_filter('manage_edit-report_columns', 'manage_report_posts_columns');
add_action('manage_report_posts_custom_column', 'add_report_column', 10, 2);

// report一覧に表示する列を追加
function manage_report_posts_columns($columns) {
	$date_escape = $columns['date'];		// 一旦退避
	unset($columns['date']);				// 一旦消す
	$columns['report_target'] = '対象店舗';
	$columns['date'] = $date_escape;		// ここで戻すと日付が最後になる
	return $columns;
}

// report投稿一覧に、food_catタクソノミー表示
function add_report_column($column_name, $post_id) {
	if ($column_name == 'report_target') {
		$vtitle = get_post_meta($post_id, 'report_target', true);

		if (isset($vtitle) && $vtitle) {
			$stitle = get_the_title($vtitle);
			echo esc_attr($stitle);
		} else {
			echo __('None');
		}
	}
}
// --- 管理画面：report投稿一覧に、対象店舗（report_target）列追加 End --->>>

// <<<--- 管理画面：enjoyment投稿一覧に、enj_catタクソノミー列追加 Start ---
add_filter('manage_edit-enjoyment_columns', 'manage_enjoyment_posts_columns');
add_action('manage_enjoyment_posts_custom_column', 'add_enjoyment_column', 10, 2);

// enjoyment一覧に表示する列を追加
function manage_enjoyment_posts_columns($columns) {
	$date_escape = $columns['date'];		// 一旦退避
	unset($columns['date']);				// 一旦消す
	$columns['enj_cat'] = 'enj カテゴリー';
	$columns['date'] = $date_escape;		// ここで戻すと日付が最後になる
	return $columns;
}

// enjoyment投稿一覧に、enj_catタクソノミー表示
function add_enjoyment_column($column_name, $post_id) {
	if ($column_name == 'enj_cat') {
		echo get_the_term_list($post_id, 'enj_cat', '', ', ');
	}
}
// --- 管理画面：enjoyment投稿一覧に、enj_catタクソノミー列追加 End --->>>

// <<<--- 管理画面：news投稿一覧に、news_catタクソノミー列追加 Start ---
add_filter('manage_edit-news_columns', 'manage_news_posts_columns');
add_action('manage_news_posts_custom_column', 'add_news_column', 10, 2);

// news一覧に表示する列を追加
function manage_news_posts_columns($columns) {
	$date_escape = $columns['date'];		// 一旦退避
	unset($columns['date']);				// 一旦消す
	$columns['news_cat'] = 'news カテゴリー';
	$columns['date'] = $date_escape;		// ここで戻すと日付が最後になる
	return $columns;
}

// news投稿一覧に、news_catタクソノミー表示
function add_news_column($column_name, $post_id) {
	if ($column_name == 'news_cat') {
		echo get_the_term_list($post_id, 'news_cat', '', ', ');
	}
}
// --- 管理画面：news投稿一覧に、news_catタクソノミー列追加 End --->>>

// <<<--- 管理画面：meta投稿一覧に、meta_catタクソノミー列追加 Start ---
add_filter('manage_edit-meta_columns', 'manage_meta_posts_columns');
add_action('manage_meta_posts_custom_column', 'add_meta_column', 10, 2);

// meta一覧に表示する列を追加
function manage_meta_posts_columns($columns) {
	$date_escape = $columns['date'];		// 一旦退避
	unset($columns['date']);				// 一旦消す
	$columns['meta_cat'] = 'meta カテゴリー';
	$columns['date'] = $date_escape;		// ここで戻すと日付が最後になる
	return $columns;
}

// meta投稿一覧に、meta_catタクソノミー表示
function add_meta_column($column_name, $post_id) {
	if ($column_name == 'meta_cat') {
		echo get_the_term_list($post_id, 'meta_cat', '', ', ');
	}
}
// --- 管理画面：meta投稿一覧に、meta_catタクソノミー列追加 End --->>>

// <<<---カスタムポストタイプでも管理画面でタクソノミーのドロップダウンを実現 Start---
add_action( 'restrict_manage_posts', 'my_restrict_manage_posts' );
add_filter('parse_query','my_convert_restrict');

function my_restrict_manage_posts() {

	global $typenow;
	$args=array( 'public' => true, '_builtin' => false );
	$post_types = get_post_types($args);

	if ( in_array($typenow, $post_types) ) {

		$filters = get_object_taxonomies($typenow);

		foreach ($filters as $tax_slug) {

			// カテゴリーは既存の絞り込み検索を使用するので除外
			if ($tax_slug !== 'category') {

				$tax_obj = get_taxonomy($tax_slug);

				// 絞り込み検索ドロップダウンリストを表示
				echo '<select name="'.$tax_slug.'">'."\n";
				echo '<option value="">すべての'.$tax_obj->label.'</option>'."\n";

				$terms = get_terms($tax_slug);

				foreach ($terms as $term) {

					if (isset($_REQUEST[$tax_slug]) && $term->slug == $_REQUEST[$tax_slug]) {
						$selected = ' selected';
					} else {
						$selected = '';
					}

					echo '<option value="'.$term->slug.'"'.$selected.'>'.$term->name.'</option>'."\n";

				}

				echo '</select>'."\n";

			}

		}
	}
}

function my_convert_restrict($query) {

	global $pagenow;
	global $typenow;

	if ($pagenow=='edit.php') {
		$filters = get_object_taxonomies($typenow);
		foreach ($filters as $tax_slug) {
		$var = & $query->query_vars[$tax_slug];
			if ( isset($var) && $var>0)  {
				$term = get_term_by('id',$var,$tax_slug);
				$var = $term->slug;
			}
		}
	}
	return $query;
}
// ---カスタムポストタイプでも管理画面でタクソノミーのドロップダウンを実現 End --->>>


// <<<---管理画面：report一覧に対象店舗での絞り込み検索を追加 Start---
add_filter('query_vars', 'my_add_query_vars');
function my_add_query_vars($vars) {
	$vars[] = 'report_target';
	return $vars;
}

// 管理画面：report一覧に、対象店舗を入力させる絞り込み検索フィールドを追加
add_action('restrict_manage_posts', 'report_restrict_manage_posts');
function report_restrict_manage_posts() {

	global $post_type;
	if ($post_type === 'report') {
	    printf(
	        '　対象店舗を入力： <input type="text" id="%1$s" name="%1$s" value="%2$s" />',
	        'report_target',
	        esc_attr(get_query_var('report_target'))
	    );
	}
}

// 管理画面：report一覧の絞り込み検索で「対象店舗」名の検索結果を抽出
add_filter('posts_where', 'admin_report_posts_where');
function admin_report_posts_where($where) {

    global $wpdb;
    global $post_type;

    if ( !is_admin() )	{
        return $where;
	}

	if ($post_type !== 'report') {
		return $where;
	}

    $value = get_query_var('report_target');

    if ( !empty($value) ) {
        $where .= $wpdb->prepare("
             AND EXISTS (
             SELECT 'x'
             FROM {$wpdb->postmeta} as m LEFT JOIN {$wpdb->posts} as p
             ON m.meta_value = p.ID
             WHERE m.post_id = {$wpdb->posts}.ID
             AND m.meta_key = 'report_target'
             AND p.post_title like %s
            )",
            "%{$value}%"
        );
    }

    return $where;

}
// ---管理画面：report一覧に対象店舗での絞り込み検索を追加 End --->>>

// <<<---管理画面：ビジュアルエディタの使用可能ボタンカスタマイズ Start ---
function myplugin_tinymce_buttons($buttons){

	global $post_type;

	// 投稿タイプ別にビジュアルエディタで使えるボタンをカスタマイズ
	switch ($post_type) {

		// restaurant投稿では「太字」「フォントサイズ」ボタンのみ有効
		case "restaurant":
			unset($buttons);
			$buttons = array(
				"bold",					// 太字
				"fontsizeselect",		// デフォルトには存在しないが追加
				"removeformat",			// 書式設定のクリア
			);
			break;

		// enjoyment投稿では「リンクの設定／解除」ボタンのみ有効
		case "enjoyment":
			unset($buttons);
			$buttons = array(
				"link",
				"unlink",
			);
			break;

	}

	return $buttons;

}
add_filter('mce_buttons','myplugin_tinymce_buttons');
// ---管理画面：ビジュアルエディタの使用可能ボタンカスタマイズ End --->>>

// <<<---管理画面：ビジュアルエディタのフォントをテキストエディタと同一にする ---
// 「editor-style.css」は別作成してテーマファイルに格納
// ビジュアルエディタのフォントサイズドロップダウンリストの値を制限する（pxにして12のみ表示）
add_editor_style('editor-style.css');
function custom_editor_settings( $initArray ){
	$initArray['body_class'] = 'editor-area'; //オリジナルのクラスを設定する
	$initArray['fontsize_formats'] = '12px';
	return $initArray;
}
add_filter( 'tiny_mce_before_init', 'custom_editor_settings' );
// ---管理画面：ビジュアルエディタのフォントをテキストエディタと同一にする --->>>

// <<<---管理画面：ビジュアルエディタで「メディアを追加」ボタンを非表示 Start ---
function hide_add_image_buttons() {

	global $post_type;
	global $pagenow;

	if (is_admin() && ($post_type === 'restaurant' || $post_type === 'enjoyment') &&
		($pagenow == 'post-new.php' || $pagenow == 'post.php')) {

		echo '<style type="text/css">
			a.button.insert-media.add_media{display:none;}
			</style>';

	}

}
add_action( 'admin_head', 'hide_add_image_buttons');
// ---管理画面：ビジュアルエディタで「メディアを追加」ボタン非表示 End --->>>

// 渡されたカテゴリーIDより、カテゴリー内の親子孫を判定
// 親子孫すべてのカテゴリーIDを配列で返す（$hide_empty=trueの場合、記事があるもののみ取得）
function get_category_hierarchy($cat_id, $hide_empty=false) {

	// 引数で渡されたIDのカテゴリー情報を取得（自分の情報も返したいので変数に保持）
	$cat = get_category($cat_id);
	$cat_hier["cat"] = $cat;

	// 親子孫の属性値を変数に設定
	// 自分が親の場合
	if ($cat->parent === 0) {
		$cat_hier["attr"] = "parent";

	// 親がいる場合（子か孫）、更に親がいるか調べる
	} else {

		// 親カテゴリーの情報を取得（親の情報も返したいので変数に保持）
		$parent = get_category($cat->parent);
		$cat_hier["parent"] = $parent;

		// 親の親がいない場合、自分は子
		if ($parent->parent === 0) {
			$cat_hier["attr"] = "child";

		// 親の親がいる場合、自分は孫
		} else {
			$cat_hier["attr"] = "grandchild";
		}

	}

	// 孫の場合は、料理ジャンル（food_cat）の情報をすべて取得
	if ($cat_hier["attr"] === "grandchild") {

		$taxonomy = "food_cat";

		$args = array(
			"orderby" => "t.order",
			"hide_empty" => $hide_empty,
		);

	// 孫以外の場合は、１つ下の階層の情報をすべて取得
	} else {

		$taxonomy = "category";

		$args = array(
			"orderby" => "t.order",
			"hide_empty" => $hide_empty,
			"parent" => $cat_id,
		);



	}

	// 階層により振り分けられた条件により、
	// 下階層カテゴリー情報または料理ジャンルタクソノミーを取得
	$cat_hier["children"] = get_terms($taxonomy, $args);

	return $cat_hier;

}

// 指定されたポストIDの、指定されたタクソノミー情報を取得し、
// 「○○／○○／○○～」の形式で返す
// 初期値はループ内IDの料理ジャンルタクソノミー情報
function get_disp_term($post_id=0, $taxonomy="food_cat") {

	$disp_term = "";
	$terms = get_the_terms($post_id, $taxonomy);

	if ( count($terms) > 0 ) {
		// 複数ある場合は「／」で区切ってすべてのタクソノミー名を表示
		foreach ( $terms as $term ) {
			$disp_term .= $term->name . "／";
		}
		// 末尾の「／」を取り除く
		$disp_term = mb_substr( $disp_term, 0, mb_strlen( $disp_term ) - 1 );

	// 指定された投稿に指定のタクソノミーが付加されていなければ空文字列を返す
	} else {
		$disp_term = "";
	}

	return $disp_term;

}

// 指定されたポストIDのすべてのコース料金を「／」で区切って表示
function get_disp_charge($post_id) {

	$disp_charge = "";
	$charges = get_post_meta($post_id, "course_charge", false);

	if ( count($charges) > 0 ) {
		// 複数ある場合は「／」で区切ってすべてのコース料金を表示
		foreach ( $charges as $charge ) {
			$disp_charge .= $charge . "／";
		}
		// 末尾の「／」を取り除く
		$disp_charge = mb_substr( $disp_charge, 0, mb_strlen( $disp_charge ) - 1 );

	} else {
		$disp_charge = "";
	}

	return $disp_charge;

}

// 指定されたポストIDのすべてのコース名称（料金）を「／」で区切って表示
// コース名称が無い場合は、料金のみを表示
function get_disp_course($post_id) {

	$disp_course = "";

	// コース名称と料金を繰り返し部分含め全取得
	$courses = get_post_meta($post_id, "course_name", false);
	$charges = get_post_meta($post_id, "course_charge", false);

	if ( count($courses) > 0 ) {
		// 複数ある場合は「／」で区切ってすべてのコース料金を表示
		foreach ( $courses as $key => $course ) {
			// コース名称が入っていればコース名称から表示
			if (!empty($course)) {
				$disp_course .= $course ."（". $charges[$key] ."）／";
			} else {
				$disp_course .= $charges[$key] ."／";
			}
		}
		// 末尾の「／」を取り除く
		$disp_course = mb_substr( $disp_course, 0, mb_strlen( $disp_course ) - 1 );
	}

	return $disp_course;

}

// レストラン詳細ページ、レポートページで使用するメタタイトルを取得
// restaurant投稿に入力があればそちらを活かし、なければ店舗名から自動生成
function get_rest_meta_title($post_id) {

	$meta_title = get_post_meta($post_id, "restaurant_meta_title", true);

	if (empty($meta_title)) {
		$meta_title = get_post_meta($post_id, "restaurant_name", true)
						. " - ごち会コースが楽しめるお店 - ごちそう会｜ごち会";
	}

	$meta_title = esc_html($meta_title);
	return $meta_title;

}

// ページに対応したメタ情報を配列で取得
function get_gochi_meta($meta_cat="top") {

	global $wp_query;

	// enjoyment一覧指定で実行された場合、enjカテゴリーで絞り込み後のページの場合は
	// 一覧ではなくenjoyment詳細と同じ扱いになり、タイトル名の頭にカテゴリー名を付与する
	if ($meta_cat === "enjoyment-list") {

		// enjカテゴリーが指定されている場合は、カテゴリー情報をあらかじめ取得しておく
		if (isset($wp_query->query_vars["enj_cat"]) && !empty($wp_query->query_vars["enj_cat"])) {

			$term = get_term_by("slug", $wp_query->query_vars["enj_cat"], "enj_cat");
			$meta_cat = "enjoyment";
		}

	}

	// 該当するメタ情報をメタ投稿から最新1件のみ抽出
	$args = array(
		"post_type" => "meta",
		"posts_per_page" => 1,
		"tax_query" => array(
			array(
				"taxonomy" => "meta_cat",
				"field" => "slug",
				"terms" => $meta_cat,
			),
		),
	);

	$meta_post = get_posts($args);

	if (count($meta_post) > 0) {

		$meta_post = $meta_post[0];

		$meta["title"] = get_post_meta($meta_post->ID, "meta_title", true);
		$meta["keywords"] = get_post_meta($meta_post->ID, "meta_keywords", true);
		$meta["description"] = get_post_meta($meta_post->ID, "meta_description", true);

		// enjoymentページは、詳細とenjカテゴリー絞り込み一覧で表示を出し分け
		if ($meta_cat === "enjoyment") {

			// 詳細ページの場合
			if ($wp_query->is_single()) {

				// 取得済みのタイトルの前に、個別記事タイトルを入れて「 - 」でつなぐ
				$meta["title"] = get_post_meta($meta_post->ID, "enjoyment_title", true) . " - " . $meta["title"];

			// 詳細ページ以外（一覧ページ）の場合
			} else {

				// 取得済みのタイトルの前に、enjカテゴリー名を入れて「 - 」でつなぐ
				if (!empty($term)) {
					$meta["title"] = $term->name . " - " . $meta["title"];
				}

			}

		}

	}

	// 万が一取得できなかった場合は、サイトの情報をメタ情報として返す
	if (empty($meta)) {
		$meta["title"] = get_bloginfo("name");
		$meta["description"] = get_bloginfo("description");
		$meta["keywords"] = "ごち会,飲み会,ごちそう会,打ち上げ,忘年会,女子会,誕生日";
	}

	return $meta;

}

// URLとして正しいかどうかをチェックしてOKの場合はtrueを返す
function check_url($url) {

	if (empty($url)) {
		return false;
	}

	// http://省略や日本語を含むURLも可能とする正規表現パターン
	$patt = '/([\w*%#!()~\'-]+\.)+[\w*%#!()~\'-]+(\/[\w*%#!()~\'-.]+)*/u';

	if (preg_match($patt, $url)) {
		return true;
	} else {
		return false;
	}

}

// ビジュアルエディタの「フォントサイズ」で設定された<span>タグ内のfontsize（12px）を
// 取り除いて<span>のみにする（レスポンシブでのフォントサイズ設定に対応させるため）
function gochi_strip_fontsize($string) {

	return str_replace( ' style="font-size: 12px;"', '', $string );

}

// enjoymentでメイン画像の登録がなかった場合にデフォルトで表示する画像のURIを取得
function get_noimage_enjoyment_uri() {
	$uri = get_stylesheet_directory_uri() . "/img/noimg_gochi.jpg";
	return $uri;
}

// 指定した画像のURLを、指定したサイズで取得する
// 引数singleをfalseにした場合、対象画像の繰り返しを配列ですべて取得する
function get_gochi_image($post_id, $image_name, $size, $single=true) {

	$image_id = get_post_meta($post_id, $image_name, $single);

	// enjoyment_imageの取得時、画像が存在しなかったらデフォルト画像を取得
	if ( ! $image_id && $image_name == "enjoyment_image" ) {
		$image = get_noimage_enjoyment_uri();

	// 上記以外は指定された画像を取得
	} else {
		if ($single) {
			$image = wp_get_attachment_image_src($image_id, $size);
			$image = $image[0];
		} else {
			foreach ($image_id as $single_id) {
				$image[] = wp_get_attachment_image_src($single_id, $size);
			}

			foreach ($image as $k => $v) {
				$image[$k] = $image[$k][0];
			}
		}
	}

	return $image;
}

// 指定した画像のURLと、横幅サイズに応じた適切なクラスを配列で返す
// 引数singleをfalseにした場合、対象画像の繰り返しを配列ですべて取得する
function get_gochi_image_class($post_id, $image_name, $size, $single=false) {

	// 画像idを取得
	$image_id = get_post_meta($post_id, $image_name, $single);

	// enjoyment_imageの取得時、画像が存在しなかったらデフォルト画像を取得
	if ( ! $image_id && $image_name == "enjoyment_image" ) {
		$images["url"] = get_noimage_enjoyment_uri();
		$images["class"] = ' enjoy__article--over-img';		// 幅430px超の場合で設定

	// 上記以外は指定された画像を取得
	} else {

		// 単一取得指定の場合
		if ($single) {

			$image = wp_get_attachment_image_src($image_id, $size);
			$images["url"] = $image[0];		// URLを取得
			$images["class"] = null;		// 一旦初期値セット

			// 画像名にreportが含まれる場合、幅588px超ならばクラスを設定
			if (strpos($image_name, "report") !== false) {
				if ($image[1] > 588) {
					$images["class"] = ' class="rest__details__report--over-img"';
				}
			}

			// 画像名にenjoymentが含まれる場合、幅430超ならばクラスを設定
			// enjoymentではデフォルトでclassが設定済みなので、追加classとして設定
			if (strpos($image_name, "enjoyment") !== false) {
				if ($image[1] > 430) {
					$images["class"] = ' enjoy__article--over-img';
				}
			}

		// 複数取得指定の場合、単一取得時より更に1階層深い配列として取得
		// 取得内容は単一時と同様だが、enjoymentの場合、奇数回は反転表示のクラスを追加
		} else {

			$i = 0;

			foreach ($image_id as $single_id) {

				$i++;		// 奇数回、偶数回の判定に使用

				$tmp = wp_get_attachment_image_src($single_id, $size);

				$image["url"] = $tmp[0];
				$image["class"] = null;

				if (strpos($image_name, "report") !== false) {
					if ($tmp[1] > 588) {
						$image["class"] = ' class="rest__details__report--over-img"';
					}
				}

				if (strpos($image_name, "enjoyment") !== false) {

					if ($tmp[1] > 430) {
						$image["class"] = ' enjoy__article--over-img';

					// 奇数回の場合、反転表示のクラスを設定
					} elseif ($i % 2 !== 0) {
						$image["class"] = ' enjoy__article--reverse';
					}
				}

				$images[] = $image;

			}

		}

	}

	return $images;

}


// レストランスライダー用のイメージ画像URL（PC/SP用）を配列で取得
// 並び順が別途指定されている場合は、その並び順通りに取得
function get_restaurant_slider_images($post_id, $single) {

	// 画像idを一旦全取得
	$image_id = get_post_meta($post_id, "restaurant_image");

	// 並び順指定がされているかどうか確認
	$order = get_post_meta($post_id, "restaurant_image_order");
	$flg = false;

	// 1つでも値が設定されているものがあれば$flgをtrueとする
	// 全角英数字を半角英数字に変換する（そのために$valueを参照渡しにする）
	foreach ($order as $key => &$value) {
		if (!empty($value)) {
			$flg = true;
			$value = mb_convert_kana($value, "a");
		}
	}

	// 並び順指定された画像が1つでもあれば番号順に並び替える
	// このとき、空白は０とみなすので先頭にくる
	if ($flg) {
		asort($order);
	}

	// $single指定の場合は先頭の1件のみ取得し、PC用とSP用（サムネイル用別画像）を切り分けて返す
	if ($single) {

		foreach ($order as $key => $value) {
			$thumb_id = get_post_meta($post_id, "restaurant_thumbnail", true);
			$tmp_main = wp_get_attachment_image_src($image_id[$key], "restaurant_main");
			$tmp_thumb = wp_get_attachment_image_src($thumb_id, "restaurant_thumb");
			$images["l_image"] = array_shift($tmp_main);
			$images["s_image"] = array_shift($tmp_thumb);
			break;
		}

	// スライダー用複数取得の場合は、PC用のみを配列で返す
	} else {

		// 順番に画像URLを配列として格納
		foreach ($order as $key => $value) {
			$tmp_main = wp_get_attachment_image_src($image_id[$key], "restaurant_main");
			$images[] = array_shift($tmp_main);
		}

	}

	return $images;

}


// OGイメージが設定されている場合はURLを、指定されていなければ代替画像のURLを取得
function get_ogimage($post_id, $post_type="restaurant") {

	// restaurant投稿の場合のみ、OGイメージ専用の登録箇所があるので、そこから取得
	if ($post_type === "restaurant") {
		// OGイメージ専用の画像IDを取得
		$image_id = get_post_meta($post_id, "restaurant_meta_ogimage", true);
	}

	// 画像が設定されていない場合（またはrestaurant投稿以外）は、メイン画像のIDを取得（または固定イメージを別途設定）
	if (empty($image_id)) {

		// 代替画像の名前をポストタイプ別に取得
		if ($post_type === "enjoyment") {
			$image_name = "enjoyment_image";
		} else {
			$image_name = "restaurant_images";
		}

		// 代替画像の画像IDを取得
		$image_id = get_post_meta($post_id, $image_name, true);
	}

	$image = wp_get_attachment_image_src($image_id, "ogp_image");

	// 画像の取得ができなかった場合は、サイト共通のOGイメージをセット
	if (!isset($image[0]) || empty($image[0])) {
		$image = COMMON_OGP;

	// 画像の取得ができた場合はURL部分のみを返す
	} else {
		$image = $image[0];
	}

	return $image;

}

// このテーマでアイキャッチ画像を使用できるようにする
add_theme_support("post-thumbnails");
set_post_thumbnail_size(320,320,true);		// デフォルトサムネイルサイズ

// restaurant投稿用の画像サイズ
add_image_size("restaurant_main", 1176, 792, true);
add_image_size("restaurant_thumb", 160, 160, true);

// OGP用画像サイズ
add_image_size("ogp_image", 1200, 630, true);

// wpの不要な書式設定等を除去
remove_filter("the_content","wpautop");
remove_filter("the_excerpt","wpautop");
remove_action("wp_head","wp_generator");


// 自動保存を無効にする
function hacky_autosave_disabler( $src, $handle ) {
    if( 'autosave' != $handle )
        return $src;
    return '';
}
add_filter( 'script_loader_src', 'hacky_autosave_disabler', 10, 2 );

// 編集画面の#wrapの背景色を青系の色にする
function my_admin_wrap_color() {
  echo '<style type="text/css">#newcontent{background: #BBBBFF!important}</style>';
  return '';
}
add_action('admin_head','my_admin_wrap_color');

// <<<--- 定数 --->>>
define ('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
define ('COMMON_OGP', home_url('/assets/common/img/ogp_img.png', 'http'));
