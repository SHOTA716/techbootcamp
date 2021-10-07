<template>
  <div>
    <Card>
      <template #title>
        {{topic.title}}
      </template>
      <template #content>
        <div class="body-text">
          {{topic.body}}
        </div>
        <Button label="いいね" icon="pi pi-heart" iconPos="right" />
      </template>
      <template #footer>
        <span>
          <router-link style="text-decoration:none;" :to="`/user/${user.id}`">{{user.name}}</router-link>
        </span>
      </template>
    </Card>
    <Comments :comments="this.comments" />
    <CommentForm :topicId="this.topic.id" @sentComment="receiveComment" />
    <!--ダイアログ表示-->
    <Dialog header="エラー" v-model:visible="displayBasic" :style="{width: '50vw'}">
      {{message}}
      <template #footer>
        <Button label="はい" icon="pi pi-check" @click="closeBasic" autofocus />
      </template>
    </Dialog>
  </div>
</template>

<script>
import axios from '@/supports/axios'
import Comments from '@/components/Comments'
import CommentForm from '@/components/CommentForm'
import Dialog from 'primevue/dialog'

export default {
  name: 'Topic',
  components: {
    Comments,
    CommentForm,
    Dialog
  },
  data () {
    return {
      topic: {},
      user: {},
      comments: [],
      displayBasic: false,
      message: '',
      id: null
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('/login')
      return
    }

    this.id = this.$route.params.id
    if (!this.id) {
      alert('不正なIDです。')
    }
    this.getTopic()
  },
  methods: {
    getTopic () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get(`/api/topic/${this.id}`)
            .then((res) => {
              if (res.status === 200) {
                this.topic = res.data
                this.user = this.topic.user
                this.comments.splice(0)
                this.comments.push(...this.topic.comments)
              } else {
                console.log('取得失敗')
                this.message = '接続に失敗しました。'
              }
            })
            .catch((err) => {
              console.log(err)
              this.displayBasic = true
              this.message = '接続に失敗しました。'
            })
        })
        .catch((err) => {
          console.log(err)
          this.displayBasic = true
          this.message = '接続に失敗しました。'
        })
    },
    receiveComment (comment) {
      this.comments.push(comment)
    },
    closeBasic () {
      this.displayBasic = false
    }
  }
}
</script>

<style scoped>
.body-text {
  white-space:pre-wrap;
}
.p-card-footer span {
  text-align: right;
  display: block;
}
Button{
  margin-top: 2%;
}
</style>
