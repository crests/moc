/*
* 全体の構成
*
* csvの読み込み関数
*
* 配列のランダム並べ替え関数
* リンクもつけて遅延読み込みする関数
*   リンクをつけたら、classをすでに画像を入れたように変える関数
*     *読み込みは上から順に行われるのかは確かめる必要あり
*
*
*
* スクリーンの末端まできたら、更新する関数
*    末端を認識する
*    ロードgifを表示する
*    読み込む（何個読み込むかは、引数化する）
*    画像の読み込みは遅延させる（遅延読み込みはここでも使う）
*
*
*/


/*
* status
*
*
*/
let CSVFileName = "photo_list.csv";
let NumOfPhotos = 10;
let DefaultPhotosSrc = "/assets/common/img/default.png";
let ImgPath = "/assets/img/gallery/small/";


/* csv読み込み
*
* fcn getCSV()
* @param {}
* @return {void} 現状何も設定されていない。jsは、関数内で定義した関数は、ずっと保持されるのか？保持されずにメモリが解放されてしまうのか？よう確認
*
*
*
* fcn convertCSVtoArray(str)
* @str {string} read csv data, which is treated as string
* @return {void}  ?????????????????????????????????????????????????????????/
*/
//CSVファイルを読み込む関数getCSV()の定義
// 正しいコード
// <div id="r"></div>
function getCSV2() {
  var req = new XMLHttpRequest();
  //非同期リクエスト、すぐに処理を行わない場合、この中の処理で完結する場合は良い
  //csvを利用する他の処理が並列する場合は、データが格納されていなくてundefinedになりうる
  //高速化をしたい場合は、csv関連の処理はcallbackやonloadに入れて、
  //requestを並行処理させる。
  req.open('get', CSVFileName, true);
  req.send(null);
  req.onload = function() {
    setCSV(req.responseText);
  };
}
function setCSV(str) {
  var data = [];
  var dataArr;
  var r = document.getElementById('r');
  var tmp = str.split('\n');
  tmp.forEach(x => {
    dataArr = x.split(',');
    if (dataArr[0]) {
      data.push(dataArr.map(x => x.trim()));
    }
  });
  //CSV = data;
  return data;
}

function getCSV(filename){
  var txt = new XMLHttpRequest();
  txt.open('get', filename, false); //同期リクエスト
  txt.send();

  return setCSV(txt.responseText);
}


/* 配列のランダム並べ替え
*
* fcn getRandomInt(max)
* @max {int} max value of length of return array. not the max val of array
* @return {Array(Int)} 1d int array, which the order is randomized.
*
* fcn randomizeArray(arr)
* @arr {1d Array} target arrray, which is going to randomized
* @result {1d Array} return Array, to which the target array are randomized.
*
*
*/
//
function getRandomIntArray(max) {
  // ランダムな配列
  var array = [...Array(max).keys()];
  for(var i = max-1; i>0; i--){
    var r = Math.floor(Math.random() * (i + 1));
    var tmp = array[i];
    array[i] = array[r];
    array[r] = tmp;
  }
  for(var i = max-1; i>0; i--){
    var r = Math.floor(Math.random() * (i + 1));
    var tmp = array[i];
    array[i] = array[r];
    array[r] = tmp;
  }
  for(var i = max-1; i>0; i--){
    var r = Math.floor(Math.random() * (i + 1));
    var tmp = array[i];
    array[i] = array[r];
    array[r] = tmp;
  }
  return array;
}

function randomizeArray(arr){
  //返す配列を初期化
  var result = new Array(arr.length);
  //ランダムな整数配列を取得。長さは引数の配列と同じ。
  var randArr = getRandomIntArray(arr.length);
  //配列の順序を入れ替える
  for(var i=1; i<arr.length; i++){
    //返す配列のi番目に、ランダムに指定されたindexの値を入れる。
    result[i] = arr[randArr[i]];
  }
  return result;
}


/* 遅延読み込み
*  こればかりは、汎用性が低い。どのようにhtml, cssを定義しておくかも含めて決める。
*
*  --default img: before reading img
*  <a href="" class="default-img">
*     <img src="default.png" alt="">
*  </a>
*
*  --after reading img
*  <a href="http://gochikai....../" class="read-img">
*     <img src="----.png" alt="------">
*  </a>
*
*
* fcn readImg(atag)
* @atag {jQuery object} a tag, which class is "default img"
* @link {string} link adress, where the img exists.
* @url {string} url, which to insert in href tag
*
*
* fcn readImgs(csv)
* @csv {2d string array} img href, dir list.
*
*/
//

function readImg(atag, link, url){
  let photo = atag.getElementsByTagName('img')[0];
  photo.src = ImgPath+link;
  photo.alt = link;
  atag.href = url;
  $(atag).removeClass('default-img');
  $(atag).addClass('read-img');
  return;
}

function readImgs(csv){
  var imgs = document.getElementsByClassName('default-img');
  var n = imgs.length;
  var rand = getRandomIntArray(csv.length);
  for (var i=0; i<n; i++){
    readImg(imgs[0],csv[rand[i]][0], csv[rand[i]][2]);
  }
}

/**
* 最後までスクロール
* https://qiita.com/w650/items/14833066ccd1368c27a1
*
*/
//
function scrolled2End(){
  var wpx = $(window).scrollTop();
  const wholeHeight = $('.header').outerHeight() +  $('.section').outerHeight();

  //console.log(wpx + "offset:" + wholeHeight + "inner:" +window.innerHeight);
  var heightFromBottom = wholeHeight - wpx - window.innerHeight;
  if(heightFromBottom<=0){
    readNewPhotos();
  }
}
//デフォルトのグレー画像を追加する。
function addNewDefaultPhotos(){
  var text = '<a class="default-img" href="/gallery/"><img src="/assets/common/img/default.png" alt="photo"></a>';
  var texts = text;
  for(var i=0; i<NumOfPhotos; i++){
    texts += text;
  }
  $(".photo_list").append(texts);
}



/* 実行する関数
*/
//読み込み終了後に実行
function readNewPhotos(){
  var csv;
  addNewDefaultPhotos();
  $('.default-img').ready(function(){
    csv = getCSV(CSVFileName);
    readImgs(csv);
  });
}
readNewPhotos();
//スクロール時に実行
$(window).scroll(function(){
  scrolled2End();
});
