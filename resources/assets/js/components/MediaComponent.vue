<template>
  <div>
    <div v-for="(item,index) in medias"  v-if="item.title==$route.params.currentTitle">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <span class="float-right">
            <router-link to="/" class="btn btn-primary">Back</router-link>
          </span>
          <h3> {{ item.title }} </h3>
          <audio id="player" v-if="item.simpleType=='audio'" controls :poster="item.poster_source">
            <source :src="item.source" type="audio/mp3"></source>
          </audio>
          <video class="col-12" id="player" v-else controls :poster="item.poster_source">
            <source :src="item.source" type="video/mp4"></source>
          </video>

        </div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12"></div>
      <div class="card">
        <div class="card-header">
          <span class='h3'>{{ item.title }}</span>
          <div class="float-right">
            <span class="btn btn-info mr-1">{{ item.created_at_readable }}</span>
              <router-link class="btn btn-primary" :to="'/profile/'+item.user.id">
                <img v-if="item.user.avatar==''" class="mx-auto rounded-circle img-fluid" src="/img/404/avatar.png" alt="avatar" style="max-height: 20px;" />
              <img v-else class="mx-auto rounded-circle img-fluid" :src="'/'+item.user.avatar" alt="avatar" style="max-height: 20px;" />
            </router-link>
          </div>
          <div class="card-body">{{ item.description }}</div>
          <div class="card-footer">Tags: <a class="btn btn-xs btn-info mr-1" v-for="tag in item.tags" :href="'/tags/'+tag.name" >{{ tag.name }} ({{ tag.count }}x)</a>

          </div>

        </div>
      </div>
      <div class="comments">
        <div v-for="comment in item.comments" class="comment mb-2 row" :id='"cid"+comment.id'>
            <div class="comment-avatar col-md-1 col-sm-2 text-center pr-1">
                <a href=""><img class="mx-auto rounded-circle img-fluid" :src="'/'+comment.user.avatar" alt="avatar" /></a>
            </div>
            <div class="comment-content col-md-11 col-sm-10">
                <h6 class="small comment-meta"><router-link class="btn btn-primary mr-2" :to="'/profile/'+comment.user.id">{{ comment.user.name }}</router-link> {{ comment.created_at }}
                  <!-- @if (Auth::id() == $comment->user_id)
                    <span class="float-right btn btn-danger" onclick="deleteComment({{ comment.id }});"><ion-icon name="trash"></ion-icon></span>
                  @endif -->
                </h6>
                <div class="comment-body">
                    <p>
                        {{ comment.body }}
                        <br>
                        <a href="" class="text-right small"><i class="ion-reply"></i> Reply</a>
                    </p>
                </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</template>
<script>
  import { eventBus } from '../eventBus.js';
  export default {
    props: ['medias','currentTitle','swapComponent','baseUrl'],
    methods: {
      emitBackClicked(title) {
        eventBus.$emit('playerBackClick',title);
      }
    }
  }
</script>
