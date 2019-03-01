$(window).scroll(function() {
  if ($(this).scrollTop() > 1){  
      $('header').addClass("sticky");
    }
    else{
      $('header').removeClass("sticky");
    }
});
$(window).scroll(function() {
  if ($(this).scrollTop() > 1){  
      $('.top-hide').addClass("sticky-top-hide");
    }
    else{
      $('.top-hide').removeClass("sticky-top-hide");
    }
});
$(document).on('click',".gallery-video",function(){
  url_youtube = $(this).attr('href');
  link = base_url+'en/home/get_iframe/';
  $.ajax({
    url:link,
    type: 'POST',
    dataType: 'json',
    data: {url: url_youtube},
    success:function(res){
        $('#myModalVideo .modal-body').empty();
        $('#myModalVideo .modal-body').append('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>');
        $('#myModalVideo .modal-body').append(res.iframe);
        $('#myModalVideo .modal-body').append('<div class="desc-video-popup"> <h2>'+
                                                res.title +'</h2> <p>'+
                                                res.desc+'</p> </div>');
    }
  })

});
$(document).on('click',".gallery-photo",function(){
  id_gallery = $(this).attr('gallery-id');
  $('#myModalPhoto .carousel-inner .item').each(function(){
    id = $(this).attr('gallery-id');
    if (id_gallery == id) {

      $('.carousel-inner .item').each(function(){
        $(this).removeClass('active');
      });
      $(this).addClass('active');
    }
  });

});
// $(window).scroll(function() {
//     if ($(this).scrollTop() > 1){  
//         $('.left-menu-frame-dtlproduct').addClass("sticky");
//       }
//       else{
//         $('.left-menu-frame-dtlproduct').removeClass("sticky");
//       }
// });
$(document).ready(function(){
    $("#filter_benefit").click(function(){
        $("ul").addClass("intro");
    });
});

// $(document).ready(function () {
//  $(".form-skip").hide();
//     $('#selectcc').click(function () {
//         $('.form-btf').hide('fast');
//         $('.form-cc').show('fast');
//     });
//     $('#selectbt').click(function () {
//         $('.form-cc').hide('fast');
//         $('.form-btf').show('fast');
//     });
// });
// $(document).ready(function () {
//     $('.btn-compare').hide();
//     var date = new Date();
//     var d = date.getDate();
//     var m = date.getMonth();
//     var y = date.getFullYear();
//     var calendar =  $('#calendar').fullCalendar({
//       header: {
//         left: 'prev',
//         center: 'title',
//         right: 'next'
//       },
//       editable: false,
//       firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
//       selectable: true,
//       defaultView: 'month',
      
//       axisFormat: 'h:mm',
//       columnFormat: {
//                 month: 'ddd',    // Mon
//                 week: 'ddd d', // Mon 7
//                 day: 'dddd M/d',  // Monday 9/7
//                 agendaDay: 'dddd d'
//             },
//             titleFormat: {
//                 month: 'MMMM yyyy', // September 2009
//                 week: "MMMM yyyy", // September 2009
//                 day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
//             },
//       allDaySlot: false,
//       selectHelper: false,
//       // select: function(start, end, allDay) {
//       //   var title = prompt('Event Title:');
//       //   if (title) {
//       //     calendar.fullCalendar('renderEvent',
//       //       {
//       //         title: title,
//       //         start: start,
//       //         end: end,
//       //         allDay: allDay
//       //       },
//       //       true // make the event "stick"
//       //     );
//       //   }
//       //   calendar.fullCalendar('unselect');
//       // },
//       droppable: false, // this allows things to be dropped onto the calendar !!!
//       // drop: function(date, allDay) { // this function is called when something is dropped
      
//       //   // retrieve the dropped element's stored Event Object
//       //   var originalEventObject = $(this).data('eventObject');
        
//       //   // we need to copy it, so that multiple events don't have a reference to the same object
//       //   var copiedEventObject = $.extend({}, originalEventObject);
        
//       //   // assign it the date that was reported
//       //   copiedEventObject.start = date;
//       //   copiedEventObject.allDay = allDay;
        
//       //   // render the event on the calendar
//       //   // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
//       //   $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
        
//       //   // is the "remove after drop" checkbox checked?
//       //   if ($('#drop-remove').is(':checked')) {
//       //     // if so, remove the element from the "Draggable Events" list
//       //     $(this).remove();
//       //   }
        
//       // },
      
//       events: [
//         {
//           title: 'All Day Event',
//           start: new Date(y, m, 1)
//         },
//         {
//           id: 999,
//           title: 'Repeating Event',
//           start: new Date(y, m, d-3, 16, 0),
//           allDay: false,
//           className: 'red-back'
//         },
//         {
//           id: 999,
//           title: 'Repeating Event',
//           start: new Date(y, m, d+4, 16, 0),
//           allDay: false,
//           className: 'info'
//         },
//         {
//           title: 'Meeting',
//           start: new Date(y, m, d, 10, 30),
//           allDay: false,
//           className: 'important'
//         },
//         {
//           title: 'Lunch',
//           start: new Date(y, m, d, 12, 0),
//           end: new Date(y, m, d, 14, 0),
//           allDay: false,
//           className: 'important'
//         },
//         {
//           title: 'Birthday Party',
//           start: new Date(y, m, d+1, 19, 0),
//           end: new Date(y, m, d+1, 22, 30),
//           allDay: false,
//         },
//         {
//           title: 'Click for Google',
//           start: new Date(y, m, 28),
//           end: new Date(y, m, 29),
//           url: 'http://google.com/',
//           className: 'success'
//         }
//       ],      
//     });
// });
upload_data();
function upload_data(){
    $("input[type='file']").change( function() {
    var filename = $(this).val().replace(/C:\\fakepath\\/i, '')
    var id = $(this).attr("id").replace("data-","");
    $('#filename-'+id).removeClass('hide');
    $('#filename-'+id).html(filename);
    $('#remove-'+id).removeClass('hide');
    remove(id);
    $('#button-'+id).addClass("hide");
    detect_max_up()

});
}
function remove(id){
    $("#remove-"+id).click( function() {
        $('#filename-'+id).addClass('hide');
        $('#remove-'+id).addClass('hide');

        $('#button-'+id).removeClass("hide");
        detect_max_up()
    });
}

function detect_max_up(){
    total_upload_file = $(".filename:visible").length;

    if(total_upload_file>=3){
        $('.show_button,.show_form').removeClass("hide");
    } else {
        $('.show_button,.show_form').addClass("hide");
    }
}

var i = 4;

$(document).ready(function () {
    $(".row-detailclaimproduct").hide();

    $('.btn-claim').click(function () {
        if($('#'+$(this).attr("id")+"_tab").is(':visible')){
            $('#'+$(this).attr("id")+"_tab").hide('fast');
            $(this).html("SELENGKAPNYA");
        } else {
            $('#'+$(this).attr("id")+"_tab").show('fast');
            $(this).html("SEMBUNYIKAN");
        }
    });

    $('#slider-index').owlCarousel({
        looping:true,
        loop:true,
        items:1,
        nav: false,
        autoPlay: true,
        autoHeight: false,
        pagination:false,
        dots:true,
        autoplay: true,
        autoplayTimeout: 3000,
        animateIn: 'fadeIn',
        smartSpeed: 450
    });

    $('#slider-partners').owlCarousel({
        margin:15,
        items:4,
        autoPlay: true,
        loop:true,
        autoHeight: false,
        autoWidth:false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        nav:false,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:3,
                nav:false
            },
            1000:{
                items:4,
                nav:true,
            }
        }
    });

    $('#slider_gallery_photo').owlCarousel({
        margin:15,
        items:3,
        autoPlay: true,
        loop:true,
        autoHeight: false,
        autoWidth:false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        nav:true,
        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:3,
                nav:false
            },
            1000:{
                items:4,
                nav:true,
            }
        }
    });

    $('.slider-sponsors').owlCarousel({
        margin:15,
        loop:true,
        pagination:false,
        items:1,
        autoHeight: false,
        autoWidth: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
    });

    $('.slider-sponsors-legal').owlCarousel({
        margin:15,
        items:1,
        loop:true,
        autoHeight: false,
        autoWidth: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
    });

    $('.banner-video').owlCarousel({
      	items:1,
        // merge:true,
        loop:true,
        margin:0,
        video:true,
        lazyLoad:true,
        center:true,
        autoplay:true,
        autoplayTimeout:1000,
        autoplayHoverPause:true,
    })
});

function heightsame(idx){
   var highestBox = 0;
        $(idx).each(function(){  
                if($(this).height() > highestBox){  
                highestBox = $(this).height();  
        }
    });
    $(idx).height(highestBox); 
}
$(document).ready(function(){

    heightsame(".heightsame");
});

function sameheight(idx){
   var highestBox = 0;
        $(idx).each(function(){  
                if($(this).height() > highestBox){  
                highestBox = $(this).height();  
        }
    });    
    $(idx).height(highestBox); 
}
$(document).ready(function(){

    sameheight(".sameheight");
});

$(window).scroll(function() {
    if ($(this).scrollTop() > 1){
        $('.anchor').addClass("show-btntop");
    }
    else{
        $('.anchor').removeClass("show-btntop");
    }
});

$('.anchor-bt').click(function(){
    var body = $("html, body");
    body.animate({scrollTop:0}, '500', 'swing');
});


$(document).on('shown.bs.select','.selectpicker',function(){
    $("body").addClass("open-bootstraps-select");
});

$(document).on('hidden.bs.select','.selectpicker',function(){
    $("body").removeClass("open-bootstraps-select");
});
$('.panel').on('shown.bs.collapse', function (e) {
    $(this).addClass("payment-with-border");
});
$('.panel').on('hidden.bs.collapse', function (e) {
    $(this).removeClass("payment-with-border");
});

$(document).on('click',".load-more",function(){
  var a = $(this);
  var nojs = $(this).attr('nojs');
  if (nojs) {
    return false;
  }

  var link = a.attr('href');
  a.html('Loading...');

  $.ajax({
    url:link,
    success:function(res){
      var lasturl = window.full_url.split('/').pop();
      if (lasturl == 'video') {
        a.parent().parent().append(res);
        // $('.frame-content .row').append(res);
        a.remove();
      }else{
        a.parent().parent().append(res);
        // $('.frame-content').append(res);
        a.remove();
      }
    }
  })
  return false;
})

$(document).on('click',".load-more-photo",function(){
  var a = $(this);
  var link = a.attr('href');
  a.html('Loading...');
  
  $.ajax({
    url:link,
    dataType:'JSON',
    success:function(res){
      $('.frame-content .row').append(res.images);
      $('#slider-detail-photo').append(res.modal_images);
      a.remove();
    }
  })
  return false;
})

$(document).on('click',".load-more-video",function(){
  var a = $(this);
  var link = a.attr('href');
  a.html('Loading...');
  
  $.ajax({
    url:link,
    dataType:'JSON',
    success:function(res){
      $('.frame-content .row').append(res.images);
      $('.frame-content .row').append(res.more);
      a.remove();
    }
  })
  return false;
})

function shared_article(){
  if($('.share-article').is(':visible')){
    $('a.share-tw').attr('href',full_url);
    $('a.share-fb').attr('href','//www.facebook.com/sharer.php?u='+full_url);
    $('a.share-in').attr('href','http://www.linkedin.com/shareArticle?url='+full_url);

    $(document).on('click','a.share-tw',function(e){ 
      e.preventDefault();
        var loc = $(this).attr('href');
        var title  = encodeURIComponent($(this).attr('title'));
        window.open('//twitter.com/share?url=' + full_url + '&text=' + news_title + '&', 'twitterwindow', 'height=450, width=550, top='+($(window).height()/2 - 225) +', left='+$(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
    });
    var getTwitterCount = function () {
      $.getJSON('//cdn.api.twitter.com/1/urls/count.json?url='+full_url+'&callback=?', function(data){
        var twitterShares = data.count;
        $('.share_type_twitter .share_count').text(twitterShares);
      });
    };
    // getTwitterCount();


    $(document).on('click','a.share-fb', function(e){ 
      $('.facebook').attr('href','//www.facebook.com/sharer.php?u='+full_url);
        e.preventDefault();
        window.open('//www.facebook.com/sharer/sharer.php?u='+full_url, 'facebook_share', 'height=320, width=640, top='+($(window).height()/2 - 225) +', left='+$(window).width()/2 +', toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
    });      

    var getFacebookCount = function () {
        $.getJSON('//graph.facebook.com/?ids='+full_url+'&callback=?', function(data){
          var facebookShares = (data[full_url].shares) ? data[full_url].shares :'0';
          $('.share_type_facebook .share_count').text(facebookShares);
        });
    };
    // getFacebookCount();
    
    $(document).on('click','a.share-in', function(e){
      e.preventDefault();
      $('.linkedin').attr('href');
      window.open('//www.linkedin.com/shareArticle?url='+full_url, 'linkedin_share', 'height=450, width=550, top'+($(window).height()/2 - 225) +', \
        left='+$(window).width()/2 +', toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
    });    
    var getLinkedinCount = function (){
      $.getJSON('//www.linkedin.com/countserv/count/share?url='+full_url+'&callback=?',function(data){
        var linkedinShares = data.count;
        $('.share_type_linkedin .share_count').text(linkedinShares);
      });
    };
    // getLinkedinCount();

      
  }
}
function download_file(ret) {
  var idx = ret.getAttribute('id');
  var add = ret.getAttribute('d-title');
  var newtab = ret.getAttribute('newtab');
  var preview = ret.getAttribute('preview');
/*  alert(base_url+'en/event/get_download');
  document.location.pathname = base_url+'event/get_download';
  return false;*/
  $.ajax({
    url: base_url+'en/'+add+'/get_download',
    type: 'POST',
    dataType: 'json',
    data: {idx: idx},
    success: function(ret) {
      if (ret.path != 'error') {
          ret.path = ret.path.replace('en/','');
        if (newtab) {
          window.open(ret.path,'_blank');
        }else{
          document.location = ret.path;
        }

      }
      return false;
    }
  })
}

function download_event_material(ret) {
  var idx = ret.getAttribute('id');
  var add = ret.getAttribute('d-title');
/*  alert(base_url+'en/event/get_download');
  document.location.pathname = base_url+'event/get_download';
  return false;*/
  // console.log(base_url+'en/'+add+'/get_material_hits');
  $.ajax({
    url: base_url+'en/'+add+'/get_material_hits',
    type: 'POST',
    dataType: 'json',
    data: {idx: idx},
    success: function(ret) {
      if (ret.path != 'error') {
        document.location = ret.path;
      }
      return false;
    }
  })
}

// function registNewsletter(modalname) { 
//   if ($('#form1').parsley().validate()) {
//     $.ajax({
//       url         : $('#form1').attr('action'),
//       type        : "POST",
//       dataType    : "json",     
//       data        : $('#form1').serialize(),
//       error: function () {
//         alert('error');
//       },
//       success     : function(ret){
//         if (ret.error == 1 ) {
//           alert(ret.msg);
//         }else{
//           $('#'+modalname).modal('show');          
//         }        
//       }
//     })
//   } 
//   return false;
// }

function formclick(x) { 
  var $id_form  = $(x).parents('form').attr('id');
  if ($('#'+$id_form).parsley().validate()) {
    var btnname = $(x).text();

    
    if ($(x).attr('disabled') == "disabled" ){
      return false
    }
    $(x).text('Loading...');
    $(x).attr('disabled',true);

    last_action = $('#'+$id_form).attr('action').split('/').pop();
    if (last_action == 'register') {
      $('#'+$id_form).append('<input type="hidden" name="full_url" value="'+full_url+'">');
    }

    $.ajax({
      url         : $('#'+$id_form).attr('action'),
      type        : "POST",
      dataType    : "json",     
      data        : $('#'+$id_form).serialize(),
      error: function (ret) {
          // console.log(ret);
          $(x).attr('disabled',false);
          $(x).html(btnname);


          if (typeof ret.modalname != 'undefined' || typeof ret.redirect != 'undefined') {
            if(typeof ret.modalname != 'undefined'){
              $('#'+ret.modalname).modal('show');
            }

            if (typeof ret.redirect != 'undefined'){
              window.location = ret.redirect;
            }
          }else{
            alert('error');
          }

      },
      success     : function(ret){
        // console.log($('#'+$id_form).serialize());

        $(x).attr('disabled',false);
        $(x).text(btnname);
          // console.log(ret);
        if (ret.error == 1 ) {
          // alert(ret.msg);
          // if(typeof ret.parsley != 'undefined'){
          //   alert(ret.parsley_message);
          // }
          if(typeof ret.msg != 'undefined'){
            alert(ret.msg);
          }
          
         
          if(typeof ret.modalname != 'undefined'){
            $('#'+ret.modalname).modal('show');
          }
          if (typeof ret.redirect != 'undefined'){
            window.location = ret.redirect;
          }
 
        }else{


           if(typeof ret.modalclose != 'undefined'){
            // jQuery.noConflict();
            $('#'+ret.modalclose).modal('hide');
          }

          if(typeof ret.clearform != 'undefined'){
            $('#'+$id_form)[0].reset();
          }

          if(typeof ret.modalname != 'undefined'){
            $('#'+ret.modalname).modal('show');
          }

          if (typeof ret.redirect != 'undefined'){
            window.location = ret.redirect;
          }

        }        

      }
    })


  }
  return false;

}  
$('#btn-submit-konten').click(function(){
    // loading();
    if ($('#form1').parsley().validate()) {    
      submit_form();
    }
    return false;
  });

  function submit_form() {    
    // var back_url = $(this).attr('data-back') || '';
    $('#form1').ajaxSubmit({
      url       : $('#form1').attr('action'),
      type      : 'post',
      dataType    : 'json',
      error: function () {
        alert('error');
      },
      success     : function(ret){
        if (ret.error == 1 ) {
          // console.log(ret.msg);
          // alert(ret.msg);
          if(typeof ret.msg != 'undefined'){
            alert(ret.msg);
          }
          if(typeof ret.modalname != 'undefined'){
            $('#'+ret.modalname).modal('show');
          }
          if (typeof ret.redirect != 'undefined'){
            window.location.href = ret.redirect;
          }
          return false;

        }else{

          if(typeof ret.modalname != 'undefined'){
            $('#'+ret.modalname).modal('show');
          }

          if (typeof ret.redirect != 'undefined'){
            window.location.href = ret.redirect;
          }

        }
    },
    });
  }

$('a .modal-send-invoice').click(function(){  
  var member_id = $(this).attr('data-id'); 
  $('#modal-id-member').val(member_id);
  $('#modal-send-invoice').modal('show');
});

// $('a .modal-send-invoice-extend').click(function(){  
//   var member_id = $(this).attr('data-id'); 
//   $('#modal-id-member').val(member_id);
//   $('#modal_send_invoice_extend').modal('show');
// });


$(document).ready( function() {
  $(document).on('change', '.btn-file :file', function() {
    var input = $(this),
    label     = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [label]);
  });

  $('.btn-file :file').on('fileselect', function(event, label) {

    var input = $(this).parents('.input-group').find(':text'),
    log = label;

    if( input.length ) {
      input.val(log);
    } else {
      if( log ) alert(log);
    }

  });
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $(input).parent().parent().parent().parent().find('img').attr('src', e.target.result);
        // $('#img-upload')
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#imgInp").change(function(){
    readURL(this);
  }); 
  
  $("#imgInpre").change(function(){
    readURL(this);
  });   

  });

  $('.save-committe').click(function(e){
    e.preventDefault();
    // console.log($('#committee_data').serialize());
    $.ajax({
      url         : base_url_lang+'member/save_member_sector_committee/',
      type        : "POST",
      data        : $('#committee_data').serialize(),
      dataType    : 'json',
      success     : function(ret){
        // console.log(ret);
        alert('Committee data successfully updated');
        window.location.href=this_controller+'member/profile';
      }
    })
        return false;
  });

  $('.save-sector').click(function(e){
    e.preventDefault();
    if ($('#sector_data').parsley().validate()) {
      $.ajax({
        url         : base_url_lang+'member/save_member_sector_committee/',
        type        : "POST",
        data        : $('#sector_data').serialize(),
        dataType    : 'json',
        success     : function(ret){
          alert('Sector data successfully updated');
          window.location.href=this_controller+'member/profile';
        }
      })
    }
        return false;
  });

$(document).ready(function(){
  $('.sector').change(function() {
    $(document).on('change', '.sector[id=66]',function(){
      if ($(".sector[id=66]").prop("checked")) {
        $("input[name=other-sector-name]").slideDown();
      }else{
        $("input[name=other-sector-name]").slideUp();
      }
    })

  var maxCheckedSector = 3 
  var countCheckedSector = document.querySelectorAll('input[name="id_sector[]"]:checked').length;

  if (countCheckedSector > maxCheckedSector){
    // console.log('enough');
    this.checked = false; 
      event.preventDefault();
      alert('Maximal 3 Sector You Are Choosen')
  } else {
    // console.log('more than');
  }

  });
});

$(document).on('click',".open-modal",function(){
  id = $(this).attr('id-modal');
  $('#'+id).modal('show');
})

$(document).on('click',".partners_click",function(){
  id = $(this).attr('idcom');
  $.ajax({
    url: base_url_lang + 'member/partners_click',
    type: "POST",
    dataType:'json',
    data: {'idcom':id},
    success: function (argument) {
      // alert('cek database gan ')
    },
    error: function (argument) {
      console.log('error click hits')
    }
  })

})

// jquery number
!function(e){"use strict";function t(e,t){if(this.createTextRange){var a=this.createTextRange();a.collapse(!0),a.moveStart("character",e),a.moveEnd("character",t-e),a.select()}else this.setSelectionRange&&(this.focus(),this.setSelectionRange(e,t))}function a(e){var t=this.value.length;if(e="start"==e.toLowerCase()?"Start":"End",document.selection){var a,i,n,l=document.selection.createRange();return a=l.duplicate(),a.expand("textedit"),a.setEndPoint("EndToEnd",l),i=a.text.length-l.text.length,n=i+l.text.length,"Start"==e?i:n}return"undefined"!=typeof this["selection"+e]&&(t=this["selection"+e]),t}var i={codes:{46:127,188:44,109:45,190:46,191:47,192:96,220:92,222:39,221:93,219:91,173:45,187:61,186:59,189:45,110:46},shifts:{96:"~",49:"!",50:"@",51:"#",52:"$",53:"%",54:"^",55:"&",56:"*",57:"(",48:")",45:"_",61:"+",91:"{",93:"}",92:"|",59:":",39:'"',44:"<",46:">",47:"?"}};e.fn.number=function(n,l,s,r){r="undefined"==typeof r?",":r,s="undefined"==typeof s?".":s,l="undefined"==typeof l?0:l;var u="\\u"+("0000"+s.charCodeAt(0).toString(16)).slice(-4),h=new RegExp("[^"+u+"0-9]","g"),o=new RegExp(u,"g");return n===!0?this.is("input:text")?this.on({"keydown.format":function(n){var u=e(this),h=u.data("numFormat"),o=n.keyCode?n.keyCode:n.which,c="",v=a.apply(this,["start"]),d=a.apply(this,["end"]),p="",f=!1;if(i.codes.hasOwnProperty(o)&&(o=i.codes[o]),!n.shiftKey&&o>=65&&90>=o?o+=32:!n.shiftKey&&o>=69&&105>=o?o-=48:n.shiftKey&&i.shifts.hasOwnProperty(o)&&(c=i.shifts[o]),""==c&&(c=String.fromCharCode(o)),8!=o&&45!=o&&127!=o&&c!=s&&!c.match(/[0-9]/)){var g=n.keyCode?n.keyCode:n.which;if(46==g||8==g||127==g||9==g||27==g||13==g||(65==g||82==g||80==g||83==g||70==g||72==g||66==g||74==g||84==g||90==g||61==g||173==g||48==g)&&(n.ctrlKey||n.metaKey)===!0||(86==g||67==g||88==g)&&(n.ctrlKey||n.metaKey)===!0||g>=35&&39>=g||g>=112&&123>=g)return;return n.preventDefault(),!1}if(0==v&&d==this.value.length?8==o?(v=d=1,this.value="",h.init=l>0?-1:0,h.c=l>0?-(l+1):0,t.apply(this,[0,0])):c==s?(v=d=1,this.value="0"+s+new Array(l+1).join("0"),h.init=l>0?1:0,h.c=l>0?-(l+1):0):45==o?(v=d=2,this.value="-0"+s+new Array(l+1).join("0"),h.init=l>0?1:0,h.c=l>0?-(l+1):0,t.apply(this,[2,2])):(h.init=l>0?-1:0,h.c=l>0?-l:0):h.c=d-this.value.length,h.isPartialSelection=v==d?!1:!0,l>0&&c==s&&v==this.value.length-l-1)h.c++,h.init=Math.max(0,h.init),n.preventDefault(),f=this.value.length+h.c;else if(45!=o||0==v&&0!=this.value.indexOf("-"))if(c==s)h.init=Math.max(0,h.init),n.preventDefault();else if(l>0&&127==o&&v==this.value.length-l-1)n.preventDefault();else if(l>0&&8==o&&v==this.value.length-l)n.preventDefault(),h.c--,f=this.value.length+h.c;else if(l>0&&127==o&&v>this.value.length-l-1){if(""===this.value)return;"0"!=this.value.slice(v,v+1)&&(p=this.value.slice(0,v)+"0"+this.value.slice(v+1),u.val(p)),n.preventDefault(),f=this.value.length+h.c}else if(l>0&&8==o&&v>this.value.length-l){if(""===this.value)return;"0"!=this.value.slice(v-1,v)&&(p=this.value.slice(0,v-1)+"0"+this.value.slice(v),u.val(p)),n.preventDefault(),h.c--,f=this.value.length+h.c}else 127==o&&this.value.slice(v,v+1)==r?n.preventDefault():8==o&&this.value.slice(v-1,v)==r?(n.preventDefault(),h.c--,f=this.value.length+h.c):l>0&&v==d&&this.value.length>l+1&&v>this.value.length-l-1&&isFinite(+c)&&!n.metaKey&&!n.ctrlKey&&!n.altKey&&1===c.length&&(p=d===this.value.length?this.value.slice(0,v-1):this.value.slice(0,v)+this.value.slice(v+1),this.value=p,f=v);else n.preventDefault();f!==!1&&t.apply(this,[f,f]),u.data("numFormat",h)},"keyup.format":function(i){var n,s=e(this),r=s.data("numFormat"),u=i.keyCode?i.keyCode:i.which,h=a.apply(this,["start"]),o=a.apply(this,["end"]);0!==h||0!==o||189!==u&&109!==u||(s.val("-"+s.val()),h=1,r.c=1-this.value.length,r.init=1,s.data("numFormat",r),n=this.value.length+r.c,t.apply(this,[n,n])),""===this.value||(48>u||u>57)&&(96>u||u>105)&&8!==u&&46!==u&&110!==u||(s.val(s.val()),l>0&&(r.init<1?(h=this.value.length-l-(r.init<0?1:0),r.c=h-this.value.length,r.init=1,s.data("numFormat",r)):h>this.value.length-l&&8!=u&&(r.c++,s.data("numFormat",r))),46!=u||r.isPartialSelection||(r.c++,s.data("numFormat",r)),n=this.value.length+r.c,t.apply(this,[n,n]))},"paste.format":function(t){var a=e(this),i=t.originalEvent,n=null;return window.clipboardData&&window.clipboardData.getData?n=window.clipboardData.getData("Text"):i.clipboardData&&i.clipboardData.getData&&(n=i.clipboardData.getData("text/plain")),a.val(n),t.preventDefault(),!1}}).each(function(){var t=e(this).data("numFormat",{c:-(l+1),decimals:l,thousands_sep:r,dec_point:s,regex_dec_num:h,regex_dec:o,init:this.value.indexOf(".")?!0:!1});""!==this.value&&t.val(t.val())}):this.each(function(){var t=e(this),a=+t.text().replace(h,"").replace(o,".");t.number(isFinite(a)?+a:0,l,s,r)}):this.text(e.number.apply(window,arguments))};var n=null,l=null;e.isPlainObject(e.valHooks.text)?(e.isFunction(e.valHooks.text.get)&&(n=e.valHooks.text.get),e.isFunction(e.valHooks.text.set)&&(l=e.valHooks.text.set)):e.valHooks.text={},e.valHooks.text.get=function(t){var a,i=e(t),l=i.data("numFormat");return l?""===t.value?"":(a=+t.value.replace(l.regex_dec_num,"").replace(l.regex_dec,"."),(0===t.value.indexOf("-")?"-":"")+(isFinite(a)?a:0)):e.isFunction(n)?n(t):void 0},e.valHooks.text.set=function(t,a){var i=e(t),n=i.data("numFormat");if(n){var s=e.number(a,n.decimals,n.dec_point,n.thousands_sep);return e.isFunction(l)?l(t,s):t.value=s}return e.isFunction(l)?l(t,a):void 0},e.number=function(e,t,a,i){i="undefined"==typeof i?"1000"!==new Number(1e3).toLocaleString()?new Number(1e3).toLocaleString().charAt(1):"":i,a="undefined"==typeof a?new Number(.1).toLocaleString().charAt(1):a,t=isFinite(+t)?Math.abs(t):0;var n="\\u"+("0000"+a.charCodeAt(0).toString(16)).slice(-4),l="\\u"+("0000"+i.charCodeAt(0).toString(16)).slice(-4);e=(e+"").replace(".",a).replace(new RegExp(l,"g"),"").replace(new RegExp(n,"g"),".").replace(new RegExp("[^0-9+-Ee.]","g"),"");var s=isFinite(+e)?+e:0,r="",u=function(e,t){return""+ +(Math.round((""+e).indexOf("e")>0?e:e+"e+"+t)+"e-"+t)};return r=(t?u(s,t):""+Math.round(s)).split("."),r[0].length>3&&(r[0]=r[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,i)),(r[1]||"").length<t&&(r[1]=r[1]||"",r[1]+=new Array(t-r[1].length+1).join("0")),r.join(a)}}(jQuery);
//# sourceMappingURL=jquery.number.min.js.map
//
$('.form-search').on('click',function(){
  $(this).find('.form-search-page').find('input').focus()
})