<template>
  <div>
    <Card>
      <template #title>
        <div v-if="topic.id !== undefined">
          {{topic.title}}
        </div>
        <div v-else>
          <Skeleton width="523px" height="37px" />
        </div>
      </template>
      <template #content>
        <div class="user_name" v-if="topic.id !== undefined">
          <span v-if="user === null">
            退会済みユーザ
          </span>
          <span v-else>
            <div v-if="user !== null">
                <Avatar icon="pi pi-user" class="p-mr-2" size="large" shape="circle" />
                <router-link class="user_link" style="text-decoration:none;" :to="`/user/${user.id}`">{{user.name}}</router-link>
            </div>
          </span>
        </div>
        <div v-else class="hide_name">
          <Skeleton width="105px" height="48px" />
        </div>
        <div class="body-text" v-if="topic.id !== undefined">
          {{topic.body}}
        </div>
        <div v-else>
          <Skeleton width="523px" height="40px" />
        </div>
      </template>
      <template #footer>
        <div v-if="topic.id !== undefined">
          <Button label="いいね" class="p-button-rounded p-button-danger" icon="pi pi-heart" iconPos="right" />
        </div>
        <div v-else>
          <Skeleton class="nice_btn" width="106px" height="42px" />
        </div>
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
import Skeleton from 'primevue/skeleton'
import Dialog from 'primevue/dialog'
import Avatar from 'primevue/avatar'

export default {
  name: 'Topic',
  components: {
    Comments,
    CommentForm,
    Skeleton,
    Dialog,
    Avatar
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

    submit () {
      const profile = this.profile.trim()
      if (!profile) {
        this.messages.submit = '未記入(空白のみ)は送信できません。'
        return
      }

      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/profile', {
            profile: this.profile
          })
            .then((res) => {
              if (res.status === 201) {
                this.$emit('sentComment', res.data)
              } else {
                this.messages.connect = '送信に失敗しました。'
              }
            })
            .catch((err) => {
              console.log(err)
              this.displayBasic = true
              this.messages.connect = '送信に失敗しました。'
            })
        })
        .catch((err) => {
          console.log(err)
          this.displayBasic = true
          this.messages.connect = '送信に失敗しました。'
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
  margin: 80px 0 0 10px;
}
.p-card-footer span {
  text-align: right;
  display: block;
}
Button{
  margin-top: 2%;
  margin-left: 407px;
}
.user_name{
  float: left;
}
.user_link{
  margin-left: 10px;
}
.hide_name{
  float: left;
  position: relative;
  top: -20px;
}
.nice_btn{
  float: right;
  position: relative;
  top: -30px;
  left: -5px;
}
</style>
