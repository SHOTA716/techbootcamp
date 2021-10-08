<template>
  <div>
    <Card>
      <template #title>
        マイページ
      </template>
      <template #content>
        <div v-if="user !== null">
          {{user.name}}
        </div>
        <div v-else>
          <Skeleton />
        </div>
        <TabView>
          <TabPanel header="Topics">
            <div v-if="user !== null">
              {{user.topics}}
            </div>
            <div v-else>
              <Skeleton />
            </div>
          </TabPanel>
          <TabPanel header="あなたのコメント" v-if="user !== null">
            <div v-if="user !== null">
              {{user.comments}}
            </div>
            <div v-else>
              <Skeleton />
            </div>
          </TabPanel>
        </TabView>
        <Button label="トピック作成" v-on:click="toNewTopic" />
      </template>
      <template #footer>
        <Button label="ログアウト" class="p-button-warning" v-on:click="logout" />
        <Button label="退会" class="p-button-danger" v-on:click="withdraw" />
      </template>
    </Card>
  </div>
</template>
<script>
import axios from '@/supports/axios'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Skeleton from 'primevue/skeleton'

export default {
  name: 'Userself',
  components: {
    TabView,
    TabPanel,
    Skeleton
  },
  data () {
    return {
      user: {}
    }
  },
  mounted () {
    if (localStorage.getItem('authenticated') !== 'true') {
      this.$router.push('/login')
      return
    }

    this.getUser()
  },
  methods: {
    toNewTopic () {
      this.$router.push('topic')
    },
    logout () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/logout')
            .then(res => {
              console.log(res)
              localStorage.setItem('authenticated', 'false')
              this.$router.push('/login')
            })
            .catch(err => {
              console.log(err)
            })
        })
        .catch((err) => {
          alert(err)
        })
    },
    withdraw () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('/api/logout')
            .then(res => {
              console.log(res)
              localStorage.setItem('authenticated', 'false')
              this.$router.push('/')
            })
            .catch(err => {
              console.log(err)
            })
        })
        .catch((err) => {
          alert(err)
        })
    },
    getUser () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/mypage')
            .then((res) => {
              if (res.status === 200) {
                this.user = res.data
              } else {
                console.log('取得失敗')
              }
            })
        })
        .catch((err) => {
          alert(err)
        })
    }
  }
}
</script>

<style lang="scss" scoped>
  .p-card-footer {
    .p-button {
      margin-right: 10px;
    }
  }
</style>
