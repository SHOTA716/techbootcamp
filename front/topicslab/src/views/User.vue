<template>
  <div example1>
    <Card >
      <template #content>
        {{user.name}}
        <TabView>
          <TabPanel header="Topics">
            {{user.topics}}
          </TabPanel>
          <TabPanel header="コメント">
            <Fieldset v-for="comment in comments" :key="comment.id">
              <template #legend>
                <span>
                  {{user.comments}}
                </span>
              </template>
              <div class="comment-text">
                {{comment.body}}
              </div>
            </Fieldset>
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
