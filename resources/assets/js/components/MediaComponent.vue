<div style="min-width: 300px;" class="col-lg-4 col-md-4 col-xs-6 card" v-for="media in medias">
  <a href="media/{{ media.title }}" class="d-block h-100">
    <img class="card-img-top" src="{{ media.poster_source }}" alt="">
      <div class="card-img-overlay">
        <h4 class="card-title bg-secondary text-info" style="opacity: 0.9;">{{ media.title }}</h4>
      </div>
  </a>
</div>
