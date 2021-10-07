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
  </div>
</template>

<script>
import axios from '@/supports/axios'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'

export default {
  name: 'user',
  components: {
    TabView,
    TabPanel
  },
  data () {
    return {
      id: null,
      user: {}
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
            })
        })
        .catch((err) => {
          alert(err)
        })
    }
  }
}
</script>
