<template>
  <div>
    <Card>
      <template #content>
        <TabView>
          <TabPanel header="Topics">
            <div v-for="(topic,key) in user.topics" :key="key">
              {{topic.title}}
              <span>
                <router-link :to="`/topic/${topic.id}`">トピックへ移動</router-link>
              </span>
            </div>
          </TabPanel>
          <TabPanel header="あなたのコメント">
            <div v-for="(comment,key) in user.comments" :key="key">
              {{comment.body}}
              <span>
                <router-link :to="`/topic/${comment.topic_id}`">トピックへ移動</router-link>
              </span>
            </div>
          </TabPanel>
        </TabView>
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
  <Skeleton />
</template>

<script>
import axios from '@/supports/axios'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Skeleton from 'primevue/skeleton'
import Dialog from 'primevue/dialog'

export default {
  name: 'user',
  components: {
    Skeleton,
    Dialog
  },
  data () {
    return {
      id: null,
      displayBasic: false,
      user: {},
      message: ''
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
    this.getUser()
  },
  methods: {
    getUser () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get(`/api/user/${this.id}`)
            .then((res) => {
              console.log(res)
              if (res.status === 200) {
                this.user = res.data
              } else {
                console.log('取得失敗')
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
    closeBasic () {
      this.displayBasic = false
    }
  }
}
</script>
