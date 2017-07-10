<template>
  <ul class="pagination" v-if="pageData.total > pageData.per_page">
    <li v-if="pageData.prev_page_url">
      <a aria-label="Previous" @click="changePage(pageData.prev_page_url, pageData.current_page-1)">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li v-for="page in pages" :class="{ 'active': page.page == pageData.current_page }">
      <a @click="changePage(page.url, page.page)" v-if="page.page != pageData.current_page"> {{ page.page}}</a>
      <a v-else="page.page == pageData.current_page"> {{ page.page }}</a>
    </li>
    <li v-if="pageData.next_page_url">
      <a aria-label="Next" @click="changePage(pageData.next_page_url, pageData.current_page+1)">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</template>
<style>

</style>
<script>
import { setQuery, parseUrl } from '../../app/utils'

export default {
  props: {
    url: {
      type: String,
      default: ''
    },
    onPageChanges: {
      type: Function,
      default: data => {

      }
    },
    beforePageChanges: {
      type: Function,
      default: data => {

      }
    }
  },
  created() {

  },
  data() {
    return {
      pages: [],
      pageData: {
        current_page: 1,
        data: [],
        from: 1,
        last_page: 1,
        next_page_url: null,
        per_page: 10,
        prev_page_url: null,
        to: 1,
        total: 0,
      },
      thisUrl: ''
    }
  },
  components: {},
  methods: {
    generatePages() {
      if (this.pageData.last_page < 2) {
        return;
      }
      let pages, index, maxPage, start, distance;
      distance = 4;
      pages = [];
      pages.push({
        url: this.getUrl(1),
        page: 1
      });
      start = this.pageData.current_page - distance;
      if (start > 2) {
        pages.push({
          url: this.getUrl(start - 1),
          page: '...'
        })
      } else {
        start = 2;
      }
      maxPage = this.pageData.current_page + distance;

      if (maxPage > this.pageData.last_page - 1) {
        maxPage = this.pageData.last_page - 1;
      }

      for (index = start; index < maxPage + 1; index++) {
        let page = {
          url: this.getUrl(index),
          page: index
        };
        pages.push(page);
      }
      if (this.pageData.last_page - maxPage > 1) {
        pages.push({
          url: this.getUrl(maxPage + 1),
          page: '...'
        });
      }
      pages.push({
        url: this.getUrl(this.pageData.last_page),
        page: this.pageData.last_page
      });

      this.pages = pages;
      return pages;
    },
    getUrl(page) {
      return setQuery(this.thisUrl, {
        page: page
      });
    },
    changePage(url, page) {
      this.thisUrl = url;
      let self = this;
      self.beforePageChanges(page);
      axios.get(url).then(response => {
        self.pageData = response.data;
        self.generatePages();
        self.onPageChanges(response.data.data)
      }).catch(error => toastrNotification('error', error.response.data.message))
    },
    sort(sort) {
      let url = setQuery(this.thisUrl, {
        order: sort
      });
      this.changePage(url);
    },
    refresh() {
      let url = this.url.split('?', 2)[0];
      this.changePage(url)
    },
    query(query) {
      let url = setQuery(this.thisUrl, query);
      this.changePage(url);
    }
  },
  mounted() {
    vueApp.$on('page.refresh', this.refresh);
    vueApp.$on('page.reset', this.changePage);
    vueApp.$on('page.sort', this.sort);
    vueApp.$on('page.query', this.query);
    this.changePage(this.url);
  },
  beforeDestroy: function () {
    vueApp.$off('page.reset');
    vueApp.$off('page.refresh');
    vueApp.$off('page.sort');
    vueApp.$off('page.query');
  }
}
</script>