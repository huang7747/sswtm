
    function addTab(that){
     var id=$(that).attr('data-id');
     if(!$('.main-content .breadcrumb a#'+id).text()){
       var parentLi=$(that).parent();
       var cloneLi=parentLi.clone();
       $(cloneLi).find('a').attr('id',id).attr('onclick','');
       $(cloneLi).find('a>i').remove();
       $(cloneLi).find('a').append("<i class='icon-remove red' onclick='return remove(this,event)'></i>");
       $('.main-content .breadcrumb').append(cloneLi);
     }else{
         console.log('111');
     }
    };
    function remove(that,event){
        var parentLi=$(that).parent('a').parent('li');
        var url     =parentLi.prev().find('a').attr('href');
            parentLi.remove();
            window.location.href=url;
            return false;
    };

    $(".nav-list").on('click','li' ,function (event) {
        if($(this).children('ul')) $(this).children('ul').slideToggle(200);
        stopPro(event);
     });

  function stopPro(event){
     return  window.event.cancelBubble ? window.event.cancelBubble = true : event.stopPropagation();//冒泡處理
  };

  function gourl(url){
        window.location.href=url;
    }
