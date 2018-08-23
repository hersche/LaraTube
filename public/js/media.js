function doLikeMarking(type,id=0){
  if(type=="media"){
    if(myLike=="1"){
        if($("#like").hasClass("btn-primary")){
          $("#like").removeClass("btn-primary");
          $("#like").addClass("btn-success");
        }
    }
    if(myLike=="-1"){
        if($("#dislike").hasClass("btn-primary")){
          $("#dislike").removeClass("btn-primary");
          $("#dislike").addClass("btn-success");
        }
    }
  }
}


$( document ).ready(function() {

doLikeMarking("media");

const player = new Plyr('#player');
getAutoplay();

});
