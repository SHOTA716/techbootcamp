<template>
  <div>
    <Card>
      <template #title>
        マイページ
      </template>
      <template #content>
        <Textarea v-model="profile" :autoResize="true" rows="5" cols="58" placeholder="自己紹介を追加"/>
        <div class="create-profile">
          <Button label="プロフィールを公開" icon="pi pi-pencil" v-on:click="submit" />
        </div>
        <div v-if="user !== null">
          {{user.intro}}
        </div>
        <div v-else>
          <Skeleton />
        </div>
         <TabView>
          <TabPanel header="Topics">
            <div v-for="(topic,key) in user.topics" :key="key">
              <div v-if="user !== null">
                <div class="topic-title">
                  {{topic.title}}
                  <div class="fortopic">
                    <router-link :to="`/topic/${topic_id}`">
                      <Button label="トピックへ移動" class="p-button-success"><i class="pi pi-external-link"></i>トピックへ移動</Button>
                    </router-link>
                  </div>
                </div>
              </div>
              <div v-else>
               <Skeleton />
              </div>
            </div>
          </TabPanel>
          <TabPanel header="あなたのコメント">
            <div v-for="(comment,key) in user.comments" :key="key">
              <div v-if="user !== null">
                <div class="comments-body">
                {{comment.body}}
                <div class="fortopic">
                  <router-link :to="`/topic/${comment.topic_id}`">
                  <Button label="トピックへ移動" class="p-button-success"><i class="pi pi-external-link"></i>トピックへ移動</Button>
                  </router-link>
                </div>
                </div>
               </div>
               <div v-else>
                <Skeleton />
               </div>
              </div>
          </TabPanel>
        </TabView>
        <div class="create-topic">
          <Button class="createTopic" label="トピック作成" icon="pi pi-plus" v-on:click="toNewTopic" />
        </div>
      </template>
      <template #footer>
        <div class="logout">
          <Button label="ログアウト" icon="pi pi-sign-out" class="p-button-warning" v-on:click="logout" />
          <Button label="退会" icon="pi pi-trash" class="p-button-danger" v-on:click="withdraw" />
        </div>
      </template>
    </Card>
    <!--ダイアログ表示-->
    <Dialog header="エラー" v-model:visible="displayBasic" :style="{width: '50vw'}">
      {{messages.logout}}
      <template #footer>
        <Button label="はい" icon="pi pi-check" @click="closeBasic" autofocus />
      </template>
    </Dialog>
    <!--ダイアログ表示-->
    <Dialog header="エラー" v-model:visible="displayBasic" :style="{width: '50vw'}">
      {{messages.withdraw}}
      <template #footer>
        <Button label="はい" icon="pi pi-check" @click="closeBasic" autofocus />
      </template>
    </Dialog>
    <!--ダイアログ表示-->
    <Dialog header="エラー" v-model:visible="displayBasic" :style="{width: '50vw'}">
      {{messages.connect}}
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
import Textarea from 'primevue/textarea'
import Skeleton from 'primevue/skeleton'
import Dialog from 'primevue/dialog'

export default {
  name: 'Userself',
  components: {
    TabView,
    TabPanel,
    Textarea,
    Skeleton,
    Dialog
  },
  data () {
    return {
      user: {},
      value2: '',
      displayBasic: false,
      messages: {
        logout: '',
        withdrow: '',
        profile: '',
        submit: '',
        connect: ''
      }
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
              this.displayBasic = true
              this.messages.logout = 'ログアウトできませんでした。'
            })
        })
        .catch((err) => {
          console.log(err)
          this.displayBasic = true
          this.messages.logout = 'ログアウトできませんでした。'
        })
    },
    withdraw () {
      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.get('/api/Withdrawal')
            .then(res => {
              console.log(res)
              localStorage.setItem('authenticated', 'false')
              this.$router.push('/')
            })
            .catch(err => {
              console.log(err)
              this.displayBasic = true
              this.messages.withdraw = '退会できませんでした。'
            })
        })
        .catch((err) => {
          console.log(err)
          this.displayBasic = true
          this.messages.withdraw = '退会できませんでした。'
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
          console.log(err)
          this.displayBasic = true
          this.messages.connect = '接続に失敗しました。'
        })
    },
    closeBasic () {
      this.displayBasic = false
    }
  }
}
</script>

<style lang="scss" scoped>
a {
  text-decoration: none;
}

.pi {
  padding-right: 5px;
}

  .p-card-footer {
    .p-button {
      margin-right: 10px;
      border-radius: 25px;
    }
  }

  .p-card-content {
    .p-button {
      border-radius: 10px;
    }
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

  .create-topic {
    text-align: center;
  }

.create-profile {
  text-align: right;
  margin-top: 10px;
}

textarea {
  border-radius: 10px;
}

.logout {
  text-align: right;
}
</style>
