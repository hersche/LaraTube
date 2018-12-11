var baseUrl:string;

class siteManager {

  medias:Array<Media>;
  currentPage:string;
  sites:Array<site>;
  currentSite:site;
  constructor(base:string){
    baseUrl = base;
    this.currentPage = "overview";
    this.sites = [];
    this.currentSite = new overviewSite();
  }

  getCurrentSite(){
    return this.currentPage;
  }

  changeSite(site:string,theValue:string){
    //if(this.sites[site]==undefined){
    console.log("changeSite: "+site);
    if(site=="player"){
      new playerSite(theValue);
    } else {
      new overviewSite();
    }
    //}
  }

  buildSite(){
    //if(this.currentPage=="overview"){
      //this.sites['overview']
    //}
  }

};

class site {
  title:string;
  htmlSkeleton:string;
  constructor(title:string){
    this.title = title;
  }

  build(){

  }

  destroy(){

  }

};


class overviewSite extends site {

  private medias:Array<Media>;

  constructor(){
    super("Overview");
    this.receiveMedias(this.createContent);
  }

  receiveMedias(createContentCallback: (dataList: Array<Media>) => void = null):void{
    var that = this;
    $.getJSON("/api/media", function name(data) {
      that.medias = [];
        $.each( data.data, function( key, value ) {
          that.medias.push(new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, value.user_id,value.created_at,value.created_at_readable));
        });
        createContentCallback(that.medias);
    });
  }
  createContent(data:Array<Media>):void{
    $("#mainContent").html("");
    var carouselHtml = "";
    var carouselIndicatorsHtml = "";
    var items = "";
    var first = true;
    var firstHtml = " active ";
    $.each( data, function( key, val1 ) {
      carouselHtml += '<div class="carousel-item bg-dark '+firstHtml+'"><img src="/'+ val1.poster_source + '" alt="'+val1.title+'"><div class="carousel-caption" style="color: black; background: lightgrey; opacity:0.9;"><h3>'+val1.title+' ('+val1.created_at_readable+')</h3><p>'+val1.description+'<span class="float-right"><a id="'+key+'CaroselPlay" class="btn btn-primary mr-2" >Play</a></span></p></div></div>';
      carouselIndicatorsHtml += '<li data-target="#demo" data-slide-to="0" class="'+firstHtml+'"></li>';
      items += '<div style="min-width: 300px;" class="col-lg-4 col-md-4 col-xs-6 card"><a href="/media/'+val1.title+'" class="d-block h-100"><img class="card-img-top" src="/'+ val1.poster_source + '" alt=""><div class="card-img-overlay"><h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">'+val1.title+'</h4></div></a></div>';
      if(first){
        firstHtml = "";
        first = false;
      }
    });
    var finalCarouselHtml = '<h3>Newest videos</h3><div id="demo" class="carousel slide" data-ride="carousel"><ul class="carousel-indicators" id="carouselIndicatorsBody">'+carouselIndicatorsHtml+'</ul><div class="carousel-inner" id="carouselInnerBody">'+carouselHtml+'</div><a class="carousel-control-prev bg-dark" href="#demo" data-slide="prev"><span class="carousel-control-prev-icon"></span></a><a class="carousel-control-next bg-dark" href="#demo" data-slide="next"><span class="carousel-control-next-icon"></span></a></div>';
    $("#mainContent").html(finalCarouselHtml);
    $.each( data, function( key, val1 ) {
      $('#'+key+'CaroselPlay').on("click",function(){
        sm.changeSite("player",val1.title);
      })
    });
  }

};
class playerSite extends site {

  private medias:Array<Media>;
  constructor(title:string){
    super(title);
    this.receiveMedias(this.createContent);
  }

  receiveMedias(createContentCallback: (dataList: Array<Media>) => void = null):void{

    var that = this;
    $.getJSON("/api/media/"+that.title, function name(data) {
      that.medias = [];
        $.each( data, function( key, value ) {
          that.medias.push(new Media(value.title, value.description, value.source, value.poster_source, value.simpleType, value.type, value.user_id,value.created_at,value.created_at_readable));
        });
        createContentCallback(that.medias);
    });
  }

  createContent(data:Array<Media>):void{
    $("#mainContent").html("");
    var carouselHtml = '';
    var first = true;
    var finalCarouselHtml;
    $.each( data, function( key, val1 ) {
      if(first){
        if(val1.simpleType=="audio"){
          carouselHtml += '<audio width="320" height="240" controls poster="/'+val1.poster_source + '"><source src="'+val1.source + '" type="audio/mp3"></audio>';
        } else if(val1.simpleType=="video"){
          carouselHtml += '<video width="320" height="240" controls poster="/'+val1.poster_source + '"><source src="'+val1.source + '" type="video/mp4"></video>';
        }
        finalCarouselHtml = '<h3>'+val1.title+'</h3><div>'+carouselHtml+'</div>';
        $("#mainContent").html(finalCarouselHtml);
        $("#mainMenu").html('<a class="btn btn-primary" id="returnBtn">Go back</a>');
        $("#returnBtn").on("click", function(){
          sm.changeSite("overview", "");
        });
      }
      if(first){
        first = false;
      }
    });


  }

};
class Media {
  title:string;
  description:string;
  source:string;
  poster_source:string;
  type:string;
  simpleType:string;
  user_id:number;
  created_at:string;
  created_at_readable:string;


  constructor(title:string,description:string,source:string,poster_source:string,simpleType:string,type:string,user_id:number,created_at:string,created_at_readable:string){
    this.title = title;
    this.description = description;
    this.source = source;
    this.poster_source = poster_source;
    this.type = type;
    this.simpleType = simpleType;
    this.user_id = user_id;
    this.created_at = created_at;
    this.created_at_readable = created_at_readable;
  }
/*

@if ($media->type=="localVideo" || $media->type=="directVideo"|| $media->type=="torrentVideo")
  <video class="col-12" id="player" poster="{{ url($media->poster()) }}" playsinline controls>
    @if ($media->type=="localVideo" || $media->type=="directVideo")
      <source src="{{ url($media->source) }}" type="video/mp4">
    @endif
  </video>

*/


  getMediaTag(){
    var tmpHtml = "";
    if(this.type=="localVideo"||this.type=="directVideo"||this.type=="torrentVideo"){
      tmpHtml += '<video class="col-12" id="player" poster="{{ url($media->poster()) }}" playsinline controls>';
      if(this.type=="localVideo"||this.type=="directVideo"){
        tmpHtml += '<source src="'+baseUrl+this.source+'" >';
      }
      return tmpHtml+"</video>";
    }

    if(this.type=="localAudio"||this.type=="directAudio"||this.type=="torrentAudio"){
      tmpHtml += '<audio class="col-12" id="player" poster="{{ url($media->poster()) }}" playsinline controls>';
      if(this.type=="localAudio"||this.type=="directAudio"){
        tmpHtml += '<source src="'+baseUrl+this.source+'" >';
      }
      return tmpHtml+"</audio>";
    }
  }

  createEditModal(containerId:string){
    var tmpHtml = "";
  }
};
if(sm==undefined){
  var sm;
}
export function init() {
    sm = new siteManager(baseUrl);
}
