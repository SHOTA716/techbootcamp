<template>
  <div>
    <Card>
      <template #content>
        <TabView>
          <TabPanel header="Topics">
            <div v-if="user.topics !== undefined">
              <div v-for="(topic,key) in user.topics" :key="key">
                {{topic.title}}
                <div class="fortopic">
                    <router-link　style="text-decoration:none;" :to="`/topic/${topic.id}`">
                      <Button label="トピックへ移動" class="p-button-success"><i class="pi pi-external-link"></i>トピックへ移動</Button>
                    </router-link>
                  </div>
              </div>
            </div>
            <div v-else>
              <Skeleton />
            </div>
          </TabPanel>
          <TabPanel header="あなたのコメント">
            <div v-if="user.topics !== undefined">
              <div v-for="(comment,key) in user.comments" :key="key">
                {{comment.body}}
                <div class="fortopic">
                  <router-link　style="text-decoration:none;" :to="`/topic/${comment.topic_id}`">
                  <Button label="トピックへ移動" class="p-button-success"><i class="pi pi-external-link"></i>トピックへ移動</Button>
                  </router-link>
                </div>
              </div>
            </div>
            <div v-else>
              <Skeleton />
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
    TabView,
    TabPanel,
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

<style>
a {
  text-decoration: none;
}
  .fortopic {
    text-align: right;
    margin-top: 15px;
  }

  .topic-title, .comments-body {
    border:1px solid #f5f5f5;
    border-radius: 10px;
    padding: 10px 10px;
    margin-bottom: 20px;
    box-shadow: 0 3px 7px 0 rgba(0, 0, 0, .3);
  }
    .p-button-success {
      border-radius: 10px;
    }
</style>
