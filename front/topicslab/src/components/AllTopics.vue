<template>
  <div v-if="topics !== null">
    <Card v-for="topic in topics" :key="topic.id">
        <template #content>
          <span class="topic-date">投稿日：{{moment(topic.created_at)}}</span>
          <h2>
            <router-link style="text-decoration:none;" :to="`/topic/${topic.id}`">
              {{topic.title}}
            </router-link>
          </h2>
        </template>
    </Card>
    <!--ダイアログ表示-->
    <Dialog header="エラー" v-model:visible="displayBasic" :style="{width: '50vw'}">
      {{message}}
      <template #footer>
        <Button label="はい" icon="pi pi-check" @click="closeBasic" autofocus />
      </template>
    </Dialog>
  </div>
  <div v-else>
    <Card>
      <template #content>
        <Skeleton />
        <h2><Skeleton /></h2>
      </template>
    </Card>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import moment from 'moment'
import Skeleton from 'primevue/skeleton'
import Dialog from 'primevue/dialog'

export default {
  name: 'AllTopics',
  components: {
    Skeleton,
    Dialog
  },
  data () {
    return {
      topics: [],
      displayBasic: false,
      message: ''
    }
  },
  mounted () {
    this.getAllTopics()
  },
  methods: {
    moment: function (date) {
      return moment(date).format('YYYY/MM/DD HH:mm:SS')
    },
    getAllTopics () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/topics')
            .then((res) => {
              if (res.status === 200) {
                this.topics.splice(0)
                this.topics.push(...res.data)
              } else {
                console.log('取得失敗')
                this.displayBasic = true
                this.message = '接続に失敗しました。'
              }
            })
        })
        .catch((err) => {
          console.log(err)
          this.displayBasic = true
          this.message = '接続に失敗しました。'
        })
    },
    closeBasic () {
      this.displayBasic = false
    }
  }
}
</script>

<style lang="scss" scoped>
  .p-card.p-component {
    margin-bottom: 20px;
  }
</style>
