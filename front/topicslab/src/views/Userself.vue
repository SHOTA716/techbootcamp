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
        <div v-if="introEdit==false">
          {{user.name}}
          <Button type="button" v-on:click="introEdit = true">編集</button>
        </div>
        <div v-if="introEdit==true">
         <Textarea v-model="value" rows="5" cols="60" placeholder="自己紹介を追加"/>
          <div class="profbutton">
            <div v-if="intro==false">
              <Button label="プロフィールを公開" class="margin-prof" v-on:click="submit" />
              <Button type="button" v-on:click="introEdit = false">キャンセル</button>
            </div>
            <div v-else>
              <Button label="プロフィールを追加" class="margin-prof" v-on:click="submit" />
              <Button type="button" v-on:click="introEdit = false">キャンセル</button>
            </div>
          </div>
        </div>
         <TabView>
          <TabPanel header="Topics">
            <div v-for="(topic,key) in user.topics" :key="key">
              <div v-if="user !== null">
               {{topic.title}}
              </div>
              <div v-else>
               <Skeleton />
              </div>
              <span>
                <router-link :to="`/topic/${topic.id}`">トピックへ移動</router-link>
              </span>
            </div>
          </TabPanel>
          <TabPanel header="あなたのコメント">
            <div v-for="(comment,key) in user.comments" :key="key">
              <div v-if="user !== null">
                {{comment.body}}
               </div>
               <div v-else>
                <Skeleton />
               </div>
              <span>
                <router-link :to="`/topic/${comment.topic_id}`">トピックへ移動</router-link>
              </span>
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
      introEdit: false,
      intro: false,
      messages: {
        logout: '',
        withdrow: '',
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
  .p-card-footer {
    .p-button {
      margin-right: 10px;
    }
  }
  .margin-prof{
    margin-right: 10px;
  }
  // .profbutton{
  //   float: right;
  // }
  // TabView{
  //   margin-top: 600px;
  // }
</style>
