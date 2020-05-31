<template>
  <v-row justify="center">
    <v-col cols="8">
      <spinner v-if="isNewsLoading" />
      <div v-if="!isNewsLoading">
        <div class="display-2 my-4">News</div>
        <div v-for="(newsArticle, index) in news" :key="index">
          <router-link
            :to="{name: 'news-article', params: {id: newsArticle.id}}"
          >
            <div class="headline">{{ newsArticle.title }}</div>
            <div class="text--disabled body-2">
              {{ newsArticle.author }} ‧
              {{ newsArticle.publishedAt | dateFromNow }}
            </div>
          </router-link>
          <v-divider class="my-5" />
        </div>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import {getNews} from '../api/news';
import Spinner from '../components/common/Spinner';

export default {
  name: 'News',

  components: {
    Spinner,
  },

  data() {
    return {
      isNewsLoading: false,
      news: [],
    };
  },

  mounted() {
    this.getNews();
  },

  methods: {
    async getNews() {
      this.isNewsLoading = true;
      try {
        let result = await getNews();
        this.news = result.data.news;
      } catch (e) {
        alert(e);
      }
      this.isNewsLoading = false;
    },
  },
};
</script>

<style scoped></style>
