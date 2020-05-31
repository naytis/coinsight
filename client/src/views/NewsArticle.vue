<template>
  <v-row justify="center">
    <v-col cols="8" class="mt-4">
      <spinner v-if="isArticleLoading" />
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
import Spinner from '../components/common/Spinner';
import {SHOW_ERROR_MESSAGE} from '../store/notification/types';
import {mapActions} from 'vuex';

export default {
  name: 'NewsArticle',

  components: {
    Spinner,
  },

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
    ...mapActions('notification', {
      showErrorMessage: SHOW_ERROR_MESSAGE,
    }),

    async getNewsArticle() {
      this.isArticleLoading = true;
      try {
        let result = await getNewsArticleById(this.$route.params.id);
        this.article = result.data;
      } catch (e) {
        this.showErrorMessage(e);
      }
      this.isArticleLoading = false;
    },
  },

  filters: {
    convertNewLines(text) {
      console.log(text);
      return text.replace('\n', '<br />');
    },
  },
};
</script>

<style scoped></style>
