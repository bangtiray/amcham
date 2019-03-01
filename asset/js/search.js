$(document).on('click','#search-news-btn',function(e){
  khusus_newsletter();
	search_news();
  return false;
});

$('#search-news-input').keypress(function (e) {
  if (e.which == 13) {
    khusus_newsletter();
    search_news();
    return false;
  }  
});

$(document).on('change','#day,#month,#year',function(){
  khusus_newsletter();
  search_news();
  return false;
});

function search_news($controller){
  var lang     = 'en';
  var full_url = window.location.href;
  var uri_path = full_url.split('/index/').pop();
  var page     = '';
  var keyword  = $('#search-news-input').val();
  var month    = $('#month').val();
  var year     = $('#year').val();

  $.ajax({
    url   : base_url+'en/news/index',
    type  : 'post',
    data  : {keyword   : keyword,
              lang     : lang,
              uri_path : uri_path,
              page     : page,
              month    : month,
              year     : year
            },
    success: function (data) {
      $('#media-news').empty().append(data);
    }
  });
}
function khusus_newsletter() {
  var uri_path  = full_url.split('/index/').pop();
  //khusus search newsletter
  if (uri_path == 'newsletter') {
    search_news('newsletter');
    return false;
  }
}

$('#password').keypress(function (e) {
  var key = e.which;
  if(key == 13){
    $('.btn-red-login').click();
    return false;  
  }
}); 