$(function(){
  var entry_url = $("#entry_url").val();
  
  /* ショッピングカートに入れるがクリックされた時 */
  $("#cart_in").click(function(){
    var item_id = $("#item_id").val();
    location.href = entry_url + "controller/cart.php?item_id=" + item_id;
  });
  
  /* ??? */
  $("#update").click(function(){
    var mem_id = $("#mem_id").val();
    location.href = entry_url + "controller/member_detail.php?mem_id" + mem_id;
  });

  /* 郵便番号検索がクリックされた時 */
  $("#address_search").click(function(){
    var zip1 = $('#zip1').val();
    var zip2 = $('#zip2').val();
    var entry_url = $('#entry_url').val();
    if(zip1.match(/[0-9]{3}/) === null || zip2.match(/[0-9]{4}/) === null){
      alert('正確な郵便番号を入力してください。');
      return false;
    }else{
      $.ajax({
        type : "get",
        url : entry_url + "controller/postcode_search.php?zip1=" + escape(zip1) + "&zip2=" + escape(zip2),
      }).then(
        function(data){
          if(data == 'no' || data == ''){
            alert('該当する郵便番号はありません');
          }else{
            $('#address').val(data);
          }
        },
        function(data){
          alert("読み込みに失敗しました。")
        },
      );
    }
  });

  // いいねボタンが押された時
  $("#like").click(function(){
    var item_id = $('#item_id').val();
    var entry_url = $('#entry_url').val();
    var $this = $(this);
    $.ajax({
      type : "post",
      url : entry_url + "controller/like.php" ,
      data : {itemId: item_id}
    }).then(
      function(data){
        if(data == 'no' || data == ''){
          alert('お気に入り登録に失敗しました')
        }else{
          console.log(data);
          // いいねの総数を表示
          $this.children('span').html(data);
          // いいね取り消しのスタイル
          $this.children('i').toggleClass('far'); //空洞ハート
          // いいね押した時のスタイル
          $this.children('i').toggleClass('fas'); //塗りつぶしハート
          $this.children('i').toggleClass('active');
          $this.toggleClass('active');
        }
      },
      function(data){
        alert("読み込みに失敗しました。")
      },
    );
  });
});