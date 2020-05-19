<template>
  <v-row justify="center">
    <v-col cols="8" class="mt-4">
      <v-overlay absolute :value="isArticleLoading">
        <v-progress-circular
          size="60"
          indeterminate
          color="primary"
        ></v-progress-circular>
      </v-overlay>
      <div v-if="!isArticleLoading">
        <div class="display-1">{{ article.title }}</div>
        <div class="text--disabled body-2">
          {{ article.author }} ‧
          {{ article.publishedAt | dateFromNow }}
        </div>
        <div
          class="mt-4"
          v-html="article.content"
          style="white-space: pre-wrap;"
        >
          {{ article.content }}
        </div>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import {getNewsArticleById} from '../api/news';
import {dateFromNow} from '../filters';

export default {
  name: 'NewsArticle',

  data() {
    return {
      isArticleLoading: false,
      article: [],
    };
  },

  mounted() {
    this.getNewsArticle();
  },

  methods: {
    async getNewsArticle() {
      this.isArticleLoading = true;
      try {
        let result = await getNewsArticleById(this.$route.params.id);
        this.article = result.data;
      } catch (e) {
        alert(e);
      }
      this.isArticleLoading = false;
    },
  },

  filters: {
    dateFromNow(date) {
      return dateFromNow(date);
    },

    convertNewLines(text) {
      console.log(text);
      return text.replace('\n', '<br />');
    },
  },
};
</script>

<style scoped></style>
